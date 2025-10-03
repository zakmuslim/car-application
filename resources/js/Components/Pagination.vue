<script setup>
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  source: { type: Object, required: true },
})

const pageLinks = computed(() => {
  const raw = props.source.meta?.links ?? props.source.links ?? []

  console.log(raw);

  // strip arrows and prev/next text
  return raw.filter(l => {
    if (!l.label) return false
    const lbl = l.label.toLowerCase()
    return !lbl.includes('«') && !lbl.includes('»') &&
           lbl !== 'previous' && lbl !== 'next'
  })
})
</script>

<template>
  <nav class="mt-4 flex items-center gap-2">
    <component
      v-for="(link, i) in pageLinks"
      :key="`${i}-${link.label}`"
      :is="link.url ? Link : 'span'"
      :href="link.url || undefined"
      class="px-3 py-1 rounded border"
      :class="{
        'bg-indigo-600 text-white': link.active,
        'text-gray-500 cursor-not-allowed': !link.url
      }"
      v-html="link.label"
    />
  </nav>
</template>
