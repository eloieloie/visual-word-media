<template>
  <div class="page-hero">
    <div class="container">
      <p class="hero-label">Mark Your Calendar</p>
      <h1>Ministry Events</h1>
      <p>Camps, seminars, workshops, and gatherings that equip, inspire, and connect the body of Christ.</p>
    </div>
  </div>

  <section class="section">
    <div class="container">
      <div class="event-filter">
        <button
          v-for="cat in categories"
          :key="cat"
          :class="['cat-btn', { active: activeCategory === cat }]"
          @click="activeCategory = cat"
        >{{ cat }}</button>
      </div>

      <div class="events-grid">
        <div class="event-card" v-for="e in filteredEvents" :key="e.title">
          <div class="ec-date">
            <span class="ec-month">{{ e.month }}</span>
            <span class="ec-day">{{ e.day }}</span>
          </div>
          <div class="ec-body">
            <span class="ec-tag">{{ e.category }}</span>
            <h3>{{ e.title }}</h3>
            <p>{{ e.desc }}</p>
            <div class="ec-meta">
              <span>📍 {{ e.location }}</span>
              <span>⏰ {{ e.time }}</span>
            </div>
          </div>
          <div class="ec-action">
            <RouterLink to="/contact" class="btn btn-navy">Register</RouterLink>
          </div>
        </div>
      </div>

      <div class="events-cta">
        <div class="scripture-block" style="max-width:640px; margin:0 auto">
          "Not neglecting to meet together, as is the habit of some, but encouraging one another."
          <span class="scripture-ref">— Hebrews 10:25</span>
        </div>
        <p style="text-align:center; margin-top:28px; color:var(--text-light)">For event updates and to be notified of upcoming programs, <RouterLink to="/contact" style="color:var(--gold); font-weight:600">contact us</RouterLink> or join our prayer network.</p>
      </div>
    </div>
  </section>

  <!-- CATEGORIES INFO -->
  <section class="section-sm" style="background:var(--section-bg)">
    <div class="container">
      <div style="text-align:center; margin-bottom:40px">
        <p class="section-label">Event Types</p>
        <h2 class="section-title">What We Host</h2>
        <div class="divider divider-center"></div>
      </div>
      <div class="grid-3">
        <div class="etype-card" v-for="et in eventTypes" :key="et.label">
          <span class="etype-icon">{{ et.icon }}</span>
          <h4>{{ et.label }}</h4>
          <p>{{ et.desc }}</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue'

const categories = ['All', 'Youth Camps', 'Media Seminars', 'Creative Arts', 'Bible Studies', 'Training Workshops', 'Prayer Meetings', 'Leadership Sessions']
const activeCategory = ref('All')

const events = [
  { month: 'JUL', day: '12', title: '60x360 Youth Discipleship Camp', category: 'Youth Camps', desc: 'A 3-day intensive discipleship camp for youth aged 15–25, focused on faith, identity, and Kingdom purpose.', location: 'Hyderabad', time: '9 AM – 6 PM', },
  { month: 'JUL', day: '20', title: 'IMPACT Media Awareness Seminar', category: 'Media Seminars', desc: 'A half-day seminar addressing digital addiction, social media influence, and Biblical discernment in the digital age.', location: 'Secunderabad', time: '10 AM – 1 PM', },
  { month: 'AUG', day: '3', title: 'Oculus Creative Arts Workshop', category: 'Creative Arts', desc: 'An all-day workshop for artists, designers, photographers, and filmmakers exploring faith and creativity together.', location: 'Hyderabad', time: '9 AM – 5 PM', },
  { month: 'AUG', day: '15', title: 'Leadership Development Camp', category: 'Leadership Sessions', desc: 'Intensive leadership training for emerging ministry leaders in the 60x360 mission field.', location: 'Rangareddy District', time: '8 AM – 8 PM', },
  { month: 'AUG', day: '25', title: 'Bible Study for Media Professionals', category: 'Bible Studies', desc: 'A weekly gathering for media workers, content creators, and digital professionals to study the Word together.', location: 'Online & Hyderabad', time: '7 PM – 9 PM', },
  { month: 'SEP', day: '6', title: 'Prayer & Fasting Gathering', category: 'Prayer Meetings', desc: 'A day of unified prayer and fasting for youth revival, village outreach, and ministry expansion.', location: 'Ministry Center', time: '6 AM – 6 PM', },
  { month: 'SEP', day: '18', title: 'New Life Counseling Training Workshop', category: 'Training Workshops', desc: 'Training volunteers and counselors in biblical approaches to emotional support and hope-centered care.', location: 'Hyderabad', time: '9 AM – 4 PM', },
  { month: 'OCT', day: '4', title: 'Evangelism Camp — Village Outreach', category: 'Youth Camps', desc: 'A village-based Gospel camp where trained youth evangelize and disciple in underserved communities.', location: 'Nalgonda Region', time: 'Overnight Camp', },
]

const filteredEvents = computed(() =>
  activeCategory.value === 'All' ? events : events.filter(e => e.category === activeCategory.value)
)

const eventTypes = [
  { icon: '🏕️', label: 'Youth Camps', desc: 'Multi-day residential camps for evangelism, discipleship, and leadership development among youth.' },
  { icon: '📺', label: 'Media Awareness Seminars', desc: 'Half-day or full-day programs addressing media influence, digital addiction, and Biblical responses.' },
  { icon: '🎨', label: 'Creative Arts Gatherings', desc: 'Workshops, exhibitions, and fellowships for artists to explore faith and creativity together.' },
  { icon: '📖', label: 'Bible Studies', desc: 'Regular and thematic Bible studies for different groups including media professionals and youth.' },
  { icon: '🔧', label: 'Training Workshops', desc: 'Practical skill-based training for volunteers, counselors, and ministry team members.' },
  { icon: '🙏', label: 'Prayer & Leadership Meetings', desc: 'Gatherings for intercession, spiritual formation, and leadership mentoring across the ministry.' },
]
</script>

<style scoped>
.event-filter { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 40px; }
.cat-btn {
  padding: 8px 18px;
  border: 1.5px solid var(--border);
  border-radius: 20px;
  background: var(--white);
  color: var(--text-light);
  font-size: 0.85rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}
.cat-btn:hover { border-color: var(--gold); color: var(--navy); }
.cat-btn.active { background: var(--navy); color: var(--white); border-color: var(--navy); }

.events-grid { display: flex; flex-direction: column; gap: 16px; }
.event-card {
  display: flex;
  align-items: center;
  gap: 24px;
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: 24px;
  transition: all 0.2s;
}
.event-card:hover { border-color: var(--gold); box-shadow: 0 4px 20px rgba(26,45,90,0.08); }
.ec-date {
  display: flex;
  flex-direction: column;
  align-items: center;
  background: var(--navy);
  color: var(--white);
  border-radius: 8px;
  padding: 12px 18px;
  min-width: 70px;
  flex-shrink: 0;
}
.ec-month { font-size: 0.78rem; letter-spacing: 0.15em; text-transform: uppercase; color: var(--gold); }
.ec-day { font-family: 'Playfair Display', serif; font-size: 2.2rem; font-weight: 700; line-height: 1; }
.ec-body { flex: 1; }
.ec-tag { font-size: 0.8rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: var(--gold); }
.ec-body h3 { color: var(--navy); margin: 6px 0 10px; font-size: 1.15rem; }
.ec-body p { color: var(--text-light); font-size: 1rem; line-height: 1.75; margin-bottom: 12px; }
.ec-meta { display: flex; gap: 24px; font-size: 0.9rem; color: var(--text-light); }

.events-cta { margin-top: 56px; }

.etype-card {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: 28px 22px;
  text-align: center;
}
.etype-icon { font-size: 2.6rem; margin-bottom: 14px; display: block; }
.etype-card h4 { color: var(--navy); margin-bottom: 10px; font-size: 1.1rem; }
.etype-card p { color: var(--text-light); font-size: 1rem; line-height: 1.8; }

@media (max-width: 640px) {
  .event-card { flex-direction: column; align-items: flex-start; }
  .ec-date { flex-direction: row; gap: 8px; align-items: center; padding: 8px 14px; }
}
</style>
