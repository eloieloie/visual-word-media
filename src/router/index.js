import { createRouter, createWebHashHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const routes = [
  { path: '/', component: HomeView },
  { path: '/about', component: () => import('../views/AboutView.vue') },
  { path: '/ministries', component: () => import('../views/MinistriesView.vue') },
  { path: '/prayer', component: () => import('../views/PrayerView.vue') },
  { path: '/volunteer', component: () => import('../views/VolunteerView.vue') },
  { path: '/testimonies', component: () => import('../views/TestimoniesView.vue') },
  { path: '/events', component: () => import('../views/EventsView.vue') },
  { path: '/resources', component: () => import('../views/ResourcesView.vue') },
  { path: '/contact', component: () => import('../views/ContactView.vue') },
]

export default createRouter({
  history: createWebHashHistory(),
  routes,
  scrollBehavior() { return { top: 0 } }
})
