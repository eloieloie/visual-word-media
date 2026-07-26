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

      <!-- ── Success screen ── -->
      <div v-if="success" class="success-screen">
        <div class="success-icon">✉️</div>
        <h2 class="auth-title">Check Your Email</h2>
        <p class="auth-sub">
          Your registration has been submitted. A confirmation email has been sent to
          <strong>{{ submittedEmail }}</strong>.
        </p>
        <p class="auth-sub" style="margin-top:12px">
          Your account is <strong>pending admin approval</strong>. You will receive another
          email once approved.
        </p>
        <RouterLink to="/login" class="btn btn-primary auth-btn" style="display:block;text-align:center;margin-top:28px">
          Back to Sign In
        </RouterLink>
      </div>

      <template v-else>
        <h2 class="auth-title">Create an Account</h2>
        <p class="auth-sub">Join our community — all fields are required.</p>

        <p v-if="error" class="auth-error">{{ error }}</p>

        <form @submit.prevent="handleRegister" class="auth-form">

          <!-- Personal -->
          <div class="form-section-label">Personal Details</div>

          <div class="field">
            <label>Full Name</label>
            <input v-model="form.name" type="text" placeholder="Your full name" required autocomplete="name" />
          </div>

          <div class="field">
            <label>Email Address</label>
            <input v-model="form.email" type="email" placeholder="you@example.com" required autocomplete="email" />
          </div>

          <div class="field">
            <label>Mobile Number</label>
            <input v-model="form.mobile" type="tel" placeholder="+91 00000 00000" required autocomplete="tel" />
          </div>

          <div class="field">
            <label>Gender</label>
            <select v-model="form.gender" required>
              <option value="" disabled>Select gender</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
          </div>

          <!-- Church -->
          <div class="form-section-label" style="margin-top:8px">Church Details</div>

          <div class="field">
            <label>Church Name</label>
            <input v-model="form.church_name" type="text" placeholder="Your church name" required />
          </div>

          <div class="field">
            <label>Church Address</label>
            <textarea v-model="form.church_address" rows="3" placeholder="Full church address" required></textarea>
          </div>

          <div class="field">
            <label>Pastor / Elder Referral Name</label>
            <input v-model="form.referral_name" type="text" placeholder="Referring pastor or elder" required />
          </div>

          <div class="field">
            <label>Referral Mobile Number</label>
            <input v-model="form.referral_mobile" type="tel" placeholder="+91 00000 00000" required />
          </div>

          <!-- Account -->
          <div class="form-section-label" style="margin-top:8px">Account Credentials</div>

          <div class="field">
            <label>Username</label>
            <input v-model="form.username" type="text" placeholder="Choose a username" required autocomplete="username"
              pattern="[a-zA-Z0-9_\-]{3,50}" title="3–50 characters: letters, numbers, _ or -" />
          </div>

          <div class="field">
            <label>Password <span class="field-hint">(min. 8 characters)</span></label>
            <div class="pw-wrap">
              <input
                v-model="form.password"
                :type="showPw ? 'text' : 'password'"
                placeholder="••••••••"
                required
                autocomplete="new-password"
                minlength="8"
              />
              <button type="button" class="eye-btn" @click="showPw = !showPw" :aria-label="showPw ? 'Hide password' : 'Show password'">
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
            <label>Confirm Password</label>
            <div class="pw-wrap">
              <input
                v-model="form.confirm_password"
                :type="showConfirm ? 'text' : 'password'"
                placeholder="••••••••"
                required
                autocomplete="new-password"
                minlength="8"
              />
              <button type="button" class="eye-btn" @click="showConfirm = !showConfirm" aria-label="Toggle">
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
            {{ loading ? 'Submitting…' : 'Create Account' }}
          </button>
        </form>

        <p class="auth-switch">
          Already have an account?
          <RouterLink to="/login">Sign in</RouterLink>
        </p>
      </template>
    </motion.div>
  </div>
</template>

<script setup>
import { img } from '../composables/useBaseUrl.js'
import { ref, reactive } from 'vue'
import { motion, useReducedMotion } from 'motion-v'
import { api } from '../services/api.js'

const prefersReduced = useReducedMotion()
const loading       = ref(false)
const success       = ref(false)
const error         = ref('')
const submittedEmail = ref('')
const showPw        = ref(false)
const showConfirm   = ref(false)

const form = reactive({
  name:             '',
  email:            '',
  mobile:           '',
  gender:           '',
  church_name:      '',
  church_address:   '',
  referral_name:    '',
  referral_mobile:  '',
  username:         '',
  password:         '',
  confirm_password: '',
})

async function handleRegister() {
  error.value   = ''
  if (form.password !== form.confirm_password) {
    error.value = 'Passwords do not match'
    return
  }
  loading.value = true
  try {
    await api.post('/auth/register.php', { ...form })
    submittedEmail.value = form.email
    success.value = true
  } catch (e) {
    error.value = e.message || 'Registration failed. Please try again.'
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
  align-items: flex-start;
  justify-content: center;
  padding: 48px 16px;
}

.auth-card {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 48px 40px;
  width: 100%;
  max-width: 520px;
  box-shadow: 0 8px 40px rgba(26,45,90,0.10);
}

.auth-brand {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 32px;
}
.auth-logo { width: 32px; height: 32px; }
.auth-site { font-family: var(--font-display); font-size: 1.1rem; font-weight: 700; color: var(--navy); }

.auth-title { font-family: var(--font-display); font-size: 1.7rem; color: var(--navy); margin-bottom: 8px; }
.auth-sub   { color: var(--text-light); font-size: 0.97rem; margin-bottom: 24px; line-height: 1.6; }

.form-section-label {
  font-size: 0.78rem;
  font-weight: 700;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: var(--gold);
  padding-bottom: 6px;
  border-bottom: 1px solid var(--border);
  margin-bottom: 4px;
}

.auth-form { display: flex; flex-direction: column; gap: 18px; }

.field { display: flex; flex-direction: column; gap: 7px; }
.field label { font-size: 0.88rem; font-weight: 600; color: var(--navy); letter-spacing: 0.03em; }
.field-hint  { font-weight: 400; color: var(--text-light); }

.field input, .field select, .field textarea {
  padding: 12px 14px;
  border: 1.5px solid var(--border);
  border-radius: 6px;
  font-size: 0.97rem;
  color: var(--text);
  background: var(--white);
  font-family: inherit;
  transition: border-color 0.2s;
  outline: none;
}
.field input:focus, .field select:focus, .field textarea:focus { border-color: var(--navy); }
.field textarea { resize: vertical; }

.pw-wrap { position: relative; }
.pw-wrap input { width: 100%; box-sizing: border-box; padding-right: 44px; }
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
  margin: 0;
}

.auth-btn {
  width: 100%;
  padding: 14px;
  font-size: 1rem;
  margin-top: 4px;
}
.auth-btn:disabled { opacity: 0.65; cursor: not-allowed; }

/* Success */
.success-screen { text-align: center; padding: 16px 0; }
.success-icon { font-size: 3.5rem; margin-bottom: 16px; }

.auth-switch {
  text-align: center;
  margin-top: 24px;
  color: var(--text-light);
  font-size: 0.93rem;
}
.auth-switch a { color: var(--gold); font-weight: 600; }

@media (max-width: 480px) {
  .auth-card { padding: 36px 20px; }
}
</style>
