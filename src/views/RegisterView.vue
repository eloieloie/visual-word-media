<template>
  <div class="auth-page">
    <div class="auth-card">
      <div class="auth-brand">
        <span class="auth-cross">✝</span>
        <span class="auth-site">Visual Word Media</span>
      </div>

      <h2 class="auth-title">Create an Account</h2>
      <p class="auth-sub">Join our community to access events and ministry resources.</p>

      <form @submit.prevent="handleRegister" class="auth-form" v-if="!success">
        <div class="field">
          <label>Full Name</label>
          <input
            v-model="name"
            type="text"
            placeholder="Your full name"
            required
            autocomplete="name"
          />
        </div>

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
          <label>Password <span class="field-hint">(min. 8 characters)</span></label>
          <input
            v-model="password"
            type="password"
            placeholder="••••••••"
            required
            autocomplete="new-password"
          />
        </div>

        <div class="field">
          <label>Confirm Password</label>
          <input
            v-model="confirm"
            type="password"
            placeholder="••••••••"
            required
            autocomplete="new-password"
          />
        </div>

        <p v-if="error" class="auth-error">{{ error }}</p>

        <button type="submit" class="btn btn-primary auth-btn" :disabled="loading">
          <span v-if="loading">Creating account…</span>
          <span v-else>Create Account</span>
        </button>
      </form>

      <div v-else class="auth-success">
        <span class="success-icon">✓</span>
        <h3>Account created!</h3>
        <p>You can now sign in with your credentials.</p>
        <RouterLink to="/login" class="btn btn-primary auth-btn" style="display:block; text-align:center; margin-top:16px">
          Go to Login
        </RouterLink>
      </div>

      <p class="auth-switch" v-if="!success">
        Already have an account?
        <RouterLink to="/login">Sign in</RouterLink>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuth } from '../composables/useAuth.js'

const { register } = useAuth()

const name     = ref('')
const email    = ref('')
const password = ref('')
const confirm  = ref('')
const error    = ref('')
const loading  = ref(false)
const success  = ref(false)

async function handleRegister() {
  error.value = ''

  console.log('[Register] Submit start', {
    name: name.value,
    email: email.value,
    passwordLength: password.value.length,
    confirmLength: confirm.value.length,
  })

  if (password.value !== confirm.value) {
    console.warn('[Register] Password mismatch')
    error.value = 'Passwords do not match'
    return
  }

  loading.value = true
  try {
    await register(name.value, email.value, password.value)
    console.log('[Register] Success', { email: email.value })
    success.value = true
  } catch (e) {
    console.error('[Register] Failed', e)
    error.value = e.message
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.auth-page {
  min-height: 100vh;
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
.auth-cross { font-size: 1.6rem; color: var(--gold); }
.auth-site  { font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: 700; color: var(--navy); }

.auth-title { font-family: 'Playfair Display', serif; font-size: 1.7rem; color: var(--navy); margin-bottom: 8px; }
.auth-sub   { color: var(--text-light); font-size: 0.97rem; margin-bottom: 32px; }

.auth-form  { display: flex; flex-direction: column; gap: 20px; }

.field { display: flex; flex-direction: column; gap: 7px; }
.field label { font-size: 0.88rem; font-weight: 600; color: var(--navy); letter-spacing: 0.03em; }
.field-hint  { font-weight: 400; color: var(--text-light); }
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

.auth-success {
  text-align: center;
  padding: 24px 0 8px;
}
.success-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 56px; height: 56px;
  border-radius: 50%;
  background: #e8f5e9;
  color: #2e7d32;
  font-size: 1.6rem;
  font-weight: 700;
  margin-bottom: 16px;
}
.auth-success h3 { color: var(--navy); margin-bottom: 8px; }
.auth-success p  { color: var(--text-light); }

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
