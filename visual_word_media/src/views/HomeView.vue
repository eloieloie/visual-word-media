<template>
  <!-- HERO -->
  <section class="hero">
    <div class="hero-bg"></div>
    <div class="container hero-content">
      <p class="hero-label">Visual Word Media Mission · Est. 1997</p>
      <h1>Serving Today's Generation<br><em>in the Will of God</em></h1>
      <p class="hero-sub">
        A Christ-centered media ministry committed to equipping individuals, nurturing creative artists,
        discipling youth, and transforming society through Biblical truth and media engagement.
      </p>
      <div class="hero-btns">
        <RouterLink to="/ministries" class="btn btn-primary">Explore Our Ministries</RouterLink>
        <RouterLink to="/volunteer" class="btn btn-outline">Join the Mission</RouterLink>
        <RouterLink to="/prayer" class="btn btn-outline">Prayer Partnership</RouterLink>
        <RouterLink to="/contact" class="btn btn-outline">Contact Us</RouterLink>
      </div>
      <p class="hero-scripture-title">Scriptural Foundation of the Ministry</p>
      <div class="hero-scriptures">
        <span>Psalm 19:1</span>
        <span>·</span>
        <span>Proverbs 24:11</span>
        <span>·</span>
        <span>Acts 13:36</span>
      </div>
    </div>
    <div class="hero-scroll">
      <span>↓</span>
    </div>
  </section>

  <!-- INTRO STRIP -->
  <div class="highlight-strip">
    Reaching Media Users & Media Makers with the Message of Truth and Reality since 1997
  </div>

  <!-- ABOUT INTRO -->
  <section class="section">
    <div class="container about-grid">
      <div class="about-text">
        <p class="section-label">Who We Are</p>
        <h2 class="section-title">A Ministry Called to Reach a Media-Driven Generation</h2>
        <div class="divider"></div>
        <p>
          Visual Word Media is a ministry dedicated to reaching both media users and media makers with the
          message of Truth and Reality, nurturing them in their relationship with their Creator.
        </p>
        <p style="margin-top:16px">
          Since 1997, the ministry has been serving churches, youth, children, families, artists, and media
          professionals through discipleship, awareness, counseling, creative arts, and media-based ministry initiatives.
        </p>
        <div class="about-stats">
          <div class="stat"><span class="stat-num">1997</span><span class="stat-label">Founded</span></div>
          <div class="stat"><span class="stat-num">6+</span><span class="stat-label">Ministry Wings</span></div>
        </div>
        <RouterLink to="/about" class="btn btn-navy" style="margin-top:28px">Our Full Story</RouterLink>
      </div>
      <div class="about-visual">
        <div class="visual-card primary">
          <img class="vc-photo" src="/images/stock/photo-1504052434569-70ad5836ab65.jpg" alt="Open Bible and cross" />
          <h3>Our Vision</h3>
          <p>Equipping individuals to face the contemporary challenge of media, thereby living a fruitful life in the image of God.</p>
        </div>
        <div class="visual-card secondary">
          <img class="vc-photo" src="/images/stock/photo-1487611459768-bd414656ea10.jpg" alt="Media mission workspace" />
          <h3>Our Mission</h3>
          <p>Reaching out to media users and media makers with the message of Truth and Reality, nurturing them in their relationship with their Maker.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- SCRIPTURE -->
  <section class="section-sm" style="background:var(--section-bg)">
    <div class="container" style="max-width:780px">
      <div class="scripture-block">
        "The heavens declare the glory of God; the skies proclaim the work of His hands."
        <span class="scripture-ref">— Psalm 19:1</span>
      </div>
    </div>
  </section>

  <!-- MINISTRY WINGS -->
  <section class="section">
    <div class="container">
      <div style="text-align:center; margin-bottom:52px">
        <p class="section-label">What We Do</p>
        <h2 class="section-title">Our Ministry Wings</h2>
        <div class="divider divider-center"></div>
        <p class="section-subtitle" style="margin:0 auto">Six focused areas of ministry reaching youth, creatives, families, villages, and digital audiences for the Kingdom of God.</p>
      </div>
      <div class="grid-3">
        <RouterLink to="/ministries" class="ministry-card" v-for="m in ministries" :key="m.name">
          <img class="mc-photo" :src="m.image" :alt="m.name + ' ministry'" />
          <h3>{{ m.name }}</h3>
          <p class="mc-tag">{{ m.tag }}</p>
          <p>{{ m.desc }}</p>
          <span class="mc-link">Learn more</span>
        </RouterLink>
      </div>
    </div>
  </section>

  <!-- SCRIPTURE 2 -->
  <section class="section-sm navy-section">
    <div class="container" style="text-align:center; max-width:720px">
      <p class="section-label" style="color:var(--gold)">The Heart Behind the Mission</p>
      <h2 style="color:var(--white); font-size:clamp(1.5rem,3vw,2.2rem); margin-bottom:20px">
        "Who will use these same channels to proclaim My glory?"
      </h2>
      <p style="color:rgba(255,255,255,0.75); font-size:1rem; line-height:1.8">
        That question became a calling. That burden became a vision. That vision became Visual Word Media Mission.
      </p>
      <RouterLink to="/about" class="btn btn-primary" style="margin-top:32px">Read Our Story</RouterLink>
    </div>
  </section>

  <!-- CORE BURDEN -->
  <section class="section">
    <div class="container">
      <div class="burden-grid">
        <div>
          <p class="section-label">Our Calling</p>
          <h2 class="section-title">Burden</h2>
          <div class="divider"></div>
          <p style="color:var(--text-light); margin-bottom:32px">
            God has placed a deep burden upon this ministry to serve, disciple, create, and impact today's generation.
          </p>
          <RouterLink to="/volunteer" class="btn btn-primary">Be Part of the Mission</RouterLink>
        </div>
        <div class="burden-list">
          <div class="burden-item" v-for="b in burdens" :key="b">
            <span class="burden-check"></span>
            <span>{{ b }}</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="section-sm" style="background:var(--gold-pale); border-top:3px solid var(--gold)">
    <div class="container cta-section">
      <div>
        <h2 class="section-title">Ready to Join the Mission?</h2>
        <p style="color:var(--text-light); margin-top:8px">"Each of you should use whatever gift you have received to serve others." — 1 Peter 4:10</p>
      </div>
      <div class="cta-buttons">
        <RouterLink to="/volunteer" class="btn btn-primary">Become a Volunteer</RouterLink>
        <RouterLink to="/prayer" class="btn btn-navy">Become a Prayer Partner</RouterLink>
        <RouterLink to="/contact" class="btn btn-outline" style="color:var(--navy);border-color:var(--navy)">Contact Our Team</RouterLink>
      </div>
    </div>
  </section>
</template>

<script setup>
const ministries = [
  { image: '/images/gemini/impact_01.png', name: 'IMPACT', tag: 'Media Awareness', desc: 'Helping individuals understand, discern, and respond Biblically to the influence of media in modern society.' },
  { image: '/images/gemini/60x360.png', name: '60x360', tag: 'Youth Discipleship', desc: 'A movement to reach villages around Hyderabad through evangelism, discipleship, and leadership.' },
  { image: '/images/gemini/production.png', name: 'PRODUCTION', tag: 'Media for the Kingdom', desc: 'Creating Biblical and educational visual resources to serve churches, ministries, children, and communities.' },
  { image: '/images/gemini/oculus.png', name: 'OCULUS', tag: 'Creative Arts', desc: 'Nurturing a generation of creative artists who will influence culture through a Biblical worldview.' },
  { image: '/images/gemini/network105.png', name: 'NETWORK105', tag: 'Digital Outreach', desc: 'A network of digital content initiatives engaging different audiences through Christ-centered media.' },
  { image: '/images/gemini/new_life.png', name: 'NEW LIFE', tag: 'Hope & Counseling', desc: 'Bringing hope, healing, and support to individuals struggling emotionally, mentally, and spiritually.' },
]

const burdens = [
  'To serve the Church and society through media',
  'To disciple the younger generation',
  'To nurture Biblically grounded creative professionals',
  'To bring awareness about the influence of media',
  'To use visual communication for evangelism and spiritual growth',
  'To raise Christ-centered youth leaders and artists',
]
</script>

<style scoped>
/* HERO */
.hero {
  min-height: 100vh;
  display: flex;
  align-items: center;
  position: relative;
  overflow: hidden;
}
.hero-bg {
  position: absolute;
  inset: 0;
  background:
    linear-gradient(112deg, rgba(8, 18, 39, 0.9) 0%, rgba(26, 45, 90, 0.78) 48%, rgba(36, 52, 112, 0.78) 100%),
    url('/images/stock/photo-1438232992991-995b7058bbb3.jpg') center/cover no-repeat;
}
.hero-bg::after {
  content: '';
  position: absolute;
  inset: 0;
  background: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23c9a227' fill-opacity='0.03'%3E%3Cpath d='M0 0h80v80H0z'/%3E%3Cpath d='M40 20v40M20 40h40' stroke='%23c9a227' stroke-opacity='0.06' stroke-width='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.hero-content {
  position: relative;
  padding: 120px 8px 80px;
  max-width: 860px;
}
.hero-label {
  font-size: 0.83rem;
  letter-spacing: 0.22em;
  text-transform: uppercase;
  color: var(--gold);
  margin-bottom: 28px;
}
.hero-content h1 {
  font-size: clamp(2.4rem, 5.5vw, 4.4rem);
  color: var(--white);
  margin-bottom: 28px;
  line-height: 1.15;
}
.hero-content h1 em { color: var(--gold); font-style: italic; }
.hero-sub {
  font-size: 1.2rem;
  color: rgba(255,255,255,0.82);
  max-width: 640px;
  line-height: 1.9;
  margin-bottom: 44px;
}
.hero-btns { display: flex; flex-wrap: nowrap; gap: 12px; margin-bottom: 24px; }
.hero-btns .btn { padding: 12px 18px; font-size: 0.9rem; }
.hero-scripture-title {
  margin-bottom: 8px;
  padding-top: 12px;
  border-top: 1px solid rgba(255, 255, 255, 0.35);
  max-width: 460px;
  font-size: 0.78rem;
  color: rgba(255,255,255,0.82);
  letter-spacing: 0.13em;
  text-transform: uppercase;
}
.hero-scriptures {
  display: flex;
  gap: 14px;
  align-items: center;
  font-size: 0.88rem;
  color: rgba(255,255,255,0.5);
  letter-spacing: 0.07em;
}
.hero-scroll {
  position: absolute;
  bottom: 32px;
  left: 50%;
  transform: translateX(-50%);
  color: rgba(255,255,255,0.35);
  font-size: 1.4rem;
  animation: bounce 2s infinite;
}
@keyframes bounce { 0%,100%{transform:translateX(-50%) translateY(0)} 50%{transform:translateX(-50%) translateY(8px)} }

/* ABOUT */
.about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: center; }
.about-text p { color: var(--text-light); line-height: 1.9; font-size: 1.05rem; }
.about-stats {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 16px;
  margin-top: 36px;
  padding-top: 36px;
  border-top: 1px solid var(--border);
  max-width: 340px;
}
.stat { text-align: center; }
.stat-num { display: block; font-family: 'Playfair Display', serif; font-size: 1.85rem; color: var(--navy); font-weight: 700; }
.stat-label { display: block; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.12em; color: var(--gold); margin-top: 6px; font-weight: 700; }
.about-visual { display: flex; flex-direction: column; gap: 20px; }
.visual-card {
  padding: 32px 28px;
  border-radius: 8px;
}
.visual-card.primary { background: var(--navy); color: var(--white); }
.visual-card.secondary { background: var(--gold-pale); border: 1px solid var(--gold); }
.visual-card h3 { font-size: 1.2rem; margin-bottom: 12px; }
.visual-card.primary h3 { color: var(--gold); }
.visual-card.secondary h3 { color: var(--navy); }
.visual-card p { font-size: 1rem; line-height: 1.85; opacity: 0.88; }
.vc-photo {
  width: 100%;
  height: 180px;
  object-fit: cover;
  border-radius: 6px;
  margin-bottom: 16px;
}

/* MINISTRY CARDS */
.ministry-card {
  display: block;
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: 32px 24px;
  transition: all 0.25s;
  cursor: pointer;
}
.ministry-card:hover { border-color: var(--gold); box-shadow: 0 8px 32px rgba(26,45,90,0.1); transform: translateY(-4px); }
.mc-photo {
  width: 100%;
  height: 150px;
  object-fit: cover;
  border-radius: 6px;
  margin-bottom: 16px;
}
.ministry-card h3 { font-size: 1.25rem; color: var(--navy); margin-bottom: 6px; }
.mc-tag { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.14em; color: var(--gold); font-weight: 700; margin-bottom: 14px; }
.ministry-card p { font-size: 1rem; color: var(--text-light); line-height: 1.85; }
.mc-link { display: inline-block; margin-top: 18px; font-size: 0.95rem; font-weight: 700; color: var(--navy); }
.ministry-card:hover .mc-link { color: var(--gold); }

/* NAVY SECTION */
.navy-section {
  background: linear-gradient(135deg, var(--navy-dark), var(--navy-mid));
  padding: 80px 0;
}

/* BURDEN */
.burden-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: start; }
.burden-list { display: flex; flex-direction: column; gap: 14px; }
.burden-item {
  display: flex;
  align-items: flex-start;
  gap: 16px;
  padding: 18px 22px;
  background: var(--section-bg);
  border-radius: 6px;
  font-size: 1.02rem;
  line-height: 1.65;
}
.burden-check {
  background: var(--gold);
  width: 12px;
  height: 12px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  margin-top: 8px;
}

/* CTA */
.cta-section {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 32px;
  flex-wrap: wrap;
}
.cta-buttons { display: flex; gap: 12px; flex-wrap: wrap; }

@media (max-width: 860px) {
  .about-grid, .burden-grid { grid-template-columns: 1fr; }
  .about-stats { grid-template-columns: repeat(2,1fr); }
  .cta-section { flex-direction: column; text-align: center; }
  .hero-btns { flex-wrap: wrap; }
}
</style>
