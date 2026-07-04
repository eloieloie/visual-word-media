/**
 * Returns a helper that prepends Vite's BASE_URL to any /images/... path.
 * Works correctly whether the app is served at domain root (BASE_URL = '/')
 * or at a subpath like /visual-word-media/ on GitHub Pages.
 */
export function img(path) {
  const base = import.meta.env.BASE_URL.replace(/\/$/, '')
  return base + path
}
