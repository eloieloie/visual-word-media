<template>
  <div class="page-hero">
    <div class="container">
      <p class="hero-label">Grow in Truth</p>
      <h1>Biblical &amp; Media Resources</h1>
      <p>Articles, teachings, Bible studies, and media content to equip you in your faith and calling.</p>
    </div>
  </div>

  <section class="section">
    <div class="container">

      <!-- Category tabs -->
      <div class="cat-tabs">
        <button
          v-for="cat in allCategories"
          :key="cat.key"
          :class="['cat-btn', { active: activeCategory === cat.key }]"
          @click="activeCategory = cat.key"
        >{{ cat.label }}</button>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="loading-state">
        <div class="spinner"></div>
        <span>Loading…</span>
      </div>

      <!-- Error -->
      <div v-else-if="error" class="error-state">
        <span>⚠️ {{ error }}</span>
      </div>

      <!-- Books category -->
      <template v-else-if="activeCategory === 'books'">
        <div v-if="books.length === 0" class="empty-state">
          <p>No books available yet.</p>
        </div>
        <div v-else class="books-grid">
          <div v-for="book in books" :key="book.id" class="book-card">
            <img v-if="book.cover_image" :src="'/' + book.cover_image" :alt="book.title" class="book-cover" />
            <div v-else class="book-cover-placeholder">📖</div>
            <div class="book-body">
              <h3 class="book-title">{{ book.title }}</h3>
              <p v-if="book.description" class="book-desc">{{ book.description }}</p>
              <div class="book-footer">
                <span class="book-price" v-if="parseFloat(book.price) > 0">
                  {{ book.currency }} {{ parseFloat(book.price).toFixed(2) }}
                </span>
                <span class="book-price free" v-else>Free</span>
                <button class="btn btn-gold btn-sm" @click="openEnquiry(book)">Get this Book</button>
              </div>
            </div>
          </div>
        </div>
      </template>

      <!-- Regular resources -->
      <template v-else>
        <div v-if="currentResources.length === 0" class="empty-state">
          <p>No resources in this category yet.</p>
        </div>
        <div v-else class="resources-list">
          <div v-for="r in currentResources" :key="r.id" class="resource-item">
            <!-- Video -->
            <template v-if="r.file_type === 'video'">
              <h4 class="ri-title">{{ r.title }}</h4>
              <p v-if="r.description" class="ri-desc">{{ r.description }}</p>
              <video controls class="ri-video" :src="'/' + r.file_path" preload="metadata"></video>
            </template>
            <!-- Audio -->
            <template v-else-if="r.file_type === 'audio'">
              <div class="ri-audio-card">
                <h4 class="ri-title">{{ r.title }}</h4>
                <p v-if="r.description" class="ri-desc">{{ r.description }}</p>
                <audio controls class="ri-audio" :src="'/' + r.file_path" preload="metadata"></audio>
              </div>
            </template>
            <!-- PDF -->
            <template v-else>
              <div class="ri-pdf-card">
                <div class="ri-pdf-icon">📄</div>
                <div class="ri-pdf-body">
                  <h4 class="ri-title">{{ r.title }}</h4>
                  <p v-if="r.description" class="ri-desc">{{ r.description }}</p>
                  <a :href="'/' + r.file_path" target="_blank" rel="noopener" class="btn btn-outline btn-sm">View PDF</a>
                </div>
              </div>
            </template>
          </div>
        </div>
      </template>

    </div>
  </section>

  <!-- Book Enquiry Modal -->
  <teleport to="body">
    <div v-if="enquiryBook" class="modal-backdrop" @click.self="closeEnquiry">
      <div class="modal-inner">
        <button class="modal-close" @click="closeEnquiry">×</button>
        <h3>Get this Book</h3>
        <p class="modal-book-title">{{ enquiryBook.title }}</p>

        <div v-if="enquirySuccess" class="enquiry-success">
          <div style="font-size:2.5rem;margin-bottom:12px">✅</div>
          <p>{{ enquirySuccess }}</p>
          <button class="btn btn-outline btn-sm" style="margin-top:16px" @click="closeEnquiry">Close</button>
        </div>

        <form v-else @submit.prevent="submitEnquiry" class="enquiry-form">
          <p v-if="enquiryError" class="error-banner">{{ enquiryError }}</p>

          <div class="field">
            <label>Full Name *</label>
            <input v-model="enquiryForm.name" type="text" required placeholder="Your name" />
          </div>
          <div class="field">
            <label>Email Address *</label>
            <input v-model="enquiryForm.email" type="email" required placeholder="you@example.com" />
          </div>
          <div class="field">
            <label>Phone Number</label>
            <input v-model="enquiryForm.phone" type="tel" placeholder="+91 00000 00000" />
          </div>
          <div class="field">
            <label>Book</label>
            <input :value="enquiryBook.title" type="text" disabled />
          </div>
          <button type="submit" class="btn btn-primary" style="width:100%;" :disabled="enquiryLoading">
            {{ enquiryLoading ? 'Sending…' : 'Send Enquiry' }}
          </button>
        </form>
      </div>
    </div>
  </teleport>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { api } from '../services/api.js'

const allCategories = [
  { key: 'articles',           label: 'Articles' },
  { key: 'bible_studies',      label: 'Bible Studies' },
  { key: 'media_awareness',    label: 'Media Awareness' },
  { key: 'family_guidance',    label: 'Family Guidance' },
  { key: 'youth_discipleship', label: 'Youth Discipleship' },
  { key: 'creative_arts',      label: 'Creative Arts' },
  { key: 'video_teachings',    label: 'Video Teachings' },
  { key: 'audio_messages',     label: 'Audio Messages' },
  { key: 'books',              label: '📖 Books' },
]

const activeCategory    = ref('articles')
const loading           = ref(false)
const error             = ref('')
const resourcesByCategory = ref({})
const books             = ref([])

const currentResources  = computed(() => resourcesByCategory.value[activeCategory.value] || [])

async function loadCategory(cat) {
  if (cat === 'books') return loadBooks()
  if (resourcesByCategory.value[cat]) return  // cached
  loading.value = true
  error.value   = ''
  try {
    const data = await api.get(`/resources/index.php?category=${cat}`)
    resourcesByCategory.value[cat] = data.resources || []
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}

async function loadBooks() {
  if (books.value.length) return
  loading.value = true
  error.value   = ''
  try {
    const data = await api.get('/books/index.php')
    books.value = data.books || []
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}

watch(activeCategory, (cat) => loadCategory(cat))
onMounted(() => loadCategory(activeCategory.value))

// ── Book enquiry ──────────────────────────────────────────────────────────────
const enquiryBook    = ref(null)
const enquirySuccess = ref('')
const enquiryError   = ref('')
const enquiryLoading = ref(false)
const enquiryForm    = ref({ name: '', email: '', phone: '' })

function openEnquiry(book) {
  enquiryBook.value    = book
  enquirySuccess.value = ''
  enquiryError.value   = ''
  enquiryForm.value    = { name: '', email: '', phone: '' }
}

function closeEnquiry() {
  enquiryBook.value = null
}

async function submitEnquiry() {
  enquiryError.value   = ''
  enquiryLoading.value = true
  try {
    const res = await api.post('/books/enquiry.php', {
      name:      enquiryForm.value.name,
      email:     enquiryForm.value.email,
      phone:     enquiryForm.value.phone,
      book_name: enquiryBook.value.title,
    })
    enquirySuccess.value = res.message || 'Enquiry sent!'
  } catch (e) {
    enquiryError.value = e.message
  } finally {
    enquiryLoading.value = false
  }
}
</script>

<style scoped>
/* ── Category tabs ── */
.cat-tabs { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 36px; }
.cat-btn {
  padding: 9px 18px;
  border-radius: 6px;
  border: 1.5px solid var(--border);
  background: transparent;
  font-size: 0.88rem;
  font-weight: 600;
  cursor: pointer;
  color: var(--text-light);
  transition: all 0.18s;
}
.cat-btn:hover    { border-color: var(--navy); color: var(--navy); }
.cat-btn.active   { background: var(--navy); color: var(--white); border-color: var(--navy); }

/* ── Resources list ── */
.resources-list { display: flex; flex-direction: column; gap: 32px; }
.resource-item  {}
.ri-title { font-size: 1.1rem; color: var(--navy); margin-bottom: 8px; }
.ri-desc  { font-size: 0.9rem; color: var(--text-light); margin-bottom: 12px; line-height: 1.6; }
.ri-video { width: 100%; max-width: 760px; border-radius: 8px; background: #000; }
.ri-audio { width: 100%; max-width: 600px; }
.ri-audio-card { background: var(--section-bg); padding: 20px 24px; border-radius: 8px; border: 1px solid var(--border); }
.ri-pdf-card { display: flex; gap: 20px; align-items: flex-start; background: var(--section-bg); padding: 20px 24px; border-radius: 8px; border: 1px solid var(--border); }
.ri-pdf-icon { font-size: 2.4rem; flex-shrink: 0; }
.ri-pdf-body {}

/* ── Books ── */
.books-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px; }
.book-card {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 10px;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  transition: box-shadow 0.2s, border-color 0.2s;
}
.book-card:hover { border-color: var(--gold); box-shadow: 0 6px 24px rgba(26,45,90,0.1); }
.book-cover { width: 100%; aspect-ratio: 2/3; object-fit: cover; display: block; max-height: 260px; }
.book-cover-placeholder { width: 100%; aspect-ratio: 2/3; background: var(--section-bg); display: flex; align-items: center; justify-content: center; font-size: 3.5rem; max-height: 260px; }
.book-body { padding: 18px 20px; display: flex; flex-direction: column; flex: 1; }
.book-title { font-family: 'Playfair Display', serif; color: var(--navy); font-size: 1.05rem; margin-bottom: 8px; }
.book-desc  { font-size: 0.85rem; color: var(--text-light); line-height: 1.6; flex: 1; margin-bottom: 14px; }
.book-footer { display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
.book-price { font-size: 1rem; font-weight: 700; color: var(--navy); }
.book-price.free { color: #1a7a4a; }
.btn-sm { padding: 8px 16px; font-size: 0.85rem; }

/* ── States ── */
.loading-state, .error-state, .empty-state {
  display: flex; align-items: center; justify-content: center;
  gap: 14px; padding: 80px 0; color: var(--text-light); flex-direction: column;
}
.spinner { width: 36px; height: 36px; border: 3px solid var(--border); border-top-color: var(--navy); border-radius: 50%; animation: spin 0.75s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
.error-state { color: #c0392b; }

/* ── Modal ── */
.modal-backdrop {
  position: fixed; inset: 0; background: rgba(0,0,0,0.6);
  z-index: 2000; display: flex; align-items: center; justify-content: center; padding: 20px;
}
.modal-inner {
  background: var(--white); border-radius: 12px; width: 100%; max-width: 460px;
  padding: 36px 32px; position: relative;
}
.modal-close {
  position: absolute; top: 16px; right: 18px; background: none; border: none;
  font-size: 1.6rem; cursor: pointer; color: var(--text-light); line-height: 1;
}
.modal-inner h3 { font-family: 'Playfair Display', serif; color: var(--navy); margin-bottom: 6px; }
.modal-book-title { color: var(--gold); font-weight: 600; font-size: 0.95rem; margin-bottom: 20px; }
.enquiry-form { display: flex; flex-direction: column; gap: 16px; }
.field { display: flex; flex-direction: column; gap: 6px; }
.field label { font-size: 0.85rem; font-weight: 600; color: var(--navy); }
.field input {
  padding: 11px 13px; border: 1.5px solid var(--border); border-radius: 6px;
  font-size: 0.95rem; outline: none; transition: border-color 0.2s;
}
.field input:focus { border-color: var(--navy); }
.field input:disabled { background: var(--section-bg); color: var(--text-light); }
.error-banner { background: #fdecea; border: 1px solid #f5c6c4; border-radius: 6px; padding: 10px 14px; color: #c0392b; font-size: 0.88rem; }
.enquiry-success { text-align: center; padding: 16px 0; color: var(--text); font-size: 0.97rem; }

@media (max-width: 860px) { .books-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 540px) { .books-grid { grid-template-columns: 1fr; } .modal-inner { padding: 28px 20px; } }
</style>
