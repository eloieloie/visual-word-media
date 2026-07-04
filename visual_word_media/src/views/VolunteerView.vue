<template>
  <div class="page-hero">
    <div class="container">
      <p class="hero-label">Be Part of the Mission</p>
      <h1>Join Us in Advancing<br>God's Kingdom</h1>
      <p>Every believer has a God-given purpose and a unique role in His Kingdom. Find yours here.</p>
    </div>
  </div>

  <!-- INTRO -->
  <section class="section-sm" style="background:var(--section-bg); text-align:center">
    <div class="container" style="max-width:760px">
      <p style="color:var(--text-light); line-height:1.85; font-size:1.05rem; margin-bottom:24px">
        At Visual Word Media Mission, we believe that every believer has a God-given purpose and a unique role in His Kingdom. The Gospel advances when ordinary people respond to God's extraordinary call.
      </p>
      <div class="scripture-block">
        "Each of you should use whatever gift you have received to serve others, as faithful stewards of God's grace."
        <span class="scripture-ref">— 1 Peter 4:10</span>
      </div>
    </div>
  </section>

  <!-- WAYS TO SERVE -->
  <section class="section">
    <div class="container">
      <div style="text-align:center; margin-bottom:52px">
        <p class="section-label">How You Can Contribute</p>
        <h2 class="section-title">Ways You Can Serve</h2>
        <div class="divider divider-center"></div>
      </div>
      <div class="grid-3">
        <div class="serve-card" v-for="role in roles" :key="role.title">
          <img class="serve-photo" :src="role.image" :alt="role.title" />
          <h3>{{ role.title }}</h3>
          <p>{{ role.desc }}</p>
          <ul v-if="role.skills" class="skill-tags">
            <li v-for="s in role.skills" :key="s">{{ s }}</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- WHY JOIN -->
  <section class="section-sm" style="background:var(--navy)">
    <div class="container">
      <div style="text-align:center; margin-bottom:40px">
        <p class="section-label" style="color:var(--gold)">The Bigger Picture</p>
        <h2 style="color:var(--white)">Why Join?</h2>
        <div class="divider divider-center"></div>
        <p style="color:rgba(255,255,255,0.72); max-width:540px; margin:0 auto">When you partner with Visual Word Media Mission, you become part of a larger vision:</p>
      </div>
      <div class="why-grid">
        <div class="why-item" v-for="w in whyJoin" :key="w">
          <span class="why-check"></span>
          <span>{{ w }}</span>
        </div>
      </div>
      <p style="text-align:center; color:var(--gold); font-family:'Playfair Display',serif; font-size:1.3rem; margin-top:40px; font-style:italic">Together, we can make an eternal impact.</p>
    </div>
  </section>

  <!-- REGISTRATION FORM -->
  <section class="section">
    <div class="container" style="max-width:860px">
      <div style="text-align:center; margin-bottom:48px">
        <p class="section-label">Take the Next Step</p>
        <h2 class="section-title">Ministry Partnership & Volunteer Registration</h2>
        <div class="divider divider-center"></div>
        <p style="color:var(--text-light)">Your calling could become someone's answered prayer.</p>
      </div>

      <!-- Success state -->
      <div v-if="submitted" class="success-panel">
        <img class="success-photo" src="/images/meaningful/volunteer/registration-success.svg" alt="Registration received" />
        <h3>Registration Received!</h3>
        <p>Thank you, <strong>{{ form.name }}</strong>! We've sent a verification link to <strong>{{ form.email }}</strong>. Please check your inbox and click the link to confirm your email address.</p>
        <p style="font-size:0.92rem;color:var(--text-light);margin-top:12px">Once verified, our team will review your details. After approval, your login credentials will be emailed to you. (Don't forget to check your spam folder.)</p>
        <button class="btn btn-outline" style="margin-top:24px" @click="resetForm">Submit Another Registration</button>
      </div>

      <!-- Error state -->
      <div v-if="submitError" class="error-banner">⚠️ {{ submitError }}</div>

      <form v-if="!submitted" @submit.prevent="submitForm" class="reg-form">
        <!-- Personal Info -->
        <div class="form-section-header">Personal Information</div>
        <div class="form-row">
          <div class="form-group">
            <label>Full Name *</label>
            <input v-model="form.name" type="text" required placeholder="Your full name" />
          </div>
          <div class="form-group">
            <label>Gender *</label>
            <div class="radio-group">
              <label class="radio-label"><input type="radio" v-model="form.gender" value="Male" required /> Male</label>
              <label class="radio-label"><input type="radio" v-model="form.gender" value="Female" required /> Female</label>
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Date of Birth *</label>
            <input v-model="form.dob" type="date" required />
          </div>
          <div class="form-group">
            <label>Mobile Number *</label>
            <input v-model="form.mobile" type="tel" required placeholder="+91 00000 00000" />
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>WhatsApp Number *</label>
            <input v-model="form.whatsapp" type="tel" required placeholder="If different from mobile" />
          </div>
          <div class="form-group">
            <label>Email Address *</label>
            <input v-model="form.email" type="email" required placeholder="your@email.com" />
          </div>
        </div>
        <div class="form-row trio">
          <div class="form-group">
            <label>City / Town *</label>
            <input v-model="form.city" type="text" required placeholder="City" />
          </div>
          <div class="form-group">
            <label>State *</label>
            <input v-model="form.state" type="text" required placeholder="State" />
          </div>
          <div class="form-group">
            <label>Country *</label>
            <input v-model="form.country" type="text" required placeholder="Country" />
          </div>
        </div>

        <!-- Spiritual Background -->
        <div class="form-section-header">Spiritual Background</div>
        <div class="form-row">
          <div class="form-group">
            <label>Are you a follower of Jesus Christ? *</label>
            <div class="radio-group">
              <label class="radio-label"><input type="radio" v-model="form.believer" value="Yes" /> Yes</label>
              <label class="radio-label"><input type="radio" v-model="form.believer" value="No" /> No</label>
            </div>
          </div>
          <div class="form-group">
            <label>Actively involved in local church? *</label>
            <div class="radio-group">
              <label class="radio-label"><input type="radio" v-model="form.churchActive" value="Yes" required /> Yes</label>
              <label class="radio-label"><input type="radio" v-model="form.churchActive" value="No" required /> No</label>
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Name of Church *</label>
            <input v-model="form.churchName" type="text" required placeholder="Your church name" />
          </div>
          <div class="form-group">
            <label>Pastor's Name *</label>
            <input v-model="form.pastor" type="text" required placeholder="Pastor's name" />
          </div>
        </div>
        <div class="form-group">
          <label>Briefly share your testimony *</label>
          <textarea v-model="form.testimony" rows="4" required placeholder="Share how God has worked in your life..."></textarea>
        </div>

        <!-- Ministry Areas -->
        <div class="form-section-header">Areas of Interest</div>
        <p style="color:var(--text-light); font-size:0.88rem; margin-bottom:16px">Which ministry areas would you like to participate in?</p>
        <div class="checkbox-grid">
          <label class="checkbox-label" v-for="area in ministryAreas" :key="area">
            <input type="checkbox" v-model="form.selectedAreas" :value="area" />
            {{ area }}
          </label>
        </div>

        <!-- Availability -->
        <div class="form-section-header">Availability & Partnership</div>
        <div class="form-row">
          <div class="form-group">
            <label>How would you like to serve?</label>
            <div class="checkbox-col">
              <label class="checkbox-label" v-for="s in serviceTypes" :key="s">
                <input type="checkbox" v-model="form.serviceType" :value="s" /> {{ s }}
              </label>
            </div>
          </div>
          <div class="form-group">
            <label>Availability</label>
            <div class="checkbox-col">
              <label class="checkbox-label" v-for="a in availability" :key="a">
                <input type="checkbox" v-model="form.availability" :value="a" /> {{ a }}
              </label>
            </div>
          </div>
        </div>

        <!-- Additional -->
        <div class="form-section-header">Additional Information</div>
        <div class="form-group">
          <label>Skills, qualifications, or ministry experience *</label>
          <textarea v-model="form.skills" rows="3" required placeholder="Describe your skills and experience..."></textarea>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Professional Occupation *</label>
            <input v-model="form.occupation" type="text" required placeholder="Your occupation" />
          </div>
          <div class="form-group">
            <label>Organization / Company *</label>
            <input v-model="form.organization" type="text" required placeholder="Where you work" />
          </div>
        </div>
        <div class="form-group">
          <label>Why do you want to be part of Visual Word Media Mission? *</label>
          <textarea v-model="form.motivation" rows="3" required placeholder="Share your heart..."></textarea>
        </div>
        <div class="form-group">
          <label>Additional Comments</label>
          <textarea v-model="form.comments" rows="3" placeholder="Anything else you'd like to share..."></textarea>
        </div>

        <div class="declaration">
          <label class="checkbox-label">
            <input type="checkbox" v-model="form.declared" required />
            I affirm that the information provided is true and accurate. I understand submission does not guarantee placement and further interaction may be required.
          </label>
        </div>

        <button type="submit" class="btn btn-primary submit-btn" :disabled="submitting">
          {{ submitting ? 'Submitting…' : 'Submit' }}
        </button>
      </form>
    </div>
  </section>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { api } from '../services/api.js'

const submitted   = ref(false)
const submitting  = ref(false)
const submitError = ref('')

const initialForm = () => ({
  name: '', gender: '', dob: '', mobile: '', whatsapp: '', email: '',
  city: '', state: '', country: '', believer: '', churchActive: '',
  churchName: '', pastor: '', testimony: '', selectedAreas: [],
  serviceType: [], availability: [], skills: '', occupation: '',
  organization: '', motivation: '', comments: '', declared: false,
})

const form = reactive(initialForm())

async function submitForm() {
  submitting.value  = true
  submitError.value = ''
  try {
    await api.post('/volunteers/index.php', { ...form })
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
  Object.assign(form, initialForm())
}

const roles = [
  { image: '/images/meaningful/volunteer/prayer-partner.svg', title: 'Prayer Partner', desc: 'Become a prayer warrior for the ministry, outreach programs, youth camps, village missions, media projects, and leadership initiatives.' },
  { image: '/images/meaningful/volunteer/evangelism-outreach.svg', title: 'Evangelism & Outreach', desc: 'Participate in village outreach programs, Gospel campaigns, youth gatherings, Bible distribution, and community engagement.' },
  { image: '/images/meaningful/volunteer/youth-mentor.svg', title: 'Youth Mentor', desc: 'Help guide, encourage, disciple, and mentor young people as they grow in their faith and leadership journey.' },
  { image: '/images/meaningful/volunteer/media-creative.svg', title: 'Media & Creative Ministry', desc: 'Use your creative skills to communicate the Gospel through excellence.', skills: ['Photography', 'Videography', 'Video Editing', 'Graphic Design', 'Animation', 'Content Writing', 'Social Media', 'Web Development', 'Audio Production', 'Music Ministry'] },
  { image: '/images/meaningful/volunteer/teaching-discipleship.svg', title: 'Teaching & Discipleship', desc: 'Assist in conducting Bible studies, discipleship groups, leadership development programs, and training sessions.' },
  { image: '/images/meaningful/volunteer/mission-partner.svg', title: 'Mission Partner', desc: 'Support ministry initiatives through financial contributions, sponsorships, equipment donations, and project partnerships.' },
  { image: '/images/meaningful/volunteer/church-partnership.svg', title: 'Church & Ministry Partnership', desc: 'Partner to host camps, conferences, leadership training, evangelistic events, and community development initiatives.' },
]

const whyJoin = [
  'Reaching unreached communities',
  'Equipping the next generation',
  'Raising disciples and leaders',
  'Strengthening churches',
  'Transforming lives through media and ministry',
  'Advancing the Kingdom of God',
]

const ministryAreas = [
  'Prayer Ministry', 'Youth Ministry', 'Evangelism & Outreach', 'Village Missions',
  'Children\'s Ministry', 'Discipleship Programs', 'Leadership Development', 'Media Production',
  'Photography', 'Videography', 'Video Editing', 'Graphic Design',
  'Social Media Ministry', 'Website Development', 'Music Ministry', 'Worship Team',
  'Administration', 'Event Management', 'Fundraising', 'Counseling Support', 'Training & Teaching',
]

const serviceTypes = ['Full-Time', 'Part-Time', 'Volunteer', 'Project-Based', 'Prayer Support', 'Financial Partnership']
const availability = ['Weekdays', 'Weekends', 'Flexible']
</script>

<style scoped>
.serve-card {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: 32px 24px;
  transition: all 0.25s;
}
.serve-card:hover { border-color: var(--gold); box-shadow: 0 8px 32px rgba(26,45,90,0.1); transform: translateY(-3px); }
.serve-photo {
  width: 100%;
  height: 150px;
  object-fit: cover;
  border-radius: 6px;
  margin-bottom: 16px;
}
.serve-card h3 { color: var(--navy); font-size: 1.2rem; margin-bottom: 12px; }
.serve-card p { color: var(--text-light); font-size: 1rem; line-height: 1.85; }
.skill-tags { list-style: none; display: flex; flex-wrap: wrap; gap: 8px; margin-top: 16px; }
.skill-tags li { background: var(--gold-pale); color: var(--navy); font-size: 0.82rem; padding: 5px 12px; border-radius: 12px; font-weight: 700; }

.why-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
.why-item {
  display: flex;
  align-items: center;
  gap: 14px;
  background: rgba(255,255,255,0.08);
  border: 1px solid rgba(201,162,39,0.2);
  border-radius: 6px;
  padding: 18px 20px;
  color: rgba(255,255,255,0.88);
  font-size: 1rem;
  line-height: 1.6;
}
.why-check {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: var(--gold);
  margin-top: 8px;
  flex-shrink: 0;
}

/* FORM */
.reg-form { display: flex; flex-direction: column; gap: 20px; }
.form-section-header {
  font-size: 0.8rem;
  font-weight: 800;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  color: var(--white);
  background: var(--navy);
  padding: 12px 18px;
  border-radius: 4px;
  margin-top: 16px;
}
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 22px; }
.form-row.trio { grid-template-columns: 1fr 1fr 1fr; }
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
.radio-group { display: flex; gap: 24px; padding-top: 8px; }
.radio-label { display: flex; align-items: center; gap: 10px; font-size: 1rem; cursor: pointer; }
.checkbox-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
.checkbox-label { display: flex; align-items: flex-start; gap: 10px; font-size: 0.95rem; cursor: pointer; line-height: 1.5; }
.checkbox-col { display: flex; flex-direction: column; gap: 10px; padding-top: 4px; }
.declaration {
  background: var(--section-bg);
  border: 1px solid var(--border);
  padding: 18px 22px;
  border-radius: 6px;
  font-size: 0.95rem;
  color: var(--text-light);
  line-height: 1.7;
}
.submit-btn { align-self: center; padding: 17px 52px; font-size: 1.05rem; margin-top: 10px; }

.success-panel {
  text-align: center;
  padding: 60px 40px;
  background: var(--white);
  border: 2px solid var(--gold);
  border-radius: 12px;
}
.success-photo {
  width: 92px;
  height: 92px;
  object-fit: cover;
  border-radius: 50%;
  margin: 0 auto 16px;
}
.success-panel h3 { font-family: 'Playfair Display', serif; color: var(--navy); font-size: 1.8rem; margin-bottom: 14px; }
.success-panel p { color: var(--text-light); font-size: 1.05rem; line-height: 1.75; }
.error-banner {
  background: #fdecea;
  border: 1px solid #f5c6c4;
  color: #c62828;
  padding: 14px 18px;
  border-radius: 8px;
  font-size: 0.95rem;
  margin-bottom: 4px;
}

@media (max-width: 760px) {
  .form-row, .form-row.trio, .why-grid, .checkbox-grid { grid-template-columns: 1fr; }
}
</style>
