<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
  <Head title="Application Submitted" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-white leading-tight">Application Submitted</h2>
    </template>
    <div class="max-w-xl mx-auto p-8 text-center">
      <h1 class="text-2xl font-semibold mb-2">Thanks!</h1>
      <p>Your application has been submitted.</p>
    </div>
  </AuthenticatedLayout>
</template>
