<template>
  <header :class="['navbar', { scrolled: scrolled || solidRoutes.includes(route.path), open: menuOpen }]">
    <div class="container nav-inner">
      <RouterLink to="/" class="logo" @click="menuOpen = false">
        <span class="logo-cross">✝</span>
        <div class="logo-text">
          <span class="logo-title">Visual Word Media</span>
          <span class="logo-sub">Mission</span>
        </div>
      </RouterLink>

      <nav class="nav-links">
        <RouterLink to="/about">About</RouterLink>
        <RouterLink to="/ministries">Ministries</RouterLink>
        <RouterLink to="/events">Events</RouterLink>
        <RouterLink to="/teachings">Teachings</RouterLink>
        <RouterLink to="/resources">Resources</RouterLink>
        <RouterLink to="/contact">Contact</RouterLink>
      </nav>

      <div class="nav-auth">
        <template v-if="isLoggedIn">
          <span class="nav-user">{{ firstName }}</span>
          <button class="btn btn-outline nav-logout" @click="handleLogout">Logout</button>
        </template>
        <template v-else>
          <RouterLink to="/login" class="nav-login">Login</RouterLink>
          <RouterLink to="/volunteer" class="btn btn-primary nav-cta">Join the Mission</RouterLink>
        </template>
      </div>

      <button class="hamburger" @click="menuOpen = !menuOpen" aria-label="Toggle menu">
        <span></span><span></span><span></span>
      </button>
    </div>

    <div class="mobile-menu" v-if="menuOpen">
      <RouterLink to="/about" @click="menuOpen=false">About</RouterLink>
      <RouterLink to="/ministries" @click="menuOpen=false">Ministries</RouterLink>
      <RouterLink to="/events" @click="menuOpen=false">Events</RouterLink>
      <RouterLink to="/teachings" @click="menuOpen=false">Teachings</RouterLink>
      <RouterLink to="/resources" @click="menuOpen=false">Resources</RouterLink>
      <RouterLink to="/testimonies" @click="menuOpen=false">Testimonies</RouterLink>
      <RouterLink to="/prayer" @click="menuOpen=false">Prayer</RouterLink>
      <RouterLink to="/contact" @click="menuOpen=false">Contact</RouterLink>
      <template v-if="isLoggedIn">
        <button class="btn btn-outline mobile-logout" @click="handleLogout; menuOpen=false">Logout</button>
      </template>
      <template v-else>
        <RouterLink to="/login" class="btn btn-outline" @click="menuOpen=false">Login</RouterLink>
        <RouterLink to="/volunteer" class="btn btn-primary" @click="menuOpen=false">Join the Mission</RouterLink>
      </template>
    </div>
  </header>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuth } from '../composables/useAuth.js'

const scrolled    = ref(false)
const menuOpen    = ref(false)
const router      = useRouter()
const route       = useRoute()
const solidRoutes = ['/login', '/register']
const { user, isLoggedIn, logout } = useAuth()

const firstName = computed(() => user.value?.name?.split(' ')[0] ?? '')

function handleLogout() {
  logout()
  router.push('/')
}

function onScroll() { scrolled.value = window.scrollY > 40 }
onMounted(() => window.addEventListener('scroll', onScroll))
onUnmounted(() => window.removeEventListener('scroll', onScroll))
</script>

<style scoped>
.navbar {
  position: fixed;
  top: 0; left: 0; right: 0;
  z-index: 1000;
  background: transparent;
  transition: background 0.3s, box-shadow 0.3s;
}
.navbar.scrolled, .navbar.open {
  background: var(--navy-dark);
  box-shadow: 0 2px 24px rgba(0,0,0,0.25);
}
.nav-inner {
  display: flex;
  align-items: center;
  gap: 32px;
  height: 72px;
}
.logo {
  display: flex;
  align-items: center;
  gap: 12px;
  flex-shrink: 0;
}
.logo-cross {
  font-size: 1.8rem;
  color: var(--gold);
  line-height: 1;
}
.logo-text { display: flex; flex-direction: column; line-height: 1.2; }
.logo-title {
  font-family: 'Playfair Display', serif;
  font-size: 1.15rem;
  font-weight: 700;
  color: var(--white);
}
.logo-sub {
  font-size: 0.72rem;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: var(--gold);
}
.nav-links {
  display: flex;
  gap: 4px;
  align-items: center;
  margin-left: auto;
}
.nav-links a {
  font-size: 0.94rem;
  font-weight: 600;
  color: rgba(255,255,255,0.88);
  padding: 7px 12px;
  border-radius: 4px;
  transition: color 0.2s;
  letter-spacing: 0.02em;
}
.nav-links a:hover, .nav-links a.router-link-active { color: var(--gold); }
.nav-auth    { display: flex; align-items: center; gap: 10px; margin-left: 10px; flex-shrink: 0; }
.nav-cta     { padding: 11px 22px; font-size: 0.94rem; }
.nav-login   { font-size: 0.94rem; font-weight: 600; color: rgba(255,255,255,0.88); padding: 7px 12px; border-radius: 4px; transition: color 0.2s; }
.nav-login:hover { color: var(--gold); }
.nav-user    { font-size: 0.88rem; color: var(--gold); font-weight: 600; max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.nav-logout  { padding: 8px 16px; font-size: 0.88rem; border-color: rgba(255,255,255,0.4); color: rgba(255,255,255,0.88); }
.nav-logout:hover { border-color: var(--gold); color: var(--gold); }
.mobile-logout { margin-top: 8px; text-align: center; border-color: rgba(255,255,255,0.4); color: rgba(255,255,255,0.88); }
.hamburger {
  display: none;
  flex-direction: column;
  gap: 5px;
  background: none;
  border: none;
  cursor: pointer;
  padding: 4px;
  margin-left: auto;
}
.hamburger span {
  display: block;
  width: 24px; height: 2px;
  background: var(--white);
  border-radius: 2px;
}
.mobile-menu {
  display: flex;
  flex-direction: column;
  background: var(--navy-dark);
  padding: 16px 24px 24px;
  gap: 4px;
}
.mobile-menu a {
  color: rgba(255,255,255,0.88);
  padding: 14px 0;
  border-bottom: 1px solid rgba(255,255,255,0.08);
  font-size: 1.05rem;
}
.mobile-menu a.btn { margin-top: 16px; text-align: center; border-bottom: none; }

@media (max-width: 960px) {
  .nav-links { display: none; }
  .nav-auth  { display: none; }
  .hamburger { display: flex; }
}
</style>
