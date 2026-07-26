<template>
  <div class="page-hero">
    <div class="container">
      <motion.p class="hero-label" :initial="{ opacity: 0, y: prefersReduced ? 0 : 14 }" :animate="{ opacity: 1, y: 0 }" :transition="{ duration: prefersReduced ? 0 : 0.6, ease: 'easeOut' }">To His Glory</motion.p>
      <motion.h1 :initial="{ opacity: 0, y: prefersReduced ? 0 : 18 }" :animate="{ opacity: 1, y: 0 }" :transition="{ duration: prefersReduced ? 0 : 0.6, delay: prefersReduced ? 0 : 0.1, ease: 'easeOut' }">Stories of Transformation</motion.h1>
      <motion.p :initial="{ opacity: 0, y: prefersReduced ? 0 : 18 }" :animate="{ opacity: 1, y: 0 }" :transition="{ duration: prefersReduced ? 0 : 0.6, delay: prefersReduced ? 0 : 0.2, ease: 'easeOut' }">Lives touched, hearts changed, and communities transformed by the grace of God through this ministry.</motion.p>
    </div>
  </div>

  <section class="section">
    <div class="container">
      <div style="text-align:center; margin-bottom:52px">
        <p class="section-label">Testimonies</p>
        <h2 class="section-title">God is Moving</h2>
        <div class="divider divider-center"></div>
        <p class="section-subtitle" style="margin:0 auto">Every testimony is a monument to God's faithfulness. These stories represent the heartbeat of Visual Word Media Mission.</p>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="loading-state">
        <div class="spinner"></div>
        <p>Loading testimonials…</p>
      </div>

      <!-- Error -->
      <div v-else-if="error" class="error-state">
        <p>⚠️ {{ error }}</p>
        <button class="btn btn-outline" @click="fetchTestimonials">Retry</button>
      </div>

      <template v-else>
        <!-- Category filter -->
        <div class="testimony-categories">
          <button
            v-for="cat in categories"
            :key="cat"
            :class="['cat-btn', { active: activeCategory === cat }]"
            @click="activeCategory = cat"
          >{{ cat }}</button>
        </div>

        <!-- Grid -->
        <div v-if="filteredTestimonials.length" class="grid-3" style="margin-top:40px">
          <motion.div
            v-for="(t, i) in filteredTestimonials" :key="t.id" class="testimony-card"
            :initial="{ opacity: 0, y: prefersReduced ? 0 : 20 }"
            :while-in-view="{ opacity: 1, y: 0 }"
            :viewport="{ once: true, margin: '-60px' }"
            :transition="{ duration: prefersReduced ? 0 : 0.45, delay: prefersReduced ? 0 : Math.min(i, 8) * 0.06, ease: 'easeOut' }"
          >
            <div class="tc-quote">❝</div>
            <p class="tc-text">{{ t.testimony }}</p>
            <div class="tc-author">
              <div class="tc-avatar">{{ t.name[0] }}</div>
              <div>
                <div class="tc-name">{{ t.name }}</div>
                <div class="tc-sub">
                  <span class="tc-tag">{{ t.category }}</span>
                  <span class="tc-location" v-if="t.location"> · {{ t.location }}</span>
                </div>
              </div>
            </div>
          </motion.div>
        </div>

        <div v-else class="empty-state">
          <p>No testimonials in this category yet.</p>
        </div>
      </template>
    </div>
  </section>

  <section class="section-sm" style="background:var(--navy); text-align:center">
    <div class="container" style="max-width:680px">
      <p class="section-label" style="color:var(--gold)">Share Your Story</p>
      <h2 style="color:var(--white)">Has God Worked in Your Life Through This Ministry?</h2>
      <p style="color:rgba(255,255,255,0.72); margin:16px 0 28px">We would love to hear your testimony. Your story could encourage thousands of others who are on a similar journey.</p>
      <RouterLink to="/contact" class="btn btn-primary">Share Your Testimony</RouterLink>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { motion, useReducedMotion } from 'motion-v'
import { api } from '../services/api.js'

const prefersReduced = useReducedMotion()
const allTestimonials = ref([])
const loading         = ref(true)
const error           = ref('')
const activeCategory  = ref('All')

const categories = computed(() => {
  const cats = [...new Set(allTestimonials.value.map(t => t.category))]
  return ['All', ...cats]
})

const filteredTestimonials = computed(() =>
  activeCategory.value === 'All'
    ? allTestimonials.value
    : allTestimonials.value.filter(t => t.category === activeCategory.value)
)

async function fetchTestimonials() {
  loading.value = true
  error.value   = ''
  try {
    const data           = await api.get('/testimonials/index.php')
    allTestimonials.value = data.testimonials || []
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}

onMounted(fetchTestimonials)
</script>

<style scoped>
.testimony-categories { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; }
.cat-btn {
  padding: 10px 22px;
  border: 1.5px solid var(--border);
  border-radius: 24px;
  background: var(--white);
  color: var(--text-light);
  font-size: 0.95rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}
.cat-btn:hover  { border-color: var(--gold); color: var(--navy); }
.cat-btn.active { background: var(--navy); color: var(--white); border-color: var(--navy); }

.testimony-card {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 32px 24px;
  display: flex;
  flex-direction: column;
  gap: 16px;
  transition: all 0.25s;
}
.testimony-card:hover { border-color: var(--gold); box-shadow: 0 8px 32px rgba(26,45,90,0.08); }
.tc-quote  { color: var(--gold); font-size: 3rem; line-height: 1; font-family: Georgia, serif; }
.tc-text   { color: var(--text-light); font-size: 1rem; line-height: 1.85; flex: 1; font-style: italic; }
.tc-author { display: flex; align-items: center; gap: 14px; margin-top: 10px; padding-top: 18px; border-top: 1px solid var(--border); }
.tc-avatar {
  width: 46px; height: 46px;
  background: var(--navy);
  color: var(--white);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-weight: 700; font-size: 1.1rem; flex-shrink: 0;
}
.tc-name     { font-weight: 700; font-size: 1rem; color: var(--navy); }
.tc-sub      { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; }
.tc-tag      { font-size: 0.8rem; color: var(--gold); font-weight: 700; letter-spacing: 0.07em; }
.tc-location { font-size: 0.78rem; color: var(--text-light); }

.loading-state, .error-state, .empty-state {
  display: flex; flex-direction: column; align-items: center;
  gap: 14px; padding: 80px 0; color: var(--text-light);
}
.spinner {
  width: 36px; height: 36px;
  border: 3px solid var(--border);
  border-top-color: var(--navy);
  border-radius: 50%;
  animation: spin 0.75s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
</style>
