<template>
  <footer class="footer">
    <div class="footer-top">
      <div class="container footer-grid">
        <div class="footer-brand">
          <div class="footer-logo">
            <span class="logo-cross">✝</span>
            <div>
              <div class="logo-title">Visual Word Media</div>
              <div class="logo-sub">Mission</div>
            </div>
          </div>
          <p class="footer-desc">
            Visual Word Media is a Christ-centered media ministry serving today's generation through discipleship, creative engagement, Biblical worldview training, and compassionate outreach since 1997.
          </p>
          <div class="footer-verse">
            <span class="verse-text">"Those who know their God shall be strong and carry out great exploits."</span>
            <span class="verse-ref">— Daniel 11:32</span>
          </div>
        </div>

        <div class="footer-col">
          <h4>Ministry</h4>
          <RouterLink to="/about">About Us</RouterLink>
          <RouterLink to="/ministries">Our Ministries</RouterLink>
          <RouterLink to="/events">Events</RouterLink>
          <RouterLink to="/testimonies">Testimonies</RouterLink>
        </div>

        <div class="footer-col">
          <h4>Resources</h4>
          <RouterLink to="/resources">Articles & Media</RouterLink>
          <RouterLink to="/resources">Bible Studies</RouterLink>
          <RouterLink to="/resources">Video Teachings</RouterLink>
          <RouterLink to="/resources">Youth Resources</RouterLink>
        </div>

        <div class="footer-col">
          <h4>Connect</h4>
          <RouterLink to="/prayer">Prayer Partnership</RouterLink>
          <RouterLink to="/volunteer">Volunteer</RouterLink>
          <RouterLink to="/contact">Contact Us</RouterLink>
          <RouterLink to="/volunteer" class="footer-cta">Join the Mission →</RouterLink>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <div class="container footer-bottom-inner">
        <span>© {{ year }} Visual Word Media Mission. All rights reserved.</span>
        <span>Serving since 1997 · Hyderabad, India</span>
      </div>
    </div>
  </footer>
</template>

<script setup>
const year = new Date().getFullYear()
</script>

<style scoped>
.footer { background: var(--navy-dark); color: rgba(255,255,255,0.8); }
.footer-top { padding: 72px 0 48px; }
.footer-grid {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr;
  gap: 48px;
}
.footer-logo {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 20px;
}
.logo-cross { font-size: 2rem; color: var(--gold); }
.logo-title {
  font-family: 'Playfair Display', serif;
  font-size: 1.05rem;
  color: var(--white);
  font-weight: 700;
}
.logo-sub { font-size: 0.68rem; letter-spacing: 0.18em; text-transform: uppercase; color: var(--gold); }
.footer-desc {
  font-size: 0.95rem;
  line-height: 1.85;
  margin-bottom: 24px;
  opacity: 0.78;
}
.footer-verse {
  border-left: 3px solid var(--gold);
  padding-left: 16px;
  font-style: italic;
  font-size: 0.95rem;
  line-height: 1.75;
}
.verse-text { display: block; opacity: 0.85; }
.verse-ref { display: block; color: var(--gold); margin-top: 6px; font-style: normal; font-size: 0.85rem; letter-spacing: 0.07em; }

.footer-col h4 {
  font-family: 'Playfair Display', serif;
  color: var(--white);
  font-size: 1.1rem;
  margin-bottom: 18px;
  padding-bottom: 12px;
  border-bottom: 1px solid rgba(201,162,39,0.3);
}
.footer-col a {
  display: block;
  font-size: 0.95rem;
  color: rgba(255,255,255,0.72);
  padding: 7px 0;
  transition: color 0.2s;
}
.footer-col a:hover { color: var(--gold); }
.footer-cta { color: var(--gold) !important; font-weight: 700; margin-top: 10px; }

.footer-bottom {
  border-top: 1px solid rgba(255,255,255,0.1);
  padding: 24px 0;
}
.footer-bottom-inner {
  display: flex;
  justify-content: space-between;
  font-size: 0.88rem;
  opacity: 0.6;
}

@media (max-width: 900px) {
  .footer-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 560px) {
  .footer-grid { grid-template-columns: 1fr; gap: 32px; }
  .footer-bottom-inner { flex-direction: column; gap: 6px; }
}
</style>
