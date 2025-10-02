<script setup>
import { useForm, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
const props = defineProps({ loan: Object });
const form = useForm({ status: props.loan.status });
function save() {
  form.put(route("loans.update", props.loan.id), { preserveScroll: true });
}
function destroy() {
  if (confirm("Soft delete this application?"))
    router.delete(route("loans.destroy", props.loan.id));
}
</script>
<template>
  <Head :title="`Application #${loan.id}`" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Application Details</h2>
    </template>
    <div class="max-w-3xl mx-auto p-6 space-y-6">
    <h1 class="text-2xl font-semibold">Application #{{ loan.id }}</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <div class="label">Name</div>
        <div class="value">{{ loan.applicant_full_name }}</div>
      </div>
      <div>
        <div class="label">Email</div>
        <div class="value">{{ loan.email }}</div>
      </div>
      <div>
        <div class="label">Phone</div>
        <div class="value">{{ loan.phone }}</div>
      </div>
      <div>
        <div class="label">DOB</div>
        <div class="value">{{ loan.date_of_birth }}</div>
      </div>
      <div>
        <div class="label">Vehicle</div>
        <div class="value">
          {{ loan.vehicle_type }} / {{ loan.vehicle_make }} / {{ loan.vehicle_model }}
        </div>
      </div>
      <div>
        <div class="label">Price</div>
        <div class="value">A${{ Number(loan.purchase_price).toLocaleString() }}</div>
      </div>
      <div>
        <div class="label">Deposit</div>
        <div class="value">A${{ Number(loan.deposit_amount).toLocaleString() }}</div>
      </div>
      <div>
        <div class="label">Term</div>
        <div class="value">{{ loan.term_months }} months</div>
      </div>
      <div>
        <div class="label">Loan Amount</div>
        <div class="value font-semibold">
          A${{ Number(loan.loan_amount).toLocaleString() }}
        </div>
      </div>
      <div>
        <div class="label">Submitted</div>
        <div class="value">{{ new Date(loan.submitted_at).toLocaleString() }}</div>
      </div>
    </div>
    <div class="flex items-end gap-3">
      <div>
        <label class="block text-sm font-medium">Status</label>
        <select v-model="form.status" class="input">
          <option value="submitted">Submitted</option>
          <option value="in_review">In Review</option>
          <option value="approved">Approved</option>
          <option value="declined">Declined</option>
        </select>
      </div>
      <button class="btn-primary" @click="save" :disabled="form.processing">Save</button>
      <button class="btn-danger ml-auto" @click="destroy">Delete</button>
    </div>
  </div>
  </AuthenticatedLayout>
</template>
<style scoped>
.label {
  @apply text-xs text-gray-500;
}
.value {
  @apply text-sm;
}
.input {
  @apply rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500;
}
.btn-primary {
  @apply rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700 disabled:opacity-50;
}
.btn-danger {
  @apply rounded-md bg-red-600 px-4 py-2 text-white hover:bg-red-700;
}
</style>
