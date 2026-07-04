import { createRouter, createWebHashHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const routes = [
  { path: '/',            component: HomeView },
  { path: '/about',       component: () => import('../views/AboutView.vue') },
  { path: '/ministries',  component: () => import('../views/MinistriesView.vue') },
  { path: '/prayer',      component: () => import('../views/PrayerView.vue') },
  { path: '/volunteer',   component: () => import('../views/VolunteerView.vue') },
  { path: '/verify-email', component: () => import('../views/VerifyEmailView.vue') },
  { path: '/testimonies', component: () => import('../views/TestimoniesView.vue') },
  { path: '/resources',   component: () => import('../views/ResourcesView.vue'), meta: { requiresAuth: true } },
  { path: '/teachings',   component: () => import('../views/TeachingsView.vue'), meta: { requiresAuth: true } },
  { path: '/partners',    component: () => import('../views/PartnersView.vue') },
  { path: '/contact',     component: () => import('../views/ContactView.vue') },
  { path: '/login',       component: () => import('../views/LoginView.vue'),    meta: { guestOnly: true } },
  { path: '/register',    component: () => import('../views/RegisterView.vue'), meta: { guestOnly: true } },
  { path: '/forgot-password', component: () => import('../views/ForgotPasswordView.vue'), meta: { guestOnly: true } },
  { path: '/reset-password',  component: () => import('../views/ResetPasswordView.vue'),  meta: { guestOnly: true } },
  { path: '/set-password',    component: () => import('../views/SetPasswordView.vue'),    meta: { requiresAuth: true } },
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
  const token    = localStorage.getItem('vwm_token')
  const userData = JSON.parse(localStorage.getItem('vwm_user') || 'null')

  if (to.meta.requiresAuth && !token) {
    return { path: '/login', query: { redirect: to.fullPath } }
  }

  if (to.meta.guestOnly && token) {
    return { path: '/' }
  }

  // If force_password_reset is set, redirect to /set-password (except when already there)
  if (token && userData?.force_password_reset && to.path !== '/set-password' && to.path !== '/login') {
    return { path: '/set-password' }
  }
})

export default router
