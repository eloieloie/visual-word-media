<template>
  <div class="auth-page">
    <div class="auth-card">
      <div class="auth-brand">
        <img class="auth-logo" src="/images/vwm-logo-mark.svg" alt="Visual Word Media logo" />
        <span class="auth-site">Visual Word Media</span>
      </div>

      <div v-if="success" class="success-screen">
        <div class="success-icon">✅</div>
        <h2 class="auth-title">Password Updated</h2>
        <p class="auth-sub">Your password has been set. You're all set — redirecting you now.</p>
      </div>

      <template v-else>
        <h2 class="auth-title">Set Your Password</h2>
        <p class="auth-sub">
          For security, please choose a new password before continuing.
        </p>

        <p v-if="error" class="auth-error">{{ error }}</p>

        <form @submit.prevent="handleSubmit" class="auth-form">
          <div class="field">
            <label>New Password <span class="field-hint">(min. 8 characters)</span></label>
            <div class="pw-wrap">
              <input
                v-model="password"
                :type="showPw ? 'text' : 'password'"
                placeholder="••••••••"
                required
                minlength="8"
                autocomplete="new-password"
              />
              <button type="button" class="eye-btn" @click="showPw = !showPw">
                <svg v-if="!showPw" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                  <circle cx="12" cy="12" r="3"/>
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                  <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                  <line x1="1" y1="1" x2="23" y2="23"/>
                </svg>
              </button>
            </div>
          </div>

          <div class="field">
            <label>Confirm New Password</label>
            <div class="pw-wrap">
              <input
                v-model="confirm"
                :type="showConfirm ? 'text' : 'password'"
                placeholder="••••••••"
                required
                minlength="8"
                autocomplete="new-password"
              />
              <button type="button" class="eye-btn" @click="showConfirm = !showConfirm">
                <svg v-if="!showConfirm" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                  <circle cx="12" cy="12" r="3"/>
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                  <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                  <line x1="1" y1="1" x2="23" y2="23"/>
                </svg>
              </button>
            </div>
          </div>

          <button type="submit" class="btn btn-primary auth-btn" :disabled="loading">
            {{ loading ? 'Saving…' : 'Set Password' }}
          </button>
        </form>
      </template>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { api } from '../services/api.js'
import { useAuth } from '../composables/useAuth.js'

const router      = useRouter()
const { clearForceReset } = useAuth()

const password    = ref('')
const confirm     = ref('')
const error       = ref('')
const loading     = ref(false)
const success     = ref(false)
const showPw      = ref(false)
const showConfirm = ref(false)

async function handleSubmit() {
  error.value = ''
  if (password.value !== confirm.value) {
    error.value = 'Passwords do not match'
    return
  }
  loading.value = true
  try {
    await api.post('/auth/set-password.php', {
      password:         password.value,
      confirm_password: confirm.value,
    })
    success.value = true
    clearForceReset()
    setTimeout(() => router.replace('/'), 1800)
  } catch (e) {
    error.value = e.message || 'Could not update password. Please try again.'
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
.auth-site { font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: 700; color: var(--navy); }
.auth-title { font-family: 'Playfair Display', serif; font-size: 1.7rem; color: var(--navy); margin-bottom: 8px; }
.auth-sub   { color: var(--text-light); font-size: 0.97rem; margin-bottom: 28px; line-height: 1.6; }
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
  box-sizing: border-box;
  width: 100%;
  padding-right: 44px;
}
.field input:focus { border-color: var(--navy); }
.pw-wrap { position: relative; }
.eye-btn {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
  color: var(--text-light);
  padding: 0;
  line-height: 1;
  display: flex;
  align-items: center;
}
.eye-btn:hover { color: var(--navy); }
.auth-error {
  color: #c0392b;
  font-size: 0.9rem;
  background: #fdf0ef;
  border: 1px solid #f5c6c4;
  border-radius: 6px;
  padding: 10px 14px;
}
.auth-btn { width: 100%; padding: 14px; font-size: 1rem; margin-top: 4px; }
.auth-btn:disabled { opacity: 0.65; cursor: not-allowed; }
.success-screen { text-align: center; padding: 16px 0; }
.success-icon { font-size: 3.5rem; margin-bottom: 16px; }
@media (max-width: 480px) {
  .auth-card { padding: 36px 20px; }
}
</style>
