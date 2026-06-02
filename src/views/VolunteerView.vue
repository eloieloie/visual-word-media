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
          <div class="serve-icon">{{ role.icon }}</div>
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
          <span class="why-check">✓</span>
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

      <form @submit.prevent="submitForm" class="reg-form">
        <!-- Personal Info -->
        <div class="form-section-header">Personal Information</div>
        <div class="form-row">
          <div class="form-group">
            <label>Full Name *</label>
            <input v-model="form.name" type="text" required placeholder="Your full name" />
          </div>
          <div class="form-group">
            <label>Gender</label>
            <div class="radio-group">
              <label class="radio-label"><input type="radio" v-model="form.gender" value="Male" /> Male</label>
              <label class="radio-label"><input type="radio" v-model="form.gender" value="Female" /> Female</label>
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Date of Birth</label>
            <input v-model="form.dob" type="date" />
          </div>
          <div class="form-group">
            <label>Mobile Number *</label>
            <input v-model="form.mobile" type="tel" required placeholder="+91 00000 00000" />
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>WhatsApp Number</label>
            <input v-model="form.whatsapp" type="tel" placeholder="If different from mobile" />
          </div>
          <div class="form-group">
            <label>Email Address *</label>
            <input v-model="form.email" type="email" required placeholder="your@email.com" />
          </div>
        </div>
        <div class="form-row trio">
          <div class="form-group">
            <label>City / Town</label>
            <input v-model="form.city" type="text" placeholder="City" />
          </div>
          <div class="form-group">
            <label>State</label>
            <input v-model="form.state" type="text" placeholder="State" />
          </div>
          <div class="form-group">
            <label>Country</label>
            <input v-model="form.country" type="text" placeholder="Country" />
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
            <label>Actively involved in local church?</label>
            <div class="radio-group">
              <label class="radio-label"><input type="radio" v-model="form.churchActive" value="Yes" /> Yes</label>
              <label class="radio-label"><input type="radio" v-model="form.churchActive" value="No" /> No</label>
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Name of Church</label>
            <input v-model="form.churchName" type="text" placeholder="Your church name" />
          </div>
          <div class="form-group">
            <label>Pastor's Name</label>
            <input v-model="form.pastor" type="text" placeholder="Pastor's name" />
          </div>
        </div>
        <div class="form-group">
          <label>Briefly share your testimony</label>
          <textarea v-model="form.testimony" rows="4" placeholder="Share how God has worked in your life..."></textarea>
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
          <label>Skills, qualifications, or ministry experience</label>
          <textarea v-model="form.skills" rows="3" placeholder="Describe your skills and experience..."></textarea>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Professional Occupation</label>
            <input v-model="form.occupation" type="text" placeholder="Your occupation" />
          </div>
          <div class="form-group">
            <label>Organization / Company</label>
            <input v-model="form.organization" type="text" placeholder="Where you work" />
          </div>
        </div>
        <div class="form-group">
          <label>Why do you want to be part of Visual Word Media Mission?</label>
          <textarea v-model="form.motivation" rows="3" placeholder="Share your heart..."></textarea>
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

        <button type="submit" class="btn btn-primary submit-btn">
          {{ submitted ? '✓ Registration Submitted — God Bless You!' : 'Submit Registration' }}
        </button>
      </form>
    </div>
  </section>
</template>

<script setup>
import { ref, reactive } from 'vue'

const submitted = ref(false)

const form = reactive({
  name: '', gender: '', dob: '', mobile: '', whatsapp: '', email: '',
  city: '', state: '', country: '', believer: '', churchActive: '',
  churchName: '', pastor: '', testimony: '', selectedAreas: [],
  serviceType: [], availability: [], skills: '', occupation: '',
  organization: '', motivation: '', comments: '', declared: false,
})

function submitForm() {
  submitted.value = true
  setTimeout(() => { submitted.value = false }, 5000)
}

const roles = [
  { icon: '🙏', title: 'Prayer Partner', desc: 'Become a prayer warrior for the ministry, outreach programs, youth camps, village missions, media projects, and leadership initiatives.' },
  { icon: '🌾', title: 'Evangelism & Outreach', desc: 'Participate in village outreach programs, Gospel campaigns, youth gatherings, Bible distribution, and community engagement.' },
  { icon: '👨‍🏫', title: 'Youth Mentor', desc: 'Help guide, encourage, disciple, and mentor young people as they grow in their faith and leadership journey.' },
  { icon: '🎬', title: 'Media & Creative Ministry', desc: 'Use your creative skills to communicate the Gospel through excellence.', skills: ['Photography', 'Videography', 'Video Editing', 'Graphic Design', 'Animation', 'Content Writing', 'Social Media', 'Web Development', 'Audio Production', 'Music Ministry'] },
  { icon: '📖', title: 'Teaching & Discipleship', desc: 'Assist in conducting Bible studies, discipleship groups, leadership development programs, and training sessions.' },
  { icon: '💼', title: 'Professional Services', desc: 'Contribute your professional expertise to advance the ministry.', skills: ['Administration', 'Project Management', 'Accounting', 'Legal Support', 'IT Services', 'Marketing', 'Event Management', 'Education & Training'] },
  { icon: '💰', title: 'Mission Partner', desc: 'Support ministry initiatives through financial contributions, sponsorships, equipment donations, and project partnerships.' },
  { icon: '⛪', title: 'Church & Ministry Partnership', desc: 'Partner to host camps, conferences, leadership training, evangelistic events, and community development initiatives.' },
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
.serve-icon { font-size: 2.6rem; margin-bottom: 16px; }
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
.why-check { color: var(--gold); font-weight: 700; flex-shrink: 0; }

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

@media (max-width: 760px) {
  .form-row, .form-row.trio, .why-grid, .checkbox-grid { grid-template-columns: 1fr; }
}
</style>
