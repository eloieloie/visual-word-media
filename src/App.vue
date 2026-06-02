<template>
  <NavBar />
  <main>
    <RouterView />
  </main>
  <FooterBar />
</template>

<script setup>
import NavBar from './components/NavBar.vue'
import FooterBar from './components/FooterBar.vue'
</script>
