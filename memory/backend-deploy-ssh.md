---
name: backend-deploy-ssh
description: How the PHP backend deploys to Hostinger and why FTP fails from CI
metadata:
  type: project
---

The `vwm_backend/` PHP backend deploys to Hostinger shared hosting. Hostinger's firewall **blocks plain FTP (port 21) from datacenter/cloud IPs** (GitHub Actions runners), so FTP-from-CI times out with `Timeout (control socket)`. `deploy.ps1` (FTP) only works from whitelisted/local networks.

**Working CI path:** SSH/rsync on **port 65002** via `.github/workflows/deploy-backend.yml` (`easingthemes/ssh-deploy`), key-based auth. Deploys to `/home/u417273443/domains/lightsalmon-porpoise-885538.hostingersite.com/public_html`. Triggers on push to `master` (paths `vwm_backend/**`) or manual `workflow_dispatch`. Uses **no `--delete`** to preserve server-only `uploads/`.

Mailgun secret: `config/secrets.php` is gitignored and **regenerated in CI** from the `MAILGUN_API_KEY` GitHub secret (`putenv`). Mail sends via the verified custom domain `mg.visualword.in` (US region). Test delivery by triggering `/api/auth/forgot-password.php` then checking the Mailgun events API — the endpoint returns a generic 200 and swallows mail errors, so the HTTP response never confirms delivery.

GitHub secrets in use: `SSH_PRIVATE_KEY`, `SSH_HOST`, `SSH_USER`, `SSH_PORT`, `MAILGUN_API_KEY` (plus legacy `FTP_*`).
