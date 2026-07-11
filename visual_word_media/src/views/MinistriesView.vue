<template>
  <div class="page-hero">
    <div class="container">
      <p class="hero-label">Kingdom Work</p>
      <h1>Our Ministry Wings</h1>
      <p>Six focused areas of ministry reaching youth, creatives, families, villages, and digital audiences for the glory of God.</p>
    </div>
  </div>

  <!-- IMPACT -->
  <section class="section" id="impact">
    <div class="container">
      <div class="ministry-section">
        <div class="ministry-header">
          <span class="min-number">01</span>
          <div>
            <p class="section-label">Ministry Wing</p>
            <h2 class="section-title">IMPACT</h2>
            <p class="min-subtitle">Facing the Influence of Media</p>
            <div class="divider"></div>
            <p class="min-overview">IMPACT focuses on helping individuals understand, discern, and respond Biblically to the influence of media in modern society.</p>
          </div>
        </div>
        <div class="ministry-body">
          <div class="min-col">
            <h4>Ministry Areas</h4>
            <ul class="min-list">
              <li>Media awareness seminars</li>
              <li>Teaching sessions</li>
              <li>Literature distribution</li>
              <li>Counseling for media addictions</li>
              <li>Youth interaction programs</li>
              <li>Prayer support for media professionals</li>
              <li>Biblical worldview training</li>
            </ul>
          </div>
          <div class="min-col">
            <h4>Key Focus Areas</h4>
            <ul class="min-list focus">
              <li>Social media influence</li>
              <li>Digital addiction</li>
              <li>Pornography addiction counseling</li>
              <li>Video game addiction awareness</li>
              <li>Responsible media engagement</li>
              <li>Media discipleship</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 60x360 -->
  <section class="section navy-bg" id="sixty">
    <div class="container">
      <div class="min-badge">02</div>
      <div class="sixty-header">
        <div>
          <p class="section-label" style="color:var(--gold)">Ministry Wing</p>
          <h2 style="color:var(--white); font-size:clamp(2rem,4vw,3rem)">60x360</h2>
          <p class="min-subtitle" style="color:var(--gold-light)">Youth Discipleship Movement</p>
          <p style="color:rgba(255,255,255,0.75); margin-top:12px; font-size:1.05rem; font-style:italic">Transforming Villages. Discipling Youth. Advancing God's Kingdom.</p>
        </div>
        <div class="sixty-stats">
          <div class="sixty-stat"><span>60 km</span><small>Mission radius around Hyderabad</small></div>
          <div class="sixty-stat"><span>360°</span><small>Every direction, every community</small></div>
        </div>
      </div>

      <div class="sixty-body">
        <p style="color:rgba(255,255,255,0.8); line-height:1.85; margin-bottom:24px">
          The 60x360 Youth Discipleship Movement was born out of prayer, burden, and divine revelation. As the leadership team spent time seeking the Lord, God revealed a vast spiritual need surrounding Hyderabad — thousands of communities remaining spiritually underserved, largely untouched by the Gospel.
        </p>
        <div class="scripture-block" style="margin-bottom:32px">
          "The harvest truly is plentiful, but the laborers are few."
          <span class="scripture-ref">— Matthew 9:37</span>
        </div>
        <div class="sixty-grid">
          <div class="sixty-card" v-for="camp in camps" :key="camp.title">
            <img class="sc-photo" :src="camp.image" :alt="camp.title" />
            <h4>{{ camp.title }}</h4>
            <p>{{ camp.desc }}</p>
          </div>
        </div>
        <div style="margin-top:40px">
          <h4 style="color:var(--gold); margin-bottom:16px; font-family:'Playfair Display',serif">Ministry Areas</h4>
          <div class="tag-list">
            <span v-for="t in sixtyAreas" :key="t">{{ t }}</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- PRODUCTION -->
  <section class="section" id="production">
    <div class="container">
      <div class="ministry-section">
        <div class="ministry-header">
          <span class="min-number">03</span>
          <div>
            <p class="section-label">Ministry Wing</p>
            <h2 class="section-title">PRODUCTION</h2>
            <p class="min-subtitle">Media for Evangelism and Church Growth</p>
            <div class="divider"></div>
            <p class="min-overview">The production ministry creates Biblical and educational visual resources to serve churches, ministries, children, and communities.</p>
          </div>
        </div>
        <div class="ministry-body">
          <div class="min-col">
            <h4>Ministry Areas</h4>
            <ul class="min-list">
              <li>Christian visual resources</li>
              <li>Children's Bible picture materials</li>
              <li>Documentary productions</li>
              <li>Audio-visual ministry tools</li>
              <li>Educational media</li>
              <li>Live media outreach</li>
              <li>Literature development</li>
            </ul>
          </div>
          <div class="min-col">
            <h4>Current Initiatives</h4>
            <ul class="min-list focus">
              <li>God's Word for Children in Pictures</li>
              <li>Missionary Stories for Children</li>
              <li>Audio-visual teaching tools</li>
              <li>Media resources for churches</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- OCULUS -->
  <section class="section gold-bg" id="oculus">
    <div class="container">
      <div class="min-badge dark">04</div>
      <p class="section-label">Ministry Wing</p>
      <h2 class="section-title">OCULUS</h2>
      <p class="min-subtitle" style="color:var(--navy)">Creative Arts Ministry</p>
      <div class="divider"></div>
      <p style="color:var(--text-light); max-width:640px; margin-bottom:40px">Oculus exists to nurture a generation of creative artists who will influence culture through a Biblical worldview.</p>
      <div class="grid-4">
        <div class="oculus-card" v-for="a in oculusAreas" :key="a.label">
          <img class="oc-photo" :src="a.image" :alt="a.label" />
          <span>{{ a.label }}</span>
        </div>
      </div>
      <div class="oculus-focus">
        <p>Encouraging Christian creativity that reflects <strong>truth</strong>, <strong>beauty</strong>, <strong>hope</strong>, and <strong>redemption</strong>.</p>
      </div>
    </div>
  </section>

  <!-- NETWORK105 -->
  <section class="section" id="network105">
    <div class="container">
      <div class="ministry-section">
        <div class="ministry-header">
          <span class="min-number">05</span>
          <div>
            <p class="section-label">Ministry Wing</p>
            <h2 class="section-title">NETWORK105</h2>
            <p class="min-subtitle">Digital Media Outreach</p>
            <div class="divider"></div>
            <p class="min-overview">A network of digital content initiatives designed to engage different age groups and audiences through meaningful and Christ-centered media.</p>
          </div>
        </div>
        <div class="network-channels">
          <h4>Channels & Streams</h4>
          <div class="channel-grid">
            <div class="channel-card" v-for="ch in channels" :key="ch">{{ ch }}</div>
          </div>
          <p style="color:var(--text-light); margin-top:24px; font-style:italic">Creating spiritually enriching and socially meaningful digital content for today's generation.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- NEW LIFE -->
  <section class="section" style="background:var(--navy)" id="newlife">
    <div class="container">
      <div class="min-badge">06</div>
      <p class="section-label" style="color:var(--gold)">Ministry Wing</p>
      <h2 style="color:var(--white); font-size:clamp(1.8rem,3vw,2.5rem)">NEW LIFE</h2>
      <p class="min-subtitle" style="color:var(--gold-light)">Suicide Prevention & Counseling Ministry</p>
      <div class="divider"></div>
      <p style="color:rgba(255,255,255,0.75); max-width:640px; margin-bottom:40px">New Life exists to bring hope, healing, and support to individuals struggling emotionally, mentally, and spiritually.</p>
      <div class="grid-3">
        <div class="newlife-card" v-for="a in newlifeAreas" :key="a">
          <span>{{ a }}</span>
        </div>
      </div>
      <div class="newlife-mission">
        <p>"To communicate hope, value, purpose, and life through Christ-centered care."</p>
      </div>
    </div>
  </section>

  <!-- BOTTOM CTA -->
  <section class="section-sm" style="background:var(--gold-pale); border-top:3px solid var(--gold); text-align:center">
    <div class="container">
      <h2 class="section-title">Partner With a Ministry Wing</h2>
      <p style="color:var(--text-light); margin-bottom:28px">Every ministry area needs volunteers, prayer, and resources. Find where God is calling you.</p>
      <div style="display:flex; gap:14px; justify-content:center; flex-wrap:wrap">
        <RouterLink to="/volunteer" class="btn btn-primary">Join the Mission</RouterLink>
        <RouterLink to="/prayer" class="btn btn-navy">Become a Prayer Partner</RouterLink>
        <RouterLink to="/volunteer" class="btn btn-navy">How do you want to involve</RouterLink>
        <RouterLink to="/contact" class="btn btn-outline" style="color:var(--navy);border-color:var(--navy)">Contact Us</RouterLink>
      </div>
    </div>
  </section>
</template>

<script setup>
const camps = [
  { image: '/images/ministries/evangelism-camps.jpg', title: 'Evangelism Camps', desc: 'Mobilizing young believers to share their faith, engage local communities, and serve as ambassadors for Christ.' },
  { image: '/images/ministries/youth-discipleship-camps.jpg', title: 'Youth Discipleship Camps', desc: 'Helping youth develop a personal relationship with Jesus, build strong spiritual disciplines, and grow in character.' },
  { image: '/images/ministries/leadership-development.jpg', title: 'Leadership Development Camps', desc: 'Identifying, mentoring, and equipping emerging leaders who can serve in churches, ministries, and mission fields.' },
  { image: '/images/ministries/village-ministry.jpg', title: 'Village-Focused Ministry', desc: 'Ensuring every village has access to the Gospel, disciple-making believers, and Biblical teaching resources.' },
]

const sixtyAreas = ['Gospel Camps', 'Discipleship Camps', 'Bible Studies', 'Leadership Mentoring', 'Village Youth Ministry', 'Mobile Educational Outreach', 'Intense Discipleship Programs']

const oculusAreas = [
  { image: '/images/ministries/artist-fellowships.jpg', label: 'Artist Fellowships' },
  { image: '/images/ministries/creative-workshops.jpg', label: 'Creative Workshops' },
  { image: '/images/ministries/art-exhibitions.jpg', label: 'Art Exhibitions' },
  { image: '/images/gemini/Competitions.png', label: 'Competitions' },
  { image: '/images/gemini/Theology_Art.png', label: 'Theology & Art' },
  { image: '/images/gemini/Bible_Studies_Artists.png', label: 'Bible Studies for Artists' },
  { image: '/images/ministries/creative-mentorship.jpg', label: 'Creative Mentorship' },
  { image: '/images/ministries/culture-faith.jpg', label: 'Culture & Faith' },
]

const channels = ['Pustakam', 'Mainline Glorious', 'Pebbles', 'Forever Young', 'PRO31', 'Word of Life', 'Education', 'Health']

const newlifeAreas = [
  'Awareness Programs', 'Counseling Support', 'Youth Interaction',
  'Tele-Help Initiatives', 'Prayer Support', 'Emotional Care Ministry',
]
</script>

<style scoped>
.ministry-section { display: flex; flex-direction: column; gap: 40px; }
.ministry-header {
  display: flex;
  align-items: flex-start;
  gap: 32px;
}
.min-number {
  font-family: 'Playfair Display', serif;
  font-size: 5.5rem;
  color: var(--gold);
  opacity: 0.22;
  line-height: 1;
  flex-shrink: 0;
  font-weight: 700;
}
.min-subtitle { font-size: 1.2rem; color: var(--gold); font-weight: 600; margin-top: 6px; }
.min-overview { color: var(--text-light); line-height: 1.9; max-width: 620px; font-size: 1.05rem; }
.ministry-body { display: grid; grid-template-columns: 1fr 1fr; gap: 36px; }
.min-col h4 { font-size: 0.88rem; text-transform: uppercase; letter-spacing: 0.12em; color: var(--navy); margin-bottom: 18px; padding-bottom: 10px; border-bottom: 2px solid var(--gold); font-weight: 800; }
.min-list { list-style: none; display: flex; flex-direction: column; gap: 12px; }
.min-list li {
  padding: 12px 16px 12px 42px;
  background: var(--section-bg);
  border-radius: 4px;
  font-size: 1rem;
  position: relative;
  line-height: 1.6;
}
.min-list li::before {
  content: '';
  position: absolute;
  left: 14px;
  top: 18px;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: var(--gold);
}
.min-list.focus li::before {
  width: 12px;
  height: 2px;
  border-radius: 2px;
  top: 22px;
}

/* 60x360 */
.navy-bg { background: linear-gradient(135deg, var(--navy-dark), var(--navy-mid)); }
.sixty-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 40px; margin-bottom: 40px; }
.sixty-stats { display: flex; gap: 24px; }
.sixty-stat { text-align: center; background: rgba(255,255,255,0.08); border: 1px solid rgba(201,162,39,0.3); border-radius: 8px; padding: 20px 24px; }
.sixty-stat span { display: block; font-family: 'Playfair Display', serif; font-size: 2rem; color: var(--gold); font-weight: 700; }
.sixty-stat small { color: rgba(255,255,255,0.65); font-size: 0.85rem; margin-top: 6px; display: block; }
.sixty-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; }
.sixty-card {
  background: rgba(255,255,255,0.07);
  border: 1px solid rgba(201,162,39,0.2);
  border-radius: 8px;
  padding: 28px 24px;
}
.sc-photo {
  width: 100%;
  height: 150px;
  object-fit: cover;
  border-radius: 6px;
  margin-bottom: 14px;
}
.sixty-card h4 { color: var(--gold); font-family: 'Playfair Display', serif; margin-bottom: 10px; font-size: 1.15rem; }
.sixty-card p { color: rgba(255,255,255,0.78); font-size: 1rem; line-height: 1.8; }
.tag-list { display: flex; flex-wrap: wrap; gap: 10px; }
.tag-list span {
  background: rgba(201,162,39,0.15);
  border: 1px solid rgba(201,162,39,0.35);
  color: var(--gold-light);
  padding: 8px 16px;
  border-radius: 20px;
  font-size: 0.9rem;
}

/* OCULUS */
.gold-bg { background: var(--cream); }
.oculus-card {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: 24px 16px;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--navy);
  transition: all 0.2s;
}
.oculus-card:hover { border-color: var(--gold); background: var(--gold-pale); }
.oc-photo {
  width: 100%;
  height: 120px;
  object-fit: cover;
  border-radius: 6px;
}
.oculus-focus {
  margin-top: 36px;
  background: var(--navy);
  color: var(--white);
  padding: 28px 36px;
  border-radius: 8px;
  font-family: 'Playfair Display', serif;
  font-style: italic;
  font-size: 1.25rem;
  text-align: center;
  line-height: 1.7;
}
.oculus-focus strong { color: var(--gold); font-style: normal; }

/* NETWORK105 */
.network-channels { margin-top: 8px; }
.network-channels h4 { margin-bottom: 20px; font-family: 'Playfair Display', serif; color: var(--navy); font-size: 1.1rem; }
.channel-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
.channel-card {
  background: var(--navy);
  color: var(--white);
  min-height: 48px;
  padding: 12px 14px;
  border-radius: 4px;
  font-size: 0.95rem;
  font-weight: 600;
  letter-spacing: 0.05em;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
}

/* NEW LIFE */
.newlife-card {
  background: rgba(255,255,255,0.08);
  border: 1px solid rgba(201,162,39,0.2);
  border-radius: 8px;
  padding: 20px;
  color: rgba(255,255,255,0.85);
  font-size: 0.92rem;
  text-align: center;
}
.newlife-mission {
  margin-top: 40px;
  border-left: 4px solid var(--gold);
  padding: 20px 24px;
  font-family: 'Playfair Display', serif;
  font-style: italic;
  font-size: 1.15rem;
  color: rgba(255,255,255,0.85);
}

.min-badge {
  font-family: 'Playfair Display', serif;
  font-size: 3rem;
  color: var(--gold);
  opacity: 0.4;
  font-weight: 700;
  margin-bottom: 8px;
}
.min-badge.dark { color: var(--navy); }

@media (max-width: 760px) {
  .ministry-body, .sixty-header, .sixty-grid { grid-template-columns: 1fr; }
  .sixty-header { flex-direction: column; }
  .sixty-stats { flex-wrap: wrap; }
  .channel-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>
