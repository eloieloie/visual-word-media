<template>
  <div class="auth-page">
    <motion.div
      class="auth-card"
      :initial="{ opacity: 0, y: prefersReduced ? 0 : 20 }"
      :animate="{ opacity: 1, y: 0 }"
      :transition="{ duration: prefersReduced ? 0 : 0.5, ease: 'easeOut' }"
    >
      <div class="auth-brand">
        <img class="auth-logo" :src="img('/images/logo.png')" alt="Visual Word Media logo" />
        <span class="auth-site">Visual Word Media</span>
      </div>

      <h2 class="auth-title">Forgot Password</h2>
      <p class="auth-sub">Enter your email and we'll send a password reset link.</p>

      <form @submit.prevent="handleRequest" class="auth-form" v-if="!success">
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

        <p v-if="error" class="auth-error">{{ error }}</p>

        <button type="submit" class="btn btn-primary auth-btn" :disabled="loading">
          <span v-if="loading">Sending…</span>
          <span v-else>Send Reset Link</span>
        </button>
      </form>

      <div v-else class="auth-success">
        <h3>Reset link sent</h3>
        <p>If your email exists in our system, a reset link has been generated.</p>
        <a
          v-if="debugResetUrl"
          class="debug-link"
          :href="debugResetUrl"
        >
          Open reset page (dev)
        </a>
      </div>

      <p class="auth-switch">
        Remembered your password?
        <RouterLink to="/login">Back to Sign in</RouterLink>
      </p>
    </motion.div>
  </div>
</template>

<script setup>
import { img } from '../composables/useBaseUrl.js'
import { ref } from 'vue'
import { motion, useReducedMotion } from 'motion-v'
import { useAuth } from '../composables/useAuth.js'

const prefersReduced = useReducedMotion()
const { requestPasswordReset } = useAuth()

const email = ref('')
const error = ref('')
const loading = ref(false)
const success = ref(false)
const debugResetUrl = ref('')

async function handleRequest() {
  error.value = ''
  loading.value = true
  try {
    const data = await requestPasswordReset(email.value)
    debugResetUrl.value = data.reset_url || ''
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
.auth-site  { font-family: var(--font-display); font-size: 1.1rem; font-weight: 700; color: var(--navy); }

.auth-title { font-family: var(--font-display); font-size: 1.7rem; color: var(--navy); margin-bottom: 8px; }
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
  margin-bottom: 20px;
}
.auth-success h3 { color: var(--navy); margin-bottom: 10px; }
.auth-success p  { color: var(--text-light); }
.debug-link {
  margin-top: 10px;
  display: inline-block;
  color: var(--gold);
  font-weight: 600;
}

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
