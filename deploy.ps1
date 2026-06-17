# ============================================================
#  VWM Backend FTP Deploy Script
#  Target : ftp://82.112.239.162  user: u417273443.u417273443
#  FTP root is already public_html, so paths start at /
#  Uploads: vwm_backend/ -> /vwm_backend/
# ============================================================

param(
    [switch]$IncludeInitDb   # include init_db.php (excluded by default)
)

$FTP_HOST  = "82.112.239.162"
$FTP_USER  = "u417273443.u417273443"
$FTP_PASS  = "Honor@new2026"

$BACKEND_LOCAL   = "C:\Users\hi\Documents\Projects\vwm\vwm_backend"
$BACKEND_EXCLUDE = @('init_db.php')

# -- Helpers --------------------------------------------------

function New-FtpDir([string]$path) {
    if ($path -eq "") { return }   # FTP root already exists
    $uri = "ftp://$FTP_HOST$path"
    try {
        $req = [System.Net.FtpWebRequest]::Create($uri)
        $req.Credentials = New-Object System.Net.NetworkCredential($FTP_USER, $FTP_PASS)
        $req.Method      = [System.Net.WebRequestMethods+Ftp]::MakeDirectory
        $req.UsePassive  = $true
        $req.UseBinary   = $true
        $req.KeepAlive   = $false
        $req.GetResponse().Close()
        Write-Host "  mkdir $path" -ForegroundColor DarkCyan
    } catch {
        $inner = $_.Exception.InnerException
        if ($inner) { $msg = $inner.Message } else { $msg = $_.Exception.Message }
        if ($msg -match '550') {
            Write-Host "  exist $path" -ForegroundColor DarkGray
        } else {
            Write-Host "  [warn] mkdir $path : $msg" -ForegroundColor Yellow
        }
    }
}

function Send-File([string]$local, [string]$remote) {
    $uri = "ftp://$FTP_HOST$remote"
    try {
        $bytes = [System.IO.File]::ReadAllBytes($local)
        $req = [System.Net.FtpWebRequest]::Create($uri)
        $req.Credentials   = New-Object System.Net.NetworkCredential($FTP_USER, $FTP_PASS)
        $req.Method        = [System.Net.WebRequestMethods+Ftp]::UploadFile
        $req.UsePassive    = $true
        $req.UseBinary     = $true
        $req.KeepAlive     = $false
        $req.ContentLength = $bytes.Length
        $stream = $req.GetRequestStream()
        $stream.Write($bytes, 0, $bytes.Length)
        $stream.Close()
        $req.GetResponse().Close()
        Write-Host "  + $remote" -ForegroundColor Green
        $script:uploaded++
    } catch {
        $inner = $_.Exception.InnerException
        if ($inner) { $msg = $inner.Message } else { $msg = $_.Exception.Message }
        Write-Host "  x FAILED $remote`n    $msg" -ForegroundColor Red
    }
}

function Get-LocalTree([string]$localDir, [string]$remoteDir, [string[]]$exclude) {
    $dirs  = [System.Collections.Generic.List[string]]::new()
    $files = [System.Collections.Generic.List[hashtable]]::new()

    $dirs.Add($remoteDir)

    Get-ChildItem -LiteralPath $localDir | ForEach-Object {
        if ($exclude -contains $_.Name) { return }
        $remotePath = "$remoteDir/$($_.Name)"
        if ($_.PSIsContainer) {
            $sub = Get-LocalTree -localDir $_.FullName -remoteDir $remotePath -exclude $exclude
            foreach ($d in $sub.dirs)  { $dirs.Add($d) }
            foreach ($f in $sub.files) { $files.Add($f) }
        } else {
            $files.Add(@{ local = $_.FullName; remote = $remotePath })
        }
    }

    return @{ dirs = $dirs; files = $files }
}

# -- Main -----------------------------------------------------

Write-Host ""
Write-Host "================================================" -ForegroundColor Cyan
Write-Host "  VWM Backend Deploy" -ForegroundColor Cyan
Write-Host "  $BACKEND_LOCAL" -ForegroundColor Cyan
Write-Host "  -> https://lightsalmon-porpoise-885538.hostingersite.com/" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

if ($IncludeInitDb) {
    $exclude = @()
} else {
    $exclude = $BACKEND_EXCLUDE
    Write-Host "  (init_db.php excluded - use -IncludeInitDb to upload it)" -ForegroundColor DarkGray
    Write-Host ""
}

$tree = Get-LocalTree -localDir $BACKEND_LOCAL -remoteDir "" -exclude $exclude

# Pass 1 - create all directories (parent before child)
Write-Host "Creating directories..." -ForegroundColor Cyan
foreach ($dir in $tree.dirs) { New-FtpDir $dir }

# Pass 2 - upload all files
Write-Host ""
Write-Host "Uploading files..." -ForegroundColor Cyan
$script:uploaded = 0
$startTime = Get-Date

foreach ($file in $tree.files) {
    Send-File -local $file.local -remote $file.remote
}

$elapsed = [math]::Round(((Get-Date) - $startTime).TotalSeconds, 1)
Write-Host ""
Write-Host "================================================" -ForegroundColor Cyan
Write-Host "  Done!  $($script:uploaded) files uploaded in ${elapsed}s" -ForegroundColor Green
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""
