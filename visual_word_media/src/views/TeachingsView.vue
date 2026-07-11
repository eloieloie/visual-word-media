<template>
  <div class="page-hero">
    <div class="container">
      <p class="hero-label">Learn &amp; Grow</p>
      <h1>Media</h1>
      <p>Video messages from our youtube channels.</p>
    </div>
  </div>

  <section class="section">
    <div class="container">

      <div>
        <div v-if="videoLoading" class="loading-state">
          <div class="spinner"></div>
          <p>Loading videos…</p>
        </div>
        <div v-else-if="videoError" class="error-state">
          <p>⚠️ {{ videoError }}</p>
          <button class="btn btn-outline" @click="loadVideos">Retry</button>
        </div>
        <div v-else-if="!videos.length" class="empty-state">
          <p>No videos found. Check back soon.</p>
        </div>
        <div v-else class="video-grid">
          <div
            class="video-card"
            v-for="v in videos"
            :key="v.id"
            @click="playVideo(v)"
          >
            <div class="video-thumb">
              <img :src="v.thumbnail" :alt="v.title" loading="lazy" />
              <div class="play-overlay">
                <span class="play-icon"></span>
              </div>
            </div>
            <div class="video-info">
              <h3>{{ v.title }}</h3>
              <p class="video-date">{{ v.date }}</p>
              <p class="video-desc" v-if="v.description">{{ truncate(v.description, 100) }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ─── Video Modal ──────────────────────────────────────── -->
  <Teleport to="body">
    <div class="modal-backdrop" v-if="activeVideo" @click.self="activeVideo = null">
      <div class="modal-inner">
        <button class="modal-close" @click="activeVideo = null">×</button>
        <div class="iframe-wrap">
          <iframe
            :src="`${activeVideo.embed}?autoplay=1&rel=0`"
            frameborder="0"
            allow="autoplay; encrypted-media; picture-in-picture"
            allowfullscreen
          ></iframe>
        </div>
        <div class="modal-title">{{ activeVideo.title }}</div>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { api } from '../services/api.js'

const videos       = ref([])
const videoLoading = ref(true)
const videoError   = ref('')
const activeVideo  = ref(null)

function truncate(str, len) {
  return str.length > len ? str.slice(0, len) + '…' : str
}

function playVideo(v) {
  activeVideo.value = v
}

async function loadVideos() {
  videoLoading.value = true
  videoError.value   = ''
  try {
    const data   = await api.get('/videos/index.php')
    videos.value = data.videos || []
  } catch (e) {
    videoError.value = e.message
  } finally {
    videoLoading.value = false
  }
}

onMounted(() => {
  loadVideos()
})
</script>

<style scoped>
/* ─── Video Grid ─────────────────────────────────────────── */
.video-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
.video-card { background: var(--white); border: 1px solid var(--border); border-radius: 10px; overflow: hidden; cursor: pointer; transition: all 0.22s; }
.video-card:hover { transform: translateY(-4px); box-shadow: 0 8px 32px rgba(26,45,90,0.13); border-color: var(--gold); }
.video-thumb { position: relative; aspect-ratio: 16/9; overflow: hidden; }
.video-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s; display: block; }
.video-card:hover .video-thumb img { transform: scale(1.04); }
.play-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.28); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.2s; }
.video-card:hover .play-overlay { opacity: 1; }
.play-icon {
  width: 54px;
  height: 54px;
  border-radius: 50%;
  background: rgba(255,255,255,0.92);
  display: inline-block;
  position: relative;
}
.play-icon::after {
  content: '';
  position: absolute;
  left: 21px;
  top: 16px;
  width: 0;
  height: 0;
  border-top: 10px solid transparent;
  border-bottom: 10px solid transparent;
  border-left: 14px solid var(--navy);
}
.video-info { padding: 16px 18px 20px; }
.video-info h3 { font-size: 1rem; color: var(--navy); margin-bottom: 5px; line-height: 1.4; }
.video-date { font-size: 0.78rem; color: var(--gold); font-weight: 600; margin-bottom: 6px; }
.video-desc { font-size: 0.85rem; color: var(--text-light); line-height: 1.6; }

.modal-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,0.78); z-index: 2000; display: flex; align-items: center; justify-content: center; padding: 20px; }
.modal-inner { background: #000; border-radius: 10px; width: 100%; max-width: 860px; position: relative; }
.modal-close { position: absolute; top: -18px; right: -18px; width: 38px; height: 38px; border-radius: 50%; background: var(--white); border: none; font-size: 1.4rem; cursor: pointer; line-height: 1; z-index: 10; }
.iframe-wrap { position: relative; aspect-ratio: 16/9; }
.iframe-wrap iframe { position: absolute; inset: 0; width: 100%; height: 100%; border-radius: 10px 10px 0 0; }
.modal-title { padding: 14px 18px; color: rgba(255,255,255,0.85); font-size: 0.95rem; font-family: 'Playfair Display', serif; border-radius: 0 0 10px 10px; }

/* ─── States ─────────────────────────────────────────────── */
.loading-state, .error-state, .empty-state { display: flex; flex-direction: column; align-items: center; gap: 14px; padding: 80px 0; color: var(--text-light); }
.spinner { width: 36px; height: 36px; border: 3px solid var(--border); border-top-color: var(--navy); border-radius: 50%; animation: spin 0.75s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }

@media (max-width: 900px) { .video-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 580px) {
  .video-grid { grid-template-columns: 1fr; }
}
</style>
