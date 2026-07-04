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

<style>
html, body, #app {
  overflow-x: hidden;
  width: 100%;
}
</style>
