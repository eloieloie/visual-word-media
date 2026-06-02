<template>
  <div class="page-hero">
    <div class="container">
      <p class="hero-label">To His Glory</p>
      <h1>Stories of Transformation</h1>
      <p>Lives touched, hearts changed, and communities transformed by the grace of God through this ministry.</p>
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

      <div class="testimony-categories">
        <button
          v-for="cat in categories"
          :key="cat"
          :class="['cat-btn', { active: activeCategory === cat }]"
          @click="activeCategory = cat"
        >{{ cat }}</button>
      </div>

      <div class="grid-3" style="margin-top:40px">
        <div class="testimony-card" v-for="t in filteredTestimonies" :key="t.name">
          <div class="tc-quote">❝</div>
          <p class="tc-text">{{ t.text }}</p>
          <div class="tc-author">
            <div class="tc-avatar">{{ t.name[0] }}</div>
            <div>
              <div class="tc-name">{{ t.name }}</div>
              <div class="tc-tag">{{ t.tag }}</div>
            </div>
          </div>
        </div>
      </div>
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
import { ref, computed } from 'vue'

const categories = ['All', 'Youth Testimonies', 'Media Recovery', 'Camp Experiences', 'Creative Artists', 'Ministry Partners']
const activeCategory = ref('All')

const testimonies = [
  { name: 'Priya R.', tag: 'Youth Testimonies', text: 'The 60x360 discipleship camp completely changed my life. I came in confused about my faith and left with a clear sense of God\'s calling. I\'m now actively serving in my village church.' },
  { name: 'Samuel K.', tag: 'Media Recovery', text: 'I struggled with social media addiction for years. Through IMPACT\'s counseling, I learned to set boundaries and redirect my time toward God\'s purposes. My family relationships have been restored.' },
  { name: 'Ananya D.', tag: 'Camp Experiences', text: 'The leadership camp taught me that my creativity is a gift from God meant to be used for His glory. I now lead a creative arts group in my church and mentor younger artists.' },
  { name: 'David M.', tag: 'Creative Artists', text: 'Oculus gave me a community of believers who understand the tension between creativity and faith. I\'ve found my calling as a filmmaker who tells stories of redemption.' },
  { name: 'Rebekah S.', tag: 'Media Recovery', text: 'After struggling with loneliness and depression amplified by social media comparison, New Life ministry gave me hope. I\'m now counseling others through similar struggles.' },
  { name: 'Jonathan T.', tag: 'Ministry Partners', text: 'Partnering with Visual Word Media Mission has transformed our church\'s media ministry. We now have a trained team producing content that reaches our entire city.' },
  { name: 'Meera P.', tag: 'Youth Testimonies', text: 'At 17, I found purpose through the 60x360 movement. We conducted our first village outreach last month and saw 12 people come to faith. God is doing incredible things.' },
  { name: 'Thomas A.', tag: 'Camp Experiences', text: 'The discipleship camp wasn\'t just a weekend event — it set me on a course of intentional spiritual growth. The mentorship I received has shaped my entire walk with God.' },
  { name: 'Grace N.', tag: 'Creative Artists', text: 'Through Network105, my written reflections are now reaching thousands. I never thought my words could impact so many lives. God has blown my expectations away.' },
]

const filteredTestimonies = computed(() =>
  activeCategory.value === 'All' ? testimonies : testimonies.filter(t => t.tag === activeCategory.value)
)
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
.cat-btn:hover { border-color: var(--gold); color: var(--navy); }
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
.tc-quote { color: var(--gold); font-size: 3rem; line-height: 1; font-family: Georgia, serif; }
.tc-text { color: var(--text-light); font-size: 1rem; line-height: 1.85; flex: 1; font-style: italic; }
.tc-author { display: flex; align-items: center; gap: 14px; margin-top: 10px; padding-top: 18px; border-top: 1px solid var(--border); }
.tc-avatar {
  width: 46px; height: 46px;
  background: var(--navy);
  color: var(--white);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 1.1rem;
  flex-shrink: 0;
}
.tc-name { font-weight: 700; font-size: 1rem; color: var(--navy); }
.tc-tag { font-size: 0.82rem; color: var(--gold); font-weight: 700; letter-spacing: 0.07em; }
</style>
