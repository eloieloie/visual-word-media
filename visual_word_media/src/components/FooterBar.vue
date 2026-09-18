<template>
  <footer class="footer">
    <div class="footer-top">
      <div class="container footer-grid">
        <div class="footer-brand">
          <div class="footer-logo">
            <img class="logo-mark" :src="img('/images/logo.png')" alt="Visual Word Media logo" />
            <div>
              <div class="logo-title">Visual Word Media</div>
              <div class="logo-sub">Mission</div>
            </div>
          </div>
          <p class="footer-desc">
            Visual Word Media is a Christ-centered media ministry serving today's generation through discipleship, creative engagement, Biblical worldview training, and compassionate outreach since 2000.
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
          <RouterLink to="/partners">Our Partners</RouterLink>
          <RouterLink to="/events">Events</RouterLink>
          <RouterLink to="/testimonies">Testimonies</RouterLink>
          <RouterLink to="/prayer">Prayer</RouterLink>
        </div>

        <div class="footer-col">
          <h4>Teachings</h4>
          <RouterLink to="/teachings">Video Teachings</RouterLink>
          <RouterLink to="/teachings">Audio Teachings</RouterLink>
          <RouterLink to="/resources">Articles & Media</RouterLink>
          <RouterLink to="/resources">Bible Studies</RouterLink>
        </div>

        <div class="footer-col">
          <h4>Connect</h4>
          <RouterLink to="/contact">Contact Us</RouterLink>
          <RouterLink to="/volunteer">Volunteer</RouterLink>
          <RouterLink to="/volunteer" class="footer-cta">Join the Mission</RouterLink>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <div class="container footer-bottom-inner">
        <span>© {{ year }} Visual Word Media Mission. All rights reserved.</span>
        <span>Serving since 2000 · Hyderabad, India</span>
        <div class="footer-social">
          <a href="https://www.instagram.com/visualwordmediamission/?hl=en" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zm0 10.162a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/></svg>
          </a>
          <a href="https://www.youtube.com/@Pustakam105" target="_blank" rel="noopener noreferrer" aria-label="YouTube">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.376.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.376-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
          </a>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { img } from '../composables/useBaseUrl.js'
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
.logo-mark {
  width: 52px;
  height: 52px;
  flex-shrink: 0;
  border-radius: 8px;
  object-fit: cover;
}
.logo-title {
  font-family: var(--font-display);
  font-size: 1.05rem;
  color: var(--white);
  font-weight: 700;
}
.logo-sub { font-family: var(--font-mono); font-size: 0.65rem; letter-spacing: 0.18em; text-transform: uppercase; color: var(--gold); }
.footer-desc {
  font-size: 0.95rem;
  line-height: 1.85;
  margin-bottom: 24px;
  opacity: 0.78;
}
.footer-verse {
  border-left: 3px solid var(--gold);
  padding-left: 16px;
  font-family: var(--font-display);
  font-style: italic;
  font-size: 0.95rem;
  line-height: 1.75;
}
.verse-text { display: block; opacity: 0.85; }
.verse-ref { display: block; color: var(--gold); margin-top: 6px; font-family: var(--font-mono); font-style: normal; font-size: 0.78rem; letter-spacing: 0.05em; }

.footer-col h4 {
  font-family: var(--font-display);
  color: var(--white);
  font-size: 1.1rem;
  margin-bottom: 18px;
  padding-bottom: 12px;
  border-bottom: 1px solid rgba(242,162,58,0.3);
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
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  font-family: var(--font-mono);
  font-size: 0.78rem;
  letter-spacing: 0.02em;
  opacity: 0.6;
}
.footer-social {
  display: flex;
  gap: 10px;
}
.footer-social a {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 28px;
  height: 28px;
  border-radius: 50%;
  border: 1px solid rgba(255,255,255,0.3);
  color: rgba(255,255,255,0.8);
  transition: color 0.2s, border-color 0.2s, background-color 0.2s;
}
.footer-social a:hover {
  color: var(--navy-dark);
  background-color: var(--gold);
  border-color: var(--gold);
}

@media (max-width: 900px) {
  .footer-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 560px) {
  .footer-grid { grid-template-columns: 1fr; gap: 32px; }
  .footer-bottom-inner { flex-direction: column; gap: 10px; }
}
</style>
