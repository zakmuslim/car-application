<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
  role: { type: String, default: "applicant" },
});
</script>

<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </template>

    <div class="py-8">
      <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 grid gap-6 md:grid-cols-2">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <h3 class="text-lg font-semibold mb-1">Apply for a Vehicle Loan</h3>
            <p class="text-sm text-gray-600 mb-3">Start a new application.</p>
            <Link
              :href="route('loans.create')"
              class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700"
            >
              Open form
            </Link>
          </div>
        </div>

        <div
          class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
          v-if="role === 'consultant'"
        >
          <div class="p-6">
            <h3 class="text-lg font-semibold mb-1">Manage Applications</h3>
            <p class="text-sm text-gray-600 mb-3">
              Search, filter, and review submissions.
            </p>
            <Link
              :href="route('loans.index')"
              class="inline-flex items-center rounded-md bg-slate-700 px-4 py-2 text-white hover:bg-slate-800"
            >
              View applications
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
