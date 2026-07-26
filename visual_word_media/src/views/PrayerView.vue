<template>
  <div class="page-hero">
    <div class="container">
      <motion.p class="hero-label" :initial="{ opacity: 0, y: prefersReduced ? 0 : 14 }" :animate="{ opacity: 1, y: 0 }" :transition="{ duration: prefersReduced ? 0 : 0.6, ease: 'easeOut' }">We're Here For You</motion.p>
      <motion.h1 :initial="{ opacity: 0, y: prefersReduced ? 0 : 18 }" :animate="{ opacity: 1, y: 0 }" :transition="{ duration: prefersReduced ? 0 : 0.6, delay: prefersReduced ? 0 : 0.1, ease: 'easeOut' }">Prayer Request</motion.h1>
      <motion.p :initial="{ opacity: 0, y: prefersReduced ? 0 : 18 }" :animate="{ opacity: 1, y: 0 }" :transition="{ duration: prefersReduced ? 0 : 0.6, delay: prefersReduced ? 0 : 0.2, ease: 'easeOut' }">Share your prayer request with us — our prayer team will intercede on your behalf.</motion.p>
    </div>
  </div>

  <section class="section">
    <div class="container prayer-grid">

      <!-- LEFT: Info -->
      <div>
        <p class="section-label">How We Pray</p>
        <h2 class="section-title">Your Needs Matter to Us</h2>
        <div class="divider"></div>
        <p style="color:var(--text-light); line-height:1.85; margin-bottom:32px">
          At Visual Word Media Mission, we believe in the power of prayer. Our dedicated prayer team reads every request and lifts each need before God in sincere intercession.
        </p>

        <div class="prayer-promise-list">
          <div class="prayer-promise" v-for="p in promises" :key="p">
            <span class="promise-check"></span>
            <span>{{ p }}</span>
          </div>
        </div>

        <div class="scripture-block" style="margin-top:36px">
          "The effective, fervent prayer of a righteous man avails much."
          <span class="scripture-ref">— James 5:16</span>
        </div>

        <!-- Contact details -->
        <div class="contact-card">
          <h4>Contact Our Prayer Team</h4>
          <p>
            <a href="mailto:prayer@visualword.in">prayer@visualword.in</a>
          </p>
          <p style="color:var(--text-light); font-size:0.88rem; margin-top:6px">
            Ministry Office · Hyderabad, Telangana, India<br>
            Monday – Saturday, 9 AM – 6 PM IST
          </p>
        </div>
      </div>

      <!-- RIGHT: Form -->
      <div class="prayer-form-box">

        <!-- Success state -->
        <motion.div
          v-if="submitted" class="success-panel"
          :initial="{ opacity: 0, y: prefersReduced ? 0 : 16 }"
          :animate="{ opacity: 1, y: 0 }"
          :transition="{ duration: prefersReduced ? 0 : 0.5, ease: 'easeOut' }"
        >
          <div class="success-icon">🙏</div>
          <h3>Prayer Request Received</h3>
          <p>Thank you, <strong>{{ form.name }}</strong>. Our prayer team has received your request and will be interceding for you. A confirmation has been sent to <strong>{{ form.email }}</strong>.</p>
          <button class="btn btn-outline" style="margin-top:24px" @click="resetForm">Submit Another Request</button>
        </motion.div>

        <template v-else>
          <h3>Submit a Prayer Request</h3>
          <p>All requests are treated with care and confidentiality.</p>

          <div v-if="submitError" class="error-banner">⚠️ {{ submitError }}</div>

          <form @submit.prevent="submitForm" class="form">
            <div class="form-group">
              <label>Full Name *</label>
              <input v-model="form.name" type="text" placeholder="Your full name" required />
            </div>
            <div class="form-group">
              <label>Email Address *</label>
              <input v-model="form.email" type="email" placeholder="your@email.com" required />
            </div>
            <div class="form-group">
              <label>Phone Number <span class="optional">(optional)</span></label>
              <input v-model="form.phone" type="tel" placeholder="+91 00000 00000" />
            </div>
            <div class="form-group">
              <label>Prayer Request *</label>
              <textarea
                v-model="form.request"
                rows="6"
                placeholder="Share your prayer need here..."
                required
              ></textarea>
            </div>
            <div class="form-group">
              <label>Keep it confidential?</label>
              <div class="radio-group">
                <label class="radio-label">
                  <input type="radio" v-model="form.confidential" :value="true" />
                  Yes — keep my request private
                </label>
                <label class="radio-label">
                  <input type="radio" v-model="form.confidential" :value="false" />
                  No — it can be shared in prayer groups
                </label>
              </div>
              <p class="field-hint">This is for internal use only and does not affect who prays for you.</p>
            </div>

            <button type="submit" class="btn btn-primary submit-btn" :disabled="submitting">
              {{ submitting ? 'Sending…' : 'Submit Prayer Request' }}
            </button>
          </form>
        </template>

      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { motion, useReducedMotion } from 'motion-v'
import { api } from '../services/api.js'

const prefersReduced = useReducedMotion()
const submitted   = ref(false)
const submitting  = ref(false)
const submitError = ref('')

const form = reactive({
  name:         '',
  email:        '',
  phone:        '',
  request:      '',
  confidential: false,
})

async function submitForm() {
  submitting.value  = true
  submitError.value = ''
  try {
    await api.post('/prayer/index.php', {
      name:         form.name,
      email:        form.email,
      phone:        form.phone,
      request:      form.request,
      confidential: form.confidential,
    })
    submitted.value = true
  } catch (e) {
    submitError.value = e.message || 'Submission failed. Please try again.'
  } finally {
    submitting.value = false
  }
}

function resetForm() {
  submitted.value   = false
  submitError.value = ''
  Object.assign(form, {
    name: '', email: '', phone: '', request: '', confidential: false,
  })
}

const promises = [
  'Every prayer request is read by a real person',
  'Our team prays together each week',
  'Confidential requests are kept strictly private',
  'You will receive a personal acknowledgement by email',
]
</script>

<style scoped>
.prayer-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: start; }

/* ── Left column ── */
.prayer-promise-list { display: flex; flex-direction: column; gap: 12px; }
.prayer-promise {
  display: flex;
  align-items: flex-start;
  gap: 14px;
  font-size: 0.97rem;
  color: var(--text);
  line-height: 1.6;
}
.promise-check {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background: var(--gold);
  flex-shrink: 0;
  margin-top: 2px;
  position: relative;
}
.promise-check::after {
  content: '';
  position: absolute;
  left: 6px;
  top: 3px;
  width: 5px;
  height: 9px;
  border: 2px solid #fff;
  border-top: none;
  border-left: none;
  transform: rotate(45deg);
}

.contact-card {
  margin-top: 32px;
  padding: 24px 28px;
  border: 1px solid var(--border);
  border-radius: 8px;
  background: var(--section-bg);
}
.contact-card h4 { color: var(--navy); margin-bottom: 10px; font-size: 1rem; }
.contact-card a { color: var(--gold); font-weight: 600; text-decoration: none; font-size: 1rem; }
.contact-card a:hover { text-decoration: underline; }

/* ── Right: form box ── */
.prayer-form-box {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 44px 36px;
  box-shadow: 0 4px 24px rgba(26,45,90,0.08);
}
.prayer-form-box > h3 {
  font-family: var(--font-display);
  color: var(--navy);
  font-size: 1.6rem;
  margin-bottom: 10px;
}
.prayer-form-box > p { color: var(--text-light); font-size: 1rem; margin-bottom: 28px; }

.form { display: flex; flex-direction: column; gap: 20px; }
.form-group { display: flex; flex-direction: column; gap: 8px; }
.form-group label { font-size: 0.9rem; font-weight: 700; color: var(--navy); letter-spacing: 0.03em; }
.optional { font-weight: 400; color: var(--text-light); font-size: 0.82rem; }
.form-group input, .form-group textarea {
  padding: 13px 16px;
  border: 1.5px solid var(--border);
  border-radius: 6px;
  font-size: 1rem;
  font-family: inherit;
  color: var(--text);
  background: var(--cream);
  transition: border-color 0.2s;
  outline: none;
}
.form-group input:focus, .form-group textarea:focus {
  border-color: var(--gold);
  background: var(--white);
}
.form-group textarea { resize: vertical; }

.radio-group { display: flex; flex-direction: column; gap: 8px; }
.radio-label { display: flex; align-items: center; gap: 10px; font-size: 0.93rem; color: var(--text); cursor: pointer; }
.field-hint { font-size: 0.8rem; color: var(--text-light); margin-top: 4px; }

.error-banner {
  background: #fdecea;
  border: 1px solid #f5c6c4;
  border-radius: 6px;
  padding: 12px 16px;
  color: #c0392b;
  font-size: 0.92rem;
  margin-bottom: 20px;
}

.submit-btn { width: 100%; padding: 15px; font-size: 1rem; margin-top: 4px; }
.submit-btn:disabled { opacity: 0.65; cursor: not-allowed; }

/* ── Success panel ── */
.success-panel {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  padding: 20px 0;
}
.success-icon { font-size: 3.5rem; margin-bottom: 20px; }
.success-panel h3 { font-family: var(--font-display); color: var(--navy); font-size: 1.6rem; margin-bottom: 14px; }
.success-panel p { color: var(--text-light); line-height: 1.8; max-width: 380px; }

@media (max-width: 760px) {
  .prayer-grid { grid-template-columns: 1fr; }
  .prayer-form-box { padding: 32px 20px; }
}
</style>
