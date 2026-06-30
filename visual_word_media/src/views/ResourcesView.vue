<template>
  <div class="page-hero">
    <div class="container">
      <p class="hero-label">Grow in Truth</p>
      <h1>Biblical & Media Resources</h1>
      <p>Articles, teachings, Bible studies, and media content to equip you in your faith and calling.</p>
    </div>
  </div>

  <section class="section">
    <div class="container">
      <div class="resource-filter">
        <button v-for="cat in categories" :key="cat" :class="['cat-btn', { active: activeCategory === cat }]" @click="activeCategory = cat">{{ cat }}</button>
      </div>
      <div class="grid-3" style="margin-top:36px">
        <div class="resource-card" v-for="r in filteredResources" :key="r.title">
          <img class="rc-photo" :src="r.image" :alt="r.title" />
          <div class="rc-type">{{ r.type }}</div>
          <h3>{{ r.title }}</h3>
          <p>{{ r.desc }}</p>
          <div class="rc-footer">
            <span class="rc-tag">{{ r.category }}</span>
            <RouterLink to="/contact" class="rc-link">Access</RouterLink>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CATEGORIES -->
  <section class="section-sm" style="background:var(--section-bg)">
    <div class="container">
      <div style="text-align:center; margin-bottom:40px">
        <p class="section-label">What We Offer</p>
        <h2 class="section-title">Resource Categories</h2>
        <div class="divider divider-center"></div>
      </div>
      <div class="grid-4">
        <div class="rcat-card" v-for="c in resourceCategories" :key="c.label">
          <img class="rcat-photo" :src="c.image" :alt="c.label" />
          <h4>{{ c.label }}</h4>
        </div>
      </div>
    </div>
  </section>

  <section class="section-sm" style="background:var(--navy); text-align:center">
    <div class="container" style="max-width:640px">
      <p class="section-label" style="color:var(--gold)">Stay Equipped</p>
      <h2 style="color:var(--white)">Request Resources or Suggest Topics</h2>
      <p style="color:rgba(255,255,255,0.72); margin:14px 0 28px">Have a topic you'd like us to address? We're always developing new content to serve the Church and next generation.</p>
      <RouterLink to="/contact" class="btn btn-primary">Get in Touch</RouterLink>
    </div>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue'

const categories = ['All', 'Articles', 'Bible Studies', 'Media Awareness', 'Family Guidance', 'Youth Discipleship', 'Creative Arts', 'Video Teachings', 'Audio Messages']
const activeCategory = ref('All')

const resources = [
  { type: 'Article', image: '/images/realistic/resources/media-awareness.jpg', title: 'Navigating Social Media with Biblical Wisdom', desc: 'A practical guide for believers on engaging social media responsibly while guarding their hearts and minds.', category: 'Media Awareness' },
  { type: 'Bible Study', image: '/images/realistic/resources/bible-studies.jpg', title: 'The Psalms and Creative Expression', desc: 'An 8-week study exploring how the Psalms model authentic, God-centered creativity and artistic expression.', category: 'Bible Studies' },
  { type: 'Video Teaching', image: '/images/realistic/resources/video-teachings.jpg', title: 'Understanding Digital Addiction', desc: 'A video series examining the psychological and spiritual dimensions of screen addiction and practical steps to freedom.', category: 'Media Awareness' },
  { type: 'Article', image: '/images/realistic/resources/family-guidance.jpg', title: 'Raising God-Centered Children in a Digital Age', desc: 'Practical wisdom for parents navigating technology, screen time, and digital discipleship in the home.', category: 'Family Guidance' },
  { type: 'Audio Message', image: '/images/realistic/resources/audio-messages.jpg', title: 'Your Gift, God\'s Glory', desc: 'A powerful message on discovering your God-given creative gifts and surrendering them for Kingdom purposes.', category: 'Creative Arts' },
  { type: 'Bible Study', image: '/images/realistic/resources/youth-discipleship.jpg', title: 'Acts 13:36 — Serving Your Generation', desc: 'A 4-week study on David\'s call to serve his generation and how we can apply that calling in today\'s digital world.', category: 'Youth Discipleship' },
  { type: 'Article', image: '/images/realistic/resources/family-guidance.jpg', title: 'Pornography: A Biblical Response', desc: 'A compassionate and Biblical approach to addressing pornography addiction, offering hope and practical steps for recovery.', category: 'Family Guidance' },
  { type: 'Video Teaching', image: '/images/realistic/resources/media-awareness.jpg', title: 'Media, Culture & the Christian Mind', desc: 'A 6-part video series developing a Biblical worldview for engaging media, arts, and popular culture.', category: 'Media Awareness' },
  { type: 'Audio Message', image: '/images/realistic/resources/youth-discipleship.jpg', title: 'Prayer for the Next Generation', desc: 'A guided prayer session for parents, pastors, and leaders interceding for today\'s youth and their spiritual formation.', category: 'Youth Discipleship' },
]

const filteredResources = computed(() =>
  activeCategory.value === 'All' ? resources : resources.filter(r => r.category === activeCategory.value)
)

const resourceCategories = [
  { image: '/images/gemini/articles.png', label: 'Articles' },
  { image: '/images/gemini/bible-studies_2.png', label: 'Bible Studies' },
  { image: '/images/gemini/media-awareness.png', label: 'Media Awareness' },
  { image: '/images/gemini/family-guidance.png', label: 'Family Guidance' },
  { image: '/images/gemini/youth-discipleship.png', label: 'Youth Discipleship' },
  { image: '/images/gemini/creative-arts.png', label: 'Creative Arts' },
  { image: '/images/gemini/video-teachings.png', label: 'Video Teachings' },
  { image: '/images/gemini/audio-messages.png', label: 'Audio Messages' },
]
</script>

<style scoped>
.resource-filter { display: flex; flex-wrap: wrap; gap: 10px; }
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

.resource-card {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: 28px 22px;
  display: flex;
  flex-direction: column;
  gap: 12px;
  transition: all 0.25s;
}
.resource-card:hover { border-color: var(--gold); box-shadow: 0 8px 28px rgba(26,45,90,0.08); transform: translateY(-2px); }
.rc-photo { width: 100%; height: 140px; object-fit: cover; border-radius: 6px; }
.rc-type { font-size: 0.82rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: var(--gold); }
.resource-card h3 { color: var(--navy); font-size: 1.1rem; line-height: 1.45; }
.resource-card p { color: var(--text-light); font-size: 1rem; line-height: 1.8; flex: 1; }
.rc-footer { display: flex; align-items: center; justify-content: space-between; margin-top: 6px; padding-top: 16px; border-top: 1px solid var(--border); }
.rc-tag { font-size: 0.8rem; background: var(--gold-pale); color: var(--navy); padding: 5px 12px; border-radius: 10px; font-weight: 700; }
.rc-link { font-size: 0.95rem; font-weight: 700; color: var(--navy); transition: color 0.2s; }
.rc-link:hover { color: var(--gold); }

.rcat-card {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: 24px;
  text-align: center;
  display: flex;
  flex-direction: column;
  gap: 10px;
  align-items: center;
  transition: all 0.2s;
}
.rcat-card:hover { border-color: var(--gold); background: var(--gold-pale); }
.rcat-photo { width: 100%; height: 90px; object-fit: cover; border-radius: 6px; }
.rcat-card h4 { color: var(--navy); font-size: 0.9rem; }
</style>
