<template>
  <div class="page-hero">
    <div class="container">
      <p class="hero-label">We'd Love to Hear From You</p>
      <h1>Contact Us</h1>
      <p>We desire to serve today's generation in the will of God through truth, creativity, discipleship, and compassionate media engagement.</p>
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
            <img class="ci-photo" src="/images/stock/photo-1524661135-423995f22d0b.jpg" alt="Location" />
            <div>
              <h4>Ministry Office</h4>
              <p>Hyderabad, Telangana, India</p>
            </div>
          </div>
          <div class="contact-info-item">
            <img class="ci-photo" src="/images/stock/photo-1512941937669-90a1b58e7e9c.jpg" alt="Phone support" />
            <div>
              <h4>Contact Number</h4>
              <p>Available via contact form or in-person</p>
            </div>
          </div>
          <div class="contact-info-item">
            <img class="ci-photo" src="/images/stock/photo-1586769852836-bc069f19e1b6.jpg" alt="Email contact" />
            <div>
              <h4>Email Address</h4>
              <p>Available via contact form below</p>
            </div>
          </div>
          <div class="contact-info-item">
            <img class="ci-photo" src="/images/stock/photo-1501139083538-0139583c060f.jpg" alt="Office hours" />
            <div>
              <h4>Ministry Hours</h4>
              <p>Monday – Saturday, 9 AM – 6 PM IST</p>
            </div>
          </div>
        </div>

        <div class="quick-links">
          <h4>Quick Actions</h4>
          <RouterLink to="/prayer" class="quick-btn">Prayer Request</RouterLink>
          <RouterLink to="/volunteer" class="quick-btn">Volunteer Inquiry</RouterLink>
          <RouterLink to="/volunteer" class="quick-btn">Ministry Partnership</RouterLink>
          <RouterLink to="/ministries" class="quick-btn">Learn About Ministries</RouterLink>
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
          <button type="submit" class="btn btn-primary submit-btn">
            {{ submitted ? 'Message Sent - God Bless You!' : 'Send Message' }}
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

const submitted = ref(false)
const form = reactive({ name: '', email: '', phone: '', subject: '', message: '', newsletter: false })

function submitContact() {
  submitted.value = true
  setTimeout(() => { submitted.value = false }, 5000)
}
</script>

<style scoped>
.contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: start; }

.contact-info-list { display: flex; flex-direction: column; gap: 16px; margin-bottom: 32px; }
.contact-info-item { display: flex; align-items: flex-start; gap: 16px; }
.ci-photo { width: 52px; height: 52px; border-radius: 6px; object-fit: cover; flex-shrink: 0; }
.contact-info-item h4 { font-size: 0.95rem; font-weight: 700; color: var(--navy); margin-bottom: 4px; }
.contact-info-item p { font-size: 1rem; color: var(--text-light); line-height: 1.6; }

.quick-links { border: 1px solid var(--border); border-radius: 8px; padding: 28px; }
.quick-links h4 { color: var(--navy); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.12em; font-weight: 800; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 2px solid var(--gold); }
.quick-btn {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 13px 16px;
  border-radius: 6px;
  font-size: 1rem;
  color: var(--navy);
  transition: all 0.2s;
  margin-bottom: 8px;
}
.quick-btn:hover { background: var(--gold-pale); color: var(--navy); }

.contact-form-box {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 44px 36px;
  box-shadow: 0 4px 24px rgba(26,45,90,0.08);
}
.contact-form-box h3 { font-family: 'Playfair Display', serif; color: var(--navy); font-size: 1.6rem; margin-bottom: 8px; }
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

@media (max-width: 760px) {
  .contact-grid, .form-row { grid-template-columns: 1fr; }
}
</style>
