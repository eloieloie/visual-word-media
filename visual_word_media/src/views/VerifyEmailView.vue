<template>
  <div class="auth-page">
    <div class="auth-card">
      <div class="auth-brand">
        <img class="auth-logo" src="/images/vwm-logo.jpg" alt="Visual Word Media logo" />
        <span class="auth-site">Visual Word Media</span>
      </div>

      <h2 class="auth-title">Email Verification</h2>

      <!-- Loading -->
      <div v-if="loading" class="auth-state">
        <p class="auth-sub">Verifying your email address…</p>
      </div>

      <!-- Success -->
      <div v-else-if="verified" class="auth-success">
        <div class="verify-icon ok">&#10003;</div>
        <h3>Email verified!</h3>
        <p>
          Thank you — your email address has been confirmed.
          Your details are now <strong>awaiting admin review</strong>.
        </p>
        <p class="auth-note">
          Once our team has reviewed your registration, your login credentials
          will be shared with you via email.
        </p>
        <RouterLink to="/" class="btn btn-primary auth-btn">Back to Home</RouterLink>
      </div>

      <!-- Error -->
      <div v-else class="auth-state">
        <div class="verify-icon err">!</div>
        <p class="auth-error">{{ error }}</p>
        <RouterLink to="/volunteer" class="btn btn-outline auth-btn">Back to Registration</RouterLink>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import { api } from '../services/api.js'

const route = useRoute()
const token = computed(() => String(route.query.token || ''))

const loading = ref(true)
const verified = ref(false)
const error = ref('')

onMounted(async () => {
  if (!token.value) {
    error.value = 'Verification link is missing or invalid.'
    loading.value = false
    return
  }
  try {
    const res = await api.post('/volunteers/verify.php', { token: token.value })
    verified.value = !!res.verified
  } catch (e) {
    error.value = e.message || 'We could not verify your email. The link may be invalid or expired.'
  } finally {
    loading.value = false
  }
})
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
  max-width: 440px;
  box-shadow: 0 8px 40px rgba(26,45,90,0.10);
  text-align: center;
}
.auth-brand {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  margin-bottom: 28px;
}
.auth-logo { width: 32px; height: 32px; }
.auth-site { font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: 700; color: var(--navy); }
.auth-title { font-family: 'Playfair Display', serif; font-size: 1.7rem; color: var(--navy); margin-bottom: 20px; }
.auth-sub { color: var(--text-light); font-size: 0.97rem; }
.auth-note { color: var(--text-light); font-size: 0.9rem; margin-top: 10px; }

.verify-icon {
  width: 64px; height: 64px;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 2rem; font-weight: 700;
  margin: 0 auto 18px;
}
.verify-icon.ok  { background: #e8f5e9; color: #276027; }
.verify-icon.err { background: #fdecea; color: #c62828; }

.auth-success h3 { color: var(--navy); margin-bottom: 12px; }
.auth-success p { color: var(--text-light); line-height: 1.7; }

.auth-error {
  color: #c0392b;
  font-size: 0.95rem;
  background: #fdf0ef;
  border: 1px solid #f5c6c4;
  border-radius: 6px;
  padding: 12px 16px;
}

.auth-btn { width: 100%; padding: 14px; font-size: 1rem; margin-top: 22px; }

@media (max-width: 480px) {
  .auth-card { padding: 36px 24px; }
}
</style>
