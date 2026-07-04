<template>
  <div class="auth-page">
    <div class="auth-card">
      <div class="auth-brand">
        <img class="auth-logo" :src="img('/images/vwm-logo.jpg')" alt="Visual Word Media logo" />
        <span class="auth-site">Visual Word Media</span>
      </div>

      <h2 class="auth-title">Welcome Back</h2>
      <p class="auth-sub">Sign in to access events and member content.</p>

      <form @submit.prevent="handleLogin" class="auth-form">
        <div class="field">
          <label>Email Address</label>
          <input
            v-model="email"
            type="email"
            placeholder="you@example.com"
            required
            autocomplete="email"
          />
        </div>

        <div class="field">
          <label>Password</label>
          <div class="password-wrap">
            <input
              v-model="password"
              :type="showPassword ? 'text' : 'password'"
              placeholder="••••••••"
              required
              autocomplete="current-password"
            />
            <button type="button" class="eye-btn" @click="showPassword = !showPassword" :aria-label="showPassword ? 'Hide password' : 'Show password'">
              <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              <svg v-else xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
            </button>
          </div>
          <RouterLink to="/forgot-password" class="forgot-link">Forgot password?</RouterLink>
        </div>

        <p v-if="error" class="auth-error">{{ error }}</p>

        <button type="submit" class="btn btn-primary auth-btn" :disabled="loading">
          <span v-if="loading">Signing in…</span>
          <span v-else>Sign In</span>
        </button>
      </form>

      <p class="auth-switch">Don't have an account? <RouterLink to="/register">Sign Up</RouterLink></p>

    </div>
  </div>
</template>

<script setup>
import { img } from '../composables/useBaseUrl.js'
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuth } from '../composables/useAuth.js'

const router   = useRouter()
const route    = useRoute()
const { login } = useAuth()

const email    = ref('')
const password = ref('')
const error    = ref('')
const loading  = ref(false)
const showPassword = ref(false)

async function handleLogin() {
  error.value   = ''
  loading.value = true
  try {
    await login(email.value, password.value)
    const redirect = route.query.redirect || '/'
    router.push(redirect)
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.auth-page {
  min-height: 100vh;
  min-height: 100svh;
  background: var(--cream);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px 16px;
}

.auth-card {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 48px 40px;
  width: 100%;
  max-width: 420px;
  box-shadow: 0 8px 40px rgba(26,45,90,0.10);
}

.auth-brand {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 32px;
}
.auth-logo { width: 32px; height: 32px; }
.auth-site  { font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: 700; color: var(--navy); }

.auth-title { font-family: 'Playfair Display', serif; font-size: 1.7rem; color: var(--navy); margin-bottom: 8px; }
.auth-sub   { color: var(--text-light); font-size: 0.97rem; margin-bottom: 32px; }

.auth-form  { display: flex; flex-direction: column; gap: 20px; }

.field { display: flex; flex-direction: column; gap: 7px; }
.field label { font-size: 0.88rem; font-weight: 600; color: var(--navy); letter-spacing: 0.03em; }
.field input {
  padding: 12px 14px;
  border: 1.5px solid var(--border);
  border-radius: 6px;
  font-size: 0.97rem;
  color: var(--text);
  background: var(--white);
  transition: border-color 0.2s;
  outline: none;
}
.field input:focus { border-color: var(--navy); }

.password-wrap {
  position: relative;
  display: flex;
  align-items: center;
}
.password-wrap input {
  flex: 1;
  padding-right: 44px;
}
.eye-btn {
  position: absolute;
  right: 12px;
  background: none;
  border: none;
  cursor: pointer;
  color: var(--text-light);
  padding: 0;
  display: flex;
  align-items: center;
  transition: color 0.2s;
}
.eye-btn:hover { color: var(--navy); }

.forgot-link {
  align-self: flex-end;
  font-size: 0.86rem;
  font-weight: 600;
  color: var(--gold);
}

.auth-error {
  color: #c0392b;
  font-size: 0.9rem;
  background: #fdf0ef;
  border: 1px solid #f5c6c4;
  border-radius: 6px;
  padding: 10px 14px;
  margin: 0;
}

.auth-btn {
  width: 100%;
  padding: 14px;
  font-size: 1rem;
  margin-top: 4px;
}
.auth-btn:disabled { opacity: 0.65; cursor: not-allowed; }

.auth-switch {
  text-align: center;
  margin-top: 24px;
  color: var(--text-light);
  font-size: 0.93rem;
}
.auth-switch a { color: var(--gold); font-weight: 600; }

@media (max-width: 480px) {
  .auth-card { padding: 36px 24px; }
}
</style>
