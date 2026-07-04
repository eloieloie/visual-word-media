<template>
  <div class="page-hero">
    <div class="container">
      <p class="hero-label">Est. 1997</p>
      <h1>About Visual Word</h1>
      <p>A ministry born in the presence of God, committed to declaring His glory through creativity, media, and Kingdom service.</p>
    </div>
  </div>

  <!-- WHO WE ARE -->
  <section class="section">
    <div class="container two-col">
      <div>
        <p class="section-label">Who We Are</p>
        <h2 class="section-title">A Christ-Centered Media Ministry</h2>
        <div class="divider"></div>
        <p>Visual Word Media is a Christian media ministry founded with a burden to serve today's generation amidst the growing influence of media and culture. We seek to equip individuals to face contemporary media challenges while helping them live fruitful lives in the image of God.</p>
        <p style="margin-top:16px">Since 1997, the ministry has been involved in media awareness, youth discipleship, creative arts, producing digital Christian tools, counseling, and Biblical engagement initiatives.</p>
      </div>
      <div class="vwm-cards">
        <div class="vwm-card">
          <img class="vwm-photo" :src="img('/images/stock/photo-1529074963764-98f45c47344b.jpg')" alt="Prayer and vision planning" />
          <h3>Vision</h3>
          <p>Equipping individuals to face the contemporary challenge of media, thereby living a fruitful life in the image of God.</p>
        </div>
        <div class="vwm-card gold">
          <img class="vwm-photo" :src="img('/images/stock/photo-1488190211105-8b0e65b80b4e.jpg')" alt="Mission media outreach" />
          <h3>Mission</h3>
          <p>Reaching out to media users and media makers with the message of Truth and Reality, nurturing them in their relationship with their Maker.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- SCRIPTURE -->
  <section class="section-sm" style="background:var(--section-bg)">
    <div class="container" style="max-width:760px">
      <div class="scripture-block">
        "The heavens declare the glory of God; the skies proclaim the work of His hands."
        <span class="scripture-ref">— Psalm 19:1</span>
      </div>
    </div>
  </section>

  <!-- ORIGIN STORY -->
  <section class="section">
    <div class="container" style="max-width:820px">
      <p class="section-label" style="text-align:center">Our Journey</p>
      <h2 class="section-title" style="text-align:center">A Vision Born in the Presence of God</h2>
      <div class="divider divider-center"></div>

      <div class="story-block">
        <p>Every ministry has a beginning, but some begin with a burden placed directly upon the heart by God.</p>
        <p>Visual Word Media Mission was founded by <strong>John Christopher</strong> through a life-changing encounter during a personal time of prayer and meditation on God's Word. One day, while reflecting on the Scriptures, his attention was drawn to the powerful declaration found in Psalm 19.</p>
        <p>As he looked into the vast open sky, he began to contemplate the incredible reality of the modern world. The same heavens created to proclaim the glory of God were now filled with countless invisible signals carrying information across the globe — radio waves, television broadcasts, mobile networks, satellites, and the internet.</p>
        <div class="story-quote">
          <p>At that moment, a deep spiritual burden gripped his heart. He realized that while technology had connected the world like never before, much of the content flowing through these channels was influencing minds, shaping cultures, and often leading people away from God's truth.</p>
          <p>God impressed upon him a simple but powerful question:</p>
          <blockquote>"Who will use these same channels to proclaim My glory?"</blockquote>
          <p class="story-conclusion">That question became a calling. That burden became a vision. That vision became <strong>Visual Word Media Mission.</strong></p>
        </div>
      </div>
    </div>
  </section>

  <!-- RESPONDING TO CALL -->
  <section class="section" style="background:var(--navy); color:var(--white)">
    <div class="container">
      <div style="text-align:center; margin-bottom:52px">
        <p class="section-label">The Founder's Conviction</p>
        <h2 style="color:var(--white); font-size:clamp(1.8rem,3vw,2.5rem)">Responding to God's Call</h2>
        <div class="divider divider-center"></div>
      </div>
      <div class="conviction-grid">
        <div class="conviction-card" v-for="c in convictions" :key="c">
          <span class="conv-bullet"></span>
          <p>{{ c }}</p>
        </div>
      </div>
      <p class="conviction-footer">With this conviction, Visual Word Media Mission was established to harness the power of media, technology, creativity, education, and discipleship to communicate the life-transforming message of Jesus Christ.</p>
    </div>
  </section>

  <!-- IMPACT AREAS -->
  <section class="section">
    <div class="container">
      <div style="text-align:center; margin-bottom:52px">
        <p class="section-label">How We Serve</p>
        <h2 class="section-title">Impacting Lives Through Multiple Platforms</h2>
        <div class="divider divider-center"></div>
      </div>
      <div class="grid-3">
        <div class="card" v-for="area in impactAreas" :key="area.title">
          <img class="impact-photo" :src="area.image" :alt="area.title" />
          <h3 style="color:var(--navy); margin-bottom:10px">{{ area.title }}</h3>
          <p style="color:var(--text-light); font-size:0.92rem; line-height:1.7">{{ area.desc }}</p>
        </div>
      </div>
    </div>
  </section>

  <!-- FROM VISION TO MISSION -->
  <section class="section-sm" style="background:var(--section-bg)">
    <div class="container" style="max-width:860px">
      <p class="section-label" style="text-align:center">Purpose</p>
      <h2 class="section-title" style="text-align:center">From Vision to Mission</h2>
      <div class="divider divider-center"></div>
      <p style="text-align:center; color:var(--text-light); margin-bottom:40px">Visual Word Media Mission exists to:</p>
      <div class="mission-list">
        <div class="mission-item" v-for="m in missions" :key="m">
          <span class="mission-bullet"></span>
          <span>{{ m }}</span>
        </div>
      </div>
    </div>
  </section>

  <!-- PRAYER -->
  <section class="section-sm" style="background:var(--gold-pale); border-top:3px solid var(--gold)">
    <div class="container" style="max-width:760px; text-align:center">
      <p class="section-label">Our Prayer</p>
      <h2 class="section-title">Looking Toward the Future</h2>
      <div class="divider divider-center"></div>
      <p style="color:var(--text-light); line-height:1.85; margin-bottom:24px">
        Our prayer is that every life touched through this ministry will come to know Jesus Christ more deeply, discover God's purpose, and become a shining witness of His love in a world searching for truth.
      </p>
      <p class="outro-tagline">Visual Word Media Mission — <em>Declaring God's Glory Through Creativity, Media, Technology, and Kingdom Service.</em></p>
    </div>
  </section>
</template>

<script setup>
import { img } from '../composables/useBaseUrl.js'
const convictions = [
  'Every camera can become a pulpit.',
  'Every screen can become a platform for truth.',
  'Every creative gift can become an instrument for God\'s glory.',
  'Every digital channel can become a pathway for the Gospel.',
]

const impactAreas = [
  { image: img('/images/stock/photo-1511632765486-a01980e01a18.jpg'), title: 'Youth Empowerment Programs', desc: 'Helping young people discover their God-given identity, purpose, talents, and calling while equipping them to navigate modern challenges with biblical wisdom.' },
  { image: img('/images/stock/photo-1492724441997-5dc865305da7.jpg'), title: 'Media and Creative Ministry', desc: 'Training and encouraging filmmakers, photographers, designers, musicians, writers, and media professionals to use their skills for Kingdom impact.' },
  { image: img('/images/stock/photo-1523240795612-9a054b0db644.jpg'), title: 'Awareness & Educational Programs', desc: 'Conducting seminars, workshops, conferences, and outreach events that address spiritual, social, personal, and professional development.' },
  { image: img('/images/stock/photo-1503676260728-1c00da094a0b.jpg'), title: 'Discipleship & Leadership Camps', desc: 'Building strong foundations of faith through mentoring, biblical teaching, leadership development, and spiritual formation.' },
  { image: img('/images/stock/photo-1511895426328-dc8714191300.jpg'), title: 'Family & Counseling Initiatives', desc: 'Providing encouragement, guidance, and support to strengthen marriages, families, and individuals facing life\'s challenges.' },
  { image: img('/images/stock/photo-1517048676732-d65bc937f952.jpg'), title: 'Content Production & Digital Outreach', desc: 'Creating meaningful media content that communicates biblical truth, inspires transformation, and reaches audiences across diverse platforms.' },
]

const missions = [
  'Proclaim the Gospel through modern media.',
  'Equip believers to use their gifts for God\'s Kingdom.',
  'Inspire young people to live purpose-driven lives.',
  'Restore biblical values in families and communities.',
  'Mentor the next generation of Christian leaders and creatives.',
  'Create Christ-centered media content that impacts society.',
  'Train media professionals to serve God through excellence and integrity.',
]
</script>

<style scoped>
.two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: start; }
.two-col p { color: var(--text-light); line-height: 1.9; font-size: 1.05rem; }
.vwm-cards { display: flex; flex-direction: column; gap: 22px; }
.vwm-card {
  padding: 32px 28px;
  border-radius: 8px;
  background: var(--navy);
  color: var(--white);
}
.vwm-card.gold { background: var(--gold-pale); color: var(--text); border: 1px solid var(--gold); }
.vwm-card h3 { margin-bottom: 12px; font-size: 1.2rem; }
.vwm-card:not(.gold) h3 { color: var(--gold); }
.vwm-card.gold h3 { color: var(--navy); }
.vwm-card p { font-size: 1rem; line-height: 1.85; opacity: 0.92; }
.vwm-photo {
  width: 100%;
  height: 160px;
  object-fit: cover;
  border-radius: 6px;
  margin-bottom: 14px;
}

.impact-photo {
  width: 100%;
  height: 150px;
  object-fit: cover;
  border-radius: 6px;
  margin-bottom: 16px;
}

.story-block { display: flex; flex-direction: column; gap: 20px; color: var(--text-light); line-height: 1.9; font-size: 1.05rem; }
.story-block strong { color: var(--text); }
.story-quote {
  background: var(--section-bg);
  border-left: 5px solid var(--gold);
  padding: 32px 32px 32px 36px;
  border-radius: 0 8px 8px 0;
  display: flex;
  flex-direction: column;
  gap: 16px;
}
.story-quote blockquote {
  font-family: 'Playfair Display', serif;
  font-style: italic;
  font-size: 1.3rem;
  color: var(--navy);
  padding: 16px 0;
  line-height: 1.7;
}
.story-conclusion { font-weight: 600; color: var(--text); font-size: 1.05rem; }

.conviction-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 40px; }
.conviction-card {
  background: rgba(255,255,255,0.07);
  border: 1px solid rgba(201,162,39,0.25);
  border-radius: 8px;
  padding: 32px 28px;
  display: flex;
  align-items: flex-start;
  gap: 18px;
}
.conv-bullet {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: var(--gold);
  box-shadow: 0 0 0 5px rgba(201,162,39,0.18);
  margin-top: 9px;
  flex-shrink: 0;
}
.conviction-card p { font-size: 1.1rem; line-height: 1.7; font-style: italic; }
.conviction-footer { text-align: center; color: rgba(255,255,255,0.78); font-size: 1rem; line-height: 1.85; max-width: 700px; margin: 0 auto; }

.mission-list { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
.mission-item {
  display: flex;
  align-items: flex-start;
  gap: 14px;
  padding: 16px 18px;
  background: var(--white);
  border-radius: 6px;
  border: 1px solid var(--border);
  font-size: 1rem;
  line-height: 1.6;
}
.mission-bullet {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: var(--gold);
  margin-top: 8px;
  flex-shrink: 0;
}
.outro-tagline { font-family: 'Playfair Display', serif; font-size: 1.15rem; color: var(--navy); }

@media (max-width: 760px) {
  .two-col, .conviction-grid, .mission-list { grid-template-columns: 1fr; }
}
</style>
