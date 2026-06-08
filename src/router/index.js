import { createRouter, createWebHashHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const routes = [
  { path: '/',            component: HomeView },
  { path: '/about',       component: () => import('../views/AboutView.vue') },
  { path: '/ministries',  component: () => import('../views/MinistriesView.vue') },
  { path: '/prayer',      component: () => import('../views/PrayerView.vue') },
  { path: '/volunteer',   component: () => import('../views/VolunteerView.vue') },
  { path: '/testimonies', component: () => import('../views/TestimoniesView.vue') },
  { path: '/resources',   component: () => import('../views/ResourcesView.vue') },
  { path: '/contact',     component: () => import('../views/ContactView.vue') },
  { path: '/login',       component: () => import('../views/LoginView.vue'),    meta: { guestOnly: true } },
  { path: '/register',    component: () => import('../views/RegisterView.vue'), meta: { guestOnly: true } },
  {
    path: '/events',
    component: () => import('../views/EventsView.vue'),
    meta: { requiresAuth: true },
  },
]

const router = createRouter({
  history: createWebHashHistory(),
  routes,
  scrollBehavior() { return { top: 0 } },
})

router.beforeEach((to) => {
  const token = localStorage.getItem('vwm_token')

  if (to.meta.requiresAuth && !token) {
    return { path: '/login', query: { redirect: to.fullPath } }
  }

  if (to.meta.guestOnly && token) {
    return { path: '/' }
  }
})

export default router
