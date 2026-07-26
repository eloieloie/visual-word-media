<template>
  <div class="page-hero">
    <div class="container">
      <motion.p class="hero-label" :initial="{ opacity: 0, y: prefersReduced ? 0 : 14 }" :animate="{ opacity: 1, y: 0 }" :transition="{ duration: prefersReduced ? 0 : 0.6, ease: 'easeOut' }">We'd Love to Hear From You</motion.p>
      <motion.h1 :initial="{ opacity: 0, y: prefersReduced ? 0 : 18 }" :animate="{ opacity: 1, y: 0 }" :transition="{ duration: prefersReduced ? 0 : 0.6, delay: prefersReduced ? 0 : 0.1, ease: 'easeOut' }">Contact Us</motion.h1>
      <motion.p :initial="{ opacity: 0, y: prefersReduced ? 0 : 18 }" :animate="{ opacity: 1, y: 0 }" :transition="{ duration: prefersReduced ? 0 : 0.6, delay: prefersReduced ? 0 : 0.2, ease: 'easeOut' }">We desire to serve today's generation in the will of God through truth, creativity, discipleship, and compassionate media engagement.</motion.p>
    </div>
  </div>

  <section class="section">
    <div class="container contact-grid">
      <!-- CONTACT INFO -->
      <div>
        <p class="section-label">Get in Touch</p>
        <h2 class="section-title">Reach Out to the Ministry</h2>
        <div class="divider"></div>

        <div class="contact-info-list">
          <div class="contact-info-item">
            <img class="ci-photo" src="/images/contact/office.svg" alt="Ministry office" />
            <div>
              <h4>Ministry Office</h4>
              <p>Hyderabad, Telangana, India</p>
            </div>
          </div>
          <div class="contact-info-item">
            <img class="ci-photo" src="/images/contact/phone.svg" alt="Contact phone" />
            <div>
              <h4>Contact Number</h4>
              <p>Available via contact form or in-person</p>
            </div>
          </div>
          <div class="contact-info-item">
            <img class="ci-photo" src="/images/contact/email.svg" alt="Email address" />
            <div>
              <h4>Email Address</h4>
              <p>Available via contact form below</p>
            </div>
          </div>
          <div class="contact-info-item">
            <img class="ci-photo" src="/images/contact/hours.svg" alt="Ministry hours" />
            <div>
              <h4>Ministry Hours</h4>
              <p>Monday – Saturday, 9 AM – 6 PM IST</p>
            </div>
          </div>
        </div>

        <div class="scripture-block" style="margin-top:36px">
          "We desire to serve today's generation in the will of God through truth, creativity, discipleship, and compassionate media engagement."
          <span class="scripture-ref">— Visual Word Media Mission</span>
        </div>
      </div>

      <!-- CONTACT FORM -->
      <div class="contact-form-box">
        <h3>Send Us a Message</h3>
        <p>Fill in the form and our team will get back to you shortly.</p>
        <form @submit.prevent="submitContact" class="form">
          <div class="form-group">
            <label>Full Name *</label>
            <input v-model="form.name" type="text" placeholder="Your name" required />
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Email Address *</label>
              <input v-model="form.email" type="email" placeholder="your@email.com" required />
            </div>
            <div class="form-group">
              <label>Phone / WhatsApp</label>
              <input v-model="form.phone" type="tel" placeholder="+91 00000 00000" />
            </div>
          </div>
          <div class="form-group">
            <label>Subject / Inquiry Type</label>
            <select v-model="form.subject">
              <option value="">Select a subject</option>
              <option>General Inquiry</option>
              <option>Prayer Request</option>
              <option>Volunteer Registration</option>
              <option>Ministry Partnership</option>
              <option>Event Information</option>
              <option>Resource Request</option>
              <option>Share a Testimony</option>
              <option>Counseling Support</option>
              <option>Media Collaboration</option>
            </select>
          </div>
          <div class="form-group">
            <label>Your Message *</label>
            <textarea v-model="form.message" rows="6" placeholder="Share your message, prayer request, or inquiry..." required></textarea>
          </div>
          <div class="form-group">
            <label class="checkbox-label">
              <input type="checkbox" v-model="form.newsletter" />
              Subscribe to ministry updates and prayer newsletter
            </label>
          </div>
          <motion.div v-if="error" class="form-error" :initial="{ opacity: 0, y: prefersReduced ? 0 : -8 }" :animate="{ opacity: 1, y: 0 }" :transition="{ duration: prefersReduced ? 0 : 0.35, ease: 'easeOut' }">{{ error }}</motion.div>
          <motion.div v-if="submitted" class="form-success" :initial="{ opacity: 0, y: prefersReduced ? 0 : -8 }" :animate="{ opacity: 1, y: 0 }" :transition="{ duration: prefersReduced ? 0 : 0.35, ease: 'easeOut' }">
            Message sent — God bless you! Check your inbox for a confirmation email. We'll be in touch soon.
          </motion.div>
          <button type="submit" class="btn btn-primary submit-btn" :disabled="loading">
            {{ loading ? 'Sending…' : 'Send Message' }}
          </button>
        </form>
      </div>
    </div>
  </section>

  <!-- CLOSING -->
  <section class="section-sm" style="background:var(--navy); text-align:center">
    <div class="container" style="max-width:720px">
      <p class="section-label" style="color:var(--gold)">Join the Mission</p>
      <h2 style="color:var(--white)">Be Part of What God Is Doing</h2>
      <div class="divider divider-center"></div>
      <p style="color:rgba(255,255,255,0.75); line-height:1.85; margin-bottom:28px">
        As God continues to open doors through media, discipleship, youth development, village outreach, leadership training, and evangelism, we invite you to partner with us in fulfilling the Great Commission.
      </p>
      <div style="display:flex; gap:14px; justify-content:center; flex-wrap:wrap">
        <RouterLink to="/volunteer" class="btn btn-primary">Join the Mission</RouterLink>
        <RouterLink to="/prayer" class="btn btn-outline">Become a Prayer Partner</RouterLink>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { motion, useReducedMotion } from 'motion-v'
import { api } from '../services/api.js'

const prefersReduced = useReducedMotion()
const submitted = ref(false)
const loading = ref(false)
const error = ref('')
const form = reactive({ name: '', email: '', phone: '', subject: '', message: '', newsletter: false })

async function submitContact() {
  error.value = ''
  loading.value = true
  try {
    await api.post('/contact/', {
      name:       form.name,
      email:      form.email,
      phone:      form.phone,
      subject:    form.subject,
      message:    form.message,
      newsletter: form.newsletter,
    })
    submitted.value = true
    Object.assign(form, { name: '', email: '', phone: '', subject: '', message: '', newsletter: false })
  } catch (err) {
    error.value = err.message || 'Something went wrong. Please try again.'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: start; }

.contact-info-list { display: flex; flex-direction: column; gap: 16px; margin-bottom: 32px; }
.contact-info-item { display: flex; align-items: flex-start; gap: 16px; }
.ci-photo { width: 52px; height: 52px; border-radius: 6px; object-fit: cover; flex-shrink: 0; }
.contact-info-item h4 { font-size: 0.95rem; font-weight: 700; color: var(--navy); margin-bottom: 4px; }
.contact-info-item p { font-size: 1rem; color: var(--text-light); line-height: 1.6; }

.contact-form-box {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 44px 36px;
  box-shadow: 0 4px 24px rgba(26,45,90,0.08);
}
.contact-form-box h3 { font-family: var(--font-display); color: var(--navy); font-size: 1.6rem; margin-bottom: 8px; }
.contact-form-box > p { color: var(--text-light); font-size: 1rem; margin-bottom: 32px; }

.form { display: flex; flex-direction: column; gap: 20px; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
.form-group { display: flex; flex-direction: column; gap: 8px; }
.form-group label { font-size: 0.9rem; font-weight: 700; color: var(--navy); }
.form-group input, .form-group select, .form-group textarea {
  padding: 13px 16px;
  border: 1.5px solid var(--border);
  border-radius: 6px;
  font-size: 1rem;
  font-family: inherit;
  color: var(--text);
  background: var(--cream);
  outline: none;
  transition: border-color 0.2s;
}
.form-group input:focus, .form-group select:focus, .form-group textarea:focus {
  border-color: var(--gold);
  background: var(--white);
}
.form-group textarea { resize: vertical; }
.checkbox-label { display: flex; align-items: center; gap: 10px; font-size: 0.95rem; cursor: pointer; color: var(--text-light); }
.submit-btn { width: 100%; padding: 16px; font-size: 1.05rem; }

.form-error {
  background: #fef2f2;
  border: 1px solid #fca5a5;
  color: #991b1b;
  border-radius: 6px;
  padding: 12px 16px;
  font-size: 0.9rem;
}
.form-success {
  background: #f0fdf4;
  border: 1px solid #86efac;
  color: #166534;
  border-radius: 6px;
  padding: 12px 16px;
  font-size: 0.9rem;
}

@media (max-width: 760px) {
  .contact-grid, .form-row { grid-template-columns: 1fr; }
}
</style>
