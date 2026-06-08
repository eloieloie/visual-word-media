const API_BASE = import.meta.env.VITE_API_BASE

async function request(endpoint, options = {}) {
  const method = options.method || 'GET'
  const url = `${API_BASE}${endpoint}`
  const token = localStorage.getItem('vwm_token')
  const headers = { 'Content-Type': 'application/json', ...(options.headers || {}) }
  if (token) headers['Authorization'] = `Bearer ${token}`

  console.log('[API] Request', {
    method,
    url,
    hasToken: !!token,
    payload: options.body || null,
  })

  const res = await fetch(url, { ...options, headers })
  const rawText = await res.text()
  const contentType = res.headers.get('content-type') || ''

  let data = null
  if (rawText) {
    try {
      data = JSON.parse(rawText)
    } catch {
      console.error('[API] Non-JSON response', {
        method,
        url,
        status: res.status,
        contentType,
        bodyPreview: rawText.slice(0, 300),
      })
    }
  }

  console.log('[API] Response', {
    method,
    url,
    status: res.status,
    ok: res.ok,
    contentType,
    bodyPreview: rawText.slice(0, 300),
  })

  if (!res.ok) {
    throw new Error(
      data?.message ||
      (rawText ? `Request failed (${res.status}): ${rawText.slice(0, 120)}` : `Request failed (${res.status})`)
    )
  }

  if (!data) {
    throw new Error(`Invalid JSON response from API (${res.status})`)
  }

  return data
}

export const api = {
  get:    (endpoint)        => request(endpoint),
  post:   (endpoint, body)  => request(endpoint, { method: 'POST',   body: JSON.stringify(body) }),
  put:    (endpoint, body)  => request(endpoint, { method: 'PUT',    body: JSON.stringify(body) }),
  delete: (endpoint)        => request(endpoint, { method: 'DELETE' }),
}
