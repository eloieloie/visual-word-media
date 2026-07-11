import { ref, computed } from 'vue'
import { api } from '../services/api.js'

// Module-level state so it's shared across components
const user  = ref(JSON.parse(localStorage.getItem('vwm_user') || 'null'))
const token = ref(localStorage.getItem('vwm_token') || null)

export function useAuth() {
  const isLoggedIn = computed(() => !!token.value)
  const isAdmin    = computed(() => user.value?.role === 'admin')

  async function login(email, password) {
    const data = await api.post('/auth/login.php', { email, password })
    token.value = data.token
    user.value  = data.user
    localStorage.setItem('vwm_token', data.token)
    localStorage.setItem('vwm_user',  JSON.stringify(data.user))
    return data
  }

  async function register(name, email, password) {
    return await api.post('/auth/register.php', { name, email, password })
  }

  async function requestPasswordReset(email) {
    return await api.post('/auth/forgot-password.php', { email })
  }

  async function resetPassword(token, password) {
    return await api.post('/auth/reset-password.php', { token, password })
  }

  function logout() {
    token.value = null
    user.value  = null
    localStorage.removeItem('vwm_token')
    localStorage.removeItem('vwm_user')
  }

  const needsPasswordReset = computed(() => !!user.value?.force_password_reset)

  function clearForceReset() {
    if (user.value) {
      user.value = { ...user.value, force_password_reset: false }
      localStorage.setItem('vwm_user', JSON.stringify(user.value))
    }
  }

  return {
    user,
    token,
    isLoggedIn,
    isAdmin,
    needsPasswordReset,
    login,
    register,
    requestPasswordReset,
    resetPassword,
    clearForceReset,
    logout,
  }
}
