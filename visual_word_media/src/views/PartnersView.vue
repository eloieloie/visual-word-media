<template>
  <div class="page-hero">
    <div class="container">
      <p class="hero-label">Kingdom Collaboration</p>
      <h1>Our Partners</h1>
      <p>United in purpose, bound by the Great Commission — we are grateful for the ministries and organisations walking alongside Visual Word Media in Kingdom work.</p>
    </div>
  </div>

  <!-- INTRO -->
  <section class="section-sm" style="background:var(--section-bg)">
    <div class="container" style="max-width:820px; text-align:center">
      <p class="section-label">Together We Advance</p>
      <h2 class="section-title" style="text-align:center">Ministries Connected to Visual Word Media</h2>
      <div class="divider divider-center"></div>
      <p class="section-subtitle" style="margin:0 auto">
        Over the decades, God has knit us together with faithful organisations across India and beyond. These partnerships amplify our reach, deepen our discipleship work, and extend the Gospel to unreached communities.
      </p>
    </div>
  </section>

  <!-- PARTNER LIST -->
  <section class="section">
    <div class="container">
      <div class="partners-list">
        <div class="partner-card" v-for="partner in partners" :key="partner.name">
          <div class="partner-icon">
            <span class="partner-initials">{{ partner.initials }}</span>
          </div>
          <div class="partner-info">
            <h3>{{ partner.name }}</h3>
            <span class="partner-category">{{ partner.category }}</span>
            <p>{{ partner.description }}</p>
            <div class="partner-focus">
              <span class="focus-tag" v-for="tag in partner.focus" :key="tag">{{ tag }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- BECOME A PARTNER CTA -->
  <section class="section-sm navy-bg">
    <div class="container" style="text-align:center">
      <p class="section-label" style="color:var(--gold)">Partner With Us</p>
      <h2 style="color:var(--white); margin-bottom:18px">Interested in Collaborating?</h2>
      <p style="color:rgba(255,255,255,0.75); max-width:580px; margin:0 auto 32px">
        We welcome ministries, churches, and organisations who share our passion for using media to proclaim the Gospel. Reach out to explore how we can serve the Kingdom together.
      </p>
      <RouterLink to="/contact" class="btn-primary-gold">Get in Touch</RouterLink>
    </div>
  </section>
</template>

<script setup>
const partners = [
  {
    name: 'Youth for Christ India',
    initials: 'YFC',
    category: 'Youth Ministry',
    description: 'Reaching young people across India with the love of Christ through relational ministry, camps, and outreach programmes. A long-standing partner in discipling the next generation.',
    focus: ['Youth Outreach', 'Evangelism', 'Discipleship'],
  },
  {
    name: 'India Gospel League',
    initials: 'IGL',
    category: 'Church Planting',
    description: 'A pioneering movement focused on planting indigenous churches and evangelising unreached villages throughout India. Partner in the 60x360 rural outreach initiative.',
    focus: ['Church Planting', 'Village Missions', 'Leadership Training'],
  },
  {
    name: 'Every Home for Christ India',
    initials: 'EHC',
    category: 'Literature & Evangelism',
    description: 'Distributing Gospel literature and following up with new believers in every home across India. Collaborating with VWM on printed and digital media evangelism efforts.',
    focus: ['Literature Distribution', 'Follow-up', 'Digital Evangelism'],
  },
  {
    name: 'Friends Missionary Prayer Band',
    initials: 'FMPB',
    category: 'Missions',
    description: 'One of India\'s largest indigenous missionary organisations, mobilising prayer and sending workers to unreached people groups. Partners in intercessory prayer networks.',
    focus: ['Unreached Peoples', 'Prayer Mobilisation', 'Missionary Sending'],
  },
  {
    name: 'Compassion India',
    initials: 'CI',
    category: 'Child Development',
    description: 'Releasing children from poverty in Jesus\' name through holistic child development programmes. Partner in community outreach and care ministry for vulnerable youth.',
    focus: ['Child Sponsorship', 'Community Development', 'Education'],
  },
  {
    name: 'Open Doors India',
    initials: 'OD',
    category: 'Persecuted Church',
    description: 'Supporting persecuted Christians across Asia and the world. Partnering with VWM to create awareness resources and provide spiritual encouragement to believers under pressure.',
    focus: ['Persecution Awareness', 'Church Strengthening', 'Prayer'],
  },
  {
    name: 'Hyderabad Bible College',
    initials: 'HBC',
    category: 'Theological Education',
    description: 'Equipping the next generation of Christian leaders through rigorous Biblical and theological training. A key partner for training volunteers and media ministry interns.',
    focus: ['Theological Training', 'Leadership Development', 'Internships'],
  },
  {
    name: 'Focus on the Family India',
    initials: 'FOF',
    category: 'Family Ministry',
    description: 'Strengthening families across India through Biblical resources, counselling support, and media. Partnering with VWM\'s IMPACT ministry on media literacy and family discipleship.',
    focus: ['Family Discipleship', 'Counselling', 'Media Literacy'],
  },
  {
    name: 'Emmanuel Hospital Association',
    initials: 'EHA',
    category: 'Healthcare & Missions',
    description: 'A network of Christian hospitals serving communities in remote and tribal areas of India. Partner in compassionate outreach and community health missions.',
    focus: ['Healthcare', 'Community Outreach', 'Tribal Ministry'],
  },
]
</script>

<style scoped>
.navy-bg { background: var(--navy-dark); }

.partners-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
  max-width: 860px;
  margin: 0 auto;
}

.partner-card {
  display: flex;
  gap: 20px;
  padding: 32px;
  border: 1px solid var(--border);
  border-radius: 8px;
  background: var(--white);
  transition: box-shadow 0.2s, transform 0.2s;
}

.partner-card:hover {
  box-shadow: 0 8px 32px rgba(26, 45, 90, 0.1);
  transform: translateY(-3px);
}

.partner-icon {
  flex-shrink: 0;
  width: 64px;
  height: 64px;
  background: var(--navy);
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.partner-initials {
  color: var(--gold);
  font-family: 'Playfair Display', Georgia, serif;
  font-size: 1rem;
  font-weight: 700;
  letter-spacing: 0.05em;
}

.partner-info { flex: 1; }

.partner-info h3 {
  font-size: 1.1rem;
  color: var(--navy);
  margin-bottom: 4px;
}

.partner-category {
  display: inline-block;
  font-size: 0.75rem;
  font-weight: 700;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  color: var(--gold);
  margin-bottom: 10px;
}

.partner-info p {
  font-size: 0.9rem;
  color: var(--text-light);
  line-height: 1.75;
  margin-bottom: 14px;
}

.partner-focus {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
}

.focus-tag {
  font-size: 0.72rem;
  font-weight: 600;
  letter-spacing: 0.05em;
  padding: 3px 10px;
  border-radius: 20px;
  background: var(--gold-pale);
  color: var(--navy);
  border: 1px solid var(--border);
}

.btn-primary-gold {
  display: inline-block;
  padding: 14px 36px;
  background: var(--gold);
  color: var(--navy-dark);
  font-weight: 700;
  font-size: 0.95rem;
  letter-spacing: 0.05em;
  border-radius: 4px;
  transition: background 0.2s;
}

.btn-primary-gold:hover {
  background: var(--gold-light);
}

@media (max-width: 600px) {
  .partner-card { flex-direction: column; }
}
</style>
