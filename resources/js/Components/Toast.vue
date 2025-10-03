<script setup>
    import { onMounted, ref, watch } from 'vue'

    const props = defineProps({ message: String })

    const show = ref(false)

    onMounted(() => { if (props.message) { show.value = true; setTimeout(()=> show.value=false, 3000) } })

    watch(() => props.message, (m) => { if (m) { show.value = true; setTimeout(()=> show.value=false, 3000) } })    
</script>

<template>
  <transition name="fade">
    <div v-if="show && message"
      class="fixed bottom-6 right-6 z-50 rounded-lg bg-emerald-600 text-white px-4 py-3 shadow-lg">
      {{ message }}
    </div>
  </transition>
</template>

<style scoped>
.fade-enter-active,.fade-leave-active{ transition: opacity .2s }
.fade-enter-from,.fade-leave-to{ opacity:0 }
</style>
