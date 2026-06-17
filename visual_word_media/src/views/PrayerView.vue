<template>
  <div class="page-hero">
    <div class="container">
      <p class="hero-label">Stand With Us</p>
      <h1>Prayer Partnership</h1>
      <p>Transformation begins with prayer. We invite believers, churches, and prayer groups to stand with us in intercession for this generation.</p>
    </div>
  </div>

  <section class="section">
    <div class="container prayer-grid">
      <div>
        <p class="section-label">Join Us in Prayer</p>
        <h2 class="section-title">Your Prayers Move Mountains</h2>
        <div class="divider"></div>
        <p style="color:var(--text-light); line-height:1.85; margin-bottom:32px">
          The ministry believes that transformation begins with prayer. We invite believers, churches, and prayer groups to stand with us in prayer for every area of ministry, every life we touch, and every community we serve.
        </p>

        <h3 style="color:var(--navy); margin-bottom:20px; font-size:1.1rem">We Are Praying For:</h3>
        <div class="prayer-items">
          <div class="prayer-item" v-for="item in prayerItems" :key="item">
            <span class="prayer-marker"></span>
            <span>{{ item }}</span>
          </div>
        </div>
      </div>

      <!-- PRAYER FORM -->
      <div class="prayer-form-box">
        <h3>Prayer Commitment Form</h3>
        <p>Fill in your details to join our prayer network.</p>
        <form @submit.prevent="submitPrayer" class="form">
          <div class="form-group">
            <label>Full Name *</label>
            <input v-model="form.name" type="text" placeholder="Your name" required />
          </div>
          <div class="form-group">
            <label>Church / Organization</label>
            <input v-model="form.church" type="text" placeholder="Your church or organization" />
          </div>
          <div class="form-group">
            <label>Country / City</label>
            <input v-model="form.location" type="text" placeholder="Your city and country" />
          </div>
          <div class="form-group">
            <label>Prayer Interest</label>
            <select v-model="form.interest">
              <option value="">Select an area</option>
              <option v-for="item in prayerItems" :key="item">{{ item }}</option>
            </select>
          </div>
          <div class="form-group">
            <label>Monthly Prayer Commitment</label>
            <textarea v-model="form.commitment" rows="3" placeholder="How do you commit to pray for this ministry?"></textarea>
          </div>
          <button type="submit" class="btn btn-primary" style="width:100%">
            {{ submitted ? 'Commitment Received!' : 'Submit Prayer Commitment' }}
          </button>
        </form>
      </div>
    </div>
  </section>

  <section class="section-sm" style="background:var(--navy); text-align:center">
    <div class="container" style="max-width:680px">
      <div class="scripture-block">
        "The effective, fervent prayer of a righteous man avails much."
        <span class="scripture-ref">— James 5:16</span>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, reactive } from 'vue'

const submitted = ref(false)
const form = reactive({ name: '', church: '', location: '', interest: '', commitment: '' })

function submitPrayer() {
  submitted.value = true
  setTimeout(() => { submitted.value = false }, 4000)
}

const prayerItems = [
  'Youth revival across the nation',
  'Media transformation for God\'s glory',
  'Creative artists surrendered to Christ',
  'Discipleship movements in villages',
  'Church strengthening and unity',
  'Open doors for ministry',
  '60x360 village outreach',
  'New Life counseling ministry',
]
</script>

<style scoped>
.prayer-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: start; }
.prayer-items { display: flex; flex-direction: column; gap: 12px; }
.prayer-item {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 16px 20px;
  background: var(--section-bg);
  border-radius: 6px;
  font-size: 1.02rem;
  line-height: 1.6;
}
.prayer-marker {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: var(--gold);
  flex-shrink: 0;
  margin-top: 6px;
}

.prayer-form-box {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 44px 36px;
  box-shadow: 0 4px 24px rgba(26,45,90,0.08);
}
.prayer-form-box h3 {
  font-family: 'Playfair Display', serif;
  color: var(--navy);
  font-size: 1.6rem;
  margin-bottom: 10px;
}
.prayer-form-box > p { color: var(--text-light); font-size: 1rem; margin-bottom: 32px; }

.form { display: flex; flex-direction: column; gap: 20px; }
.form-group { display: flex; flex-direction: column; gap: 8px; }
.form-group label { font-size: 0.9rem; font-weight: 700; color: var(--navy); letter-spacing: 0.03em; }
.form-group input, .form-group select, .form-group textarea {
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
.form-group input:focus, .form-group select:focus, .form-group textarea:focus {
  border-color: var(--gold);
  background: var(--white);
}
.form-group textarea { resize: vertical; }

@media (max-width: 760px) {
  .prayer-grid { grid-template-columns: 1fr; }
}
</style>
