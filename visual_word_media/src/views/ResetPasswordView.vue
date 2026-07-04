<template>
  <div class="auth-page">
    <div class="auth-card">
      <div class="auth-brand">
        <img class="auth-logo" src="/images/vwm-logo.jpg" alt="Visual Word Media logo" />
        <span class="auth-site">Visual Word Media</span>
      </div>

      <h2 class="auth-title">Reset Password</h2>
      <p class="auth-sub">Set a new password for your account.</p>

      <p v-if="missingToken" class="auth-error">
        Reset token is missing or invalid. Please request a new reset link.
      </p>

      <form @submit.prevent="handleReset" class="auth-form" v-else-if="!success">
        <div class="field">
          <label>New Password <span class="field-hint">(min. 8 characters)</span></label>
          <input
            v-model="password"
            type="password"
            placeholder="••••••••"
            required
            autocomplete="new-password"
          />
        </div>

        <div class="field">
          <label>Confirm New Password</label>
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
          <span v-if="loading">Resetting…</span>
          <span v-else>Reset Password</span>
        </button>
      </form>

      <div v-else class="auth-success">
        <h3>Password reset successful</h3>
        <p>You can now sign in with your new password.</p>
        <RouterLink to="/login" class="btn btn-primary auth-btn">Go to Login</RouterLink>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { useRoute } from 'vue-router'
import { useAuth } from '../composables/useAuth.js'

const route = useRoute()
const { resetPassword } = useAuth()

const password = ref('')
const confirm = ref('')
const error = ref('')
const loading = ref(false)
const success = ref(false)
const token = computed(() => String(route.query.token || ''))
const missingToken = computed(() => !token.value)

async function handleReset() {
  error.value = ''

  if (password.value.length < 8) {
    error.value = 'Password must be at least 8 characters'
    return
  }

  if (password.value !== confirm.value) {
    error.value = 'Passwords do not match'
    return
  }

  loading.value = true
  try {
    await resetPassword(token.value, password.value)
    success.value = true
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
}

.auth-success {
  text-align: center;
}
.auth-success h3 { color: var(--navy); margin-bottom: 10px; }
.auth-success p  { color: var(--text-light); margin-bottom: 20px; }

@media (max-width: 480px) {
  .auth-card { padding: 36px 24px; }
}
</style>
