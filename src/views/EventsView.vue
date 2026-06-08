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

      <!-- Loading state -->
      <div v-if="loading" class="events-loading">
        <div class="spinner"></div>
        <p>Loading events…</p>
      </div>

      <!-- Error state -->
      <div v-else-if="error" class="events-error">
        <p>⚠️ {{ error }}</p>
        <button class="btn btn-outline" @click="fetchEvents">Retry</button>
      </div>

      <template v-else>
        <div class="event-filter">
          <button
            v-for="cat in categories"
            :key="cat"
            :class="['cat-btn', { active: activeCategory === cat }]"
            @click="activeCategory = cat"
          >{{ cat }}</button>
        </div>

        <div class="events-grid" v-if="filteredEvents.length">
          <div class="event-card" v-for="e in filteredEvents" :key="e.id">
            <div class="ec-date">
              <span class="ec-month">{{ e.month }}</span>
              <span class="ec-day">{{ e.day }}</span>
            </div>
            <div class="ec-body">
              <span class="ec-tag">{{ e.category }}</span>
              <h3>{{ e.title }}</h3>
              <p>{{ e.description }}</p>
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

        <p v-else class="no-events">No events found in this category.</p>
      </template>

      <div class="events-cta">
        <div class="scripture-block" style="max-width:640px; margin:0 auto">
          "Not neglecting to meet together, as is the habit of some, but encouraging one another."
          <span class="scripture-ref">— Hebrews 10:25</span>
        </div>
        <p style="text-align:center; margin-top:28px; color:var(--text-light)">
          For event updates and to be notified of upcoming programs,
          <RouterLink to="/contact" style="color:var(--gold); font-weight:600">contact us</RouterLink>
          or join our prayer network.
        </p>
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
import { ref, computed, onMounted } from 'vue'
import { api } from '../services/api.js'

const categories    = ['All', 'Youth Camps', 'Media Seminars', 'Creative Arts', 'Bible Studies', 'Training Workshops', 'Prayer Meetings', 'Leadership Sessions']
const activeCategory = ref('All')
const events        = ref([])
const loading       = ref(true)
const error         = ref('')

async function fetchEvents() {
  loading.value = true
  error.value   = ''
  try {
    const data  = await api.get('/events/index.php')
    events.value = data.events
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}

const filteredEvents = computed(() =>
  activeCategory.value === 'All'
    ? events.value
    : events.value.filter(e => e.category === activeCategory.value)
)

onMounted(fetchEvents)

const eventTypes = [
  { icon: '🏕️', label: 'Youth Camps',              desc: 'Multi-day residential camps for evangelism, discipleship, and leadership development among youth.' },
  { icon: '📺', label: 'Media Awareness Seminars',  desc: 'Half-day or full-day programs addressing media influence, digital addiction, and Biblical responses.' },
  { icon: '🎨', label: 'Creative Arts Gatherings',  desc: 'Workshops, exhibitions, and fellowships for artists to explore faith and creativity together.' },
  { icon: '📖', label: 'Bible Studies',             desc: 'Regular and thematic Bible studies for different groups including media professionals and youth.' },
  { icon: '🔧', label: 'Training Workshops',        desc: 'Practical skill-based training for volunteers, counselors, and ministry team members.' },
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
.cat-btn:hover  { border-color: var(--gold); color: var(--navy); }
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
.ec-day   { font-family: 'Playfair Display', serif; font-size: 2.2rem; font-weight: 700; line-height: 1; }
.ec-body  { flex: 1; }
.ec-tag   { font-size: 0.8rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: var(--gold); }
.ec-body h3 { color: var(--navy); margin: 6px 0 10px; font-size: 1.15rem; }
.ec-body p  { color: var(--text-light); font-size: 1rem; line-height: 1.75; margin-bottom: 12px; }
.ec-meta    { display: flex; gap: 24px; font-size: 0.9rem; color: var(--text-light); }

.events-cta { margin-top: 56px; }

.no-events {
  text-align: center;
  color: var(--text-light);
  padding: 60px 0;
  font-size: 1.05rem;
}

/* Loading */
.events-loading {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 16px;
  padding: 80px 0;
  color: var(--text-light);
}
.spinner {
  width: 36px; height: 36px;
  border: 3px solid var(--border);
  border-top-color: var(--navy);
  border-radius: 50%;
  animation: spin 0.75s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* Error */
.events-error {
  text-align: center;
  padding: 60px 0;
  color: #c0392b;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 16px;
}

.etype-card {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: 28px 22px;
  text-align: center;
}
.etype-icon { font-size: 2.6rem; margin-bottom: 14px; display: block; }
.etype-card h4 { color: var(--navy); margin-bottom: 10px; font-size: 1.1rem; }
.etype-card p  { color: var(--text-light); font-size: 1rem; line-height: 1.8; }

@media (max-width: 640px) {
  .event-card { flex-direction: column; align-items: flex-start; }
  .ec-date    { flex-direction: row; gap: 8px; align-items: center; padding: 8px 14px; }
}
</style>
