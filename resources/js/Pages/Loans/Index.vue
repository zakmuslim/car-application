[<script setup>
import { Link, router } from "@inertiajs/vue3";
import { ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
const props = defineProps({ loans: Object, filters: Object });
const q = ref(props.filters.q || "");
const vehicle_type = ref(props.filters.vehicle_type || "");
const status = ref(props.filters.status || "");
const pending = ref(false);
function applyFilters() {
  pending.value = true;
  router.get(
    route("loans.index"),
    { q: q.value, vehicle_type: vehicle_type.value, status: status.value },
    { preserveState: true, replace: true, onFinish: () => (pending.value = false) }
  );
}
</script>
<template>
  <Head title="Loan Applications" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Loan Applications</h2>
    </template>
    <div class="p-6 max-w-6xl mx-auto">
    <h1 class="text-2xl font-semibold mb-4">Applications</h1>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">
      <input
        class="input"
        placeholder="Search name/email/make/model"
        v-model="q"
        @keyup.enter="applyFilters"
      />
      <select class="input" v-model="vehicle_type" @change="applyFilters">
        <option value="">All Types</option>
        <option value="car">Car</option>
        <option value="ute">Ute</option>
        <option value="truck">Truck</option>
        <option value="van">Van</option>
        <option value="suv">SUV</option>
      </select>
      <select class="input" v-model="status" @change="applyFilters">
        <option value="">All Status</option>
        <option value="submitted">Submitted</option>
        <option value="in_review">In Review</option>
        <option value="approved">Approved</option>
        <option value="declined">Declined</option>
      </select>
      <button class="btn-primary" @click="applyFilters">Apply</button>
    </div>
    <div v-if="pending" class="space-y-2">
      <div v-for="i in 5" :key="i" class="h-14 bg-gray-100 animate-pulse rounded"></div>
    </div>
    <div v-else>
      <div v-if="loans.data.length === 0" class="text-gray-500">
        No applications found.
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="text-left text-gray-600">
            <tr>
              <th class="py-2 pr-4">ID</th>
              <th class="py-2 pr-4">Name</th>
              <th class="py-2 pr-4">Email</th>
              <th class="py-2 pr-4">Make/Model</th>
              <th class="py-2 pr-4">Price</th>
              <th class="py-2 pr-4">Term</th>
              <th class="py-2 pr-4">Status</th>
              <th class="py-2 pr-4">Submitted</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="row in loans.data"
              :key="row.id"
              class="border-t hover:bg-gray-50 cursor-pointer"
              @click="$inertia.get(route('loans.show', row.id))"
            >
              <td class="py-2 pr-4">{{ row.id }}</td>
              <td class="py-2 pr-4">{{ row.applicant_full_name }}</td>
              <td class="py-2 pr-4">{{ row.email }}</td>
              <td class="py-2 pr-4">{{ row.vehicle_make }} / {{ row.vehicle_model }}</td>
              <td class="py-2 pr-4">
                A${{ Number(row.purchase_price).toLocaleString() }}
              </td>
              <td class="py-2 pr-4">{{ row.term_months }}</td>
              <td class="py-2 pr-4">{{ row.status }}</td>
              <td class="py-2 pr-4">{{ new Date(row.submitted_at).toLocaleString() }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="mt-4 flex gap-2">
        <Link
          v-for="link in loans.links"
          :key="link.url || link.label"
          :href="link.url || '#'"
          class="px-3 py-1 rounded border"
          :class="{ 'bg-indigo-600 text-white': link.active, 'text-gray-500': !link.url }"
          v-html="link.label"
          preserve-state
        />
      </div>
    </div>
  </div>
  </AuthenticatedLayout>
</template>
<style scoped>
.input {
  @apply w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500;
}
.btn-primary {
  @apply rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700;
}
</style>
