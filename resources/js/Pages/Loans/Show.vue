<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3'

const props = defineProps({
  loan: {
    type: Object,
    required: true,
  },
})

const fmtCurrency = (n) =>
  `A$${Number(n ?? 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`

const fmtDateTime = (iso) =>
  iso ? new Date(iso).toLocaleString() : '—'

const statusColor = (s) => ({
  submitted: 'bg-slate-100 text-slate-700',
  in_review: 'bg-amber-100 text-amber-700',
  approved: 'bg-emerald-100 text-emerald-700',
  declined: 'bg-rose-100 text-rose-700',
}[s] || 'bg-slate-100 text-slate-700')

// status update (consultant)
const statusForm = useForm({ status: props.loan.status })
function saveStatus() {
  statusForm.put(route('loans.update', props.loan.id), { preserveScroll: true })
}
function destroyLoan() {
  if (!confirm('Soft delete this application?')) return
  router.delete(route('loans.destroy', props.loan.id))
}
</script>

<template>
  <Head :title="`Application #${loan.id}`" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2 text-sm text-slate-500">
          <Link :href="route('loans.index')" class="hover:text-indigo-600">Applications</Link>
          <span>/</span>
          <span class="text-slate-700">Application Details</span>
        </div>
      </div>
    </template>

    <div class="py-8">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-slate-900 rounded-lg shadow-sm border border-slate-200 dark:border-slate-800 p-6 md:p-8">

          <!-- Header row -->
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
              <h2 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">Application Details</h2>
              <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Application ID: {{ loan.id }}
              </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
              <span class="px-3 py-1 text-xs font-medium rounded-full"
                    :class="statusColor(loan.status)">
                {{ loan.status.replace('_',' ') }}
              </span>
              <span class="text-sm text-slate-500 dark:text-slate-400">
                Submitted {{ fmtDateTime(loan.submitted_at) }}
              </span>
            </div>
          </div>

          <div class="space-y-12">

            <!-- Applicant -->
            <section>
              <h3 class="text-lg md:text-xl font-semibold text-slate-800 dark:text-slate-200 border-b border-slate-200 dark:border-slate-700 pb-3 mb-6">
                Applicant Information
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <div>
                  <label class="block text-sm font-medium text-slate-500">Full Name</label>
                  <p class="mt-1 text-base text-slate-900 dark:text-white">{{ loan.applicant_full_name }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-500">Email</label>
                  <p class="mt-1 text-base text-slate-900 dark:text-white">{{ loan.email }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-500">Phone Number</label>
                  <p class="mt-1 text-base text-slate-900 dark:text-white">{{ loan.phone }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-500">Date of Birth</label>
                  <p class="mt-1 text-base text-slate-900 dark:text-white">
                    {{ loan.date_of_birth }}
                  </p>
                </div>
              </div>
            </section>

            <!-- Vehicle -->
            <section>
              <h3 class="text-lg md:text-xl font-semibold text-slate-800 dark:text-slate-200 border-b border-slate-200 dark:border-slate-700 pb-3 mb-6">
                Vehicle Information
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <div>
                  <label class="block text-sm font-medium text-slate-500">Vehicle Type</label>
                  <p class="mt-1 text-base text-slate-900 dark:text-white capitalize">{{ loan.vehicle_type }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-500">Vehicle Price</label>
                  <p class="mt-1 text-base text-slate-900 dark:text-white">{{ fmtCurrency(loan.purchase_price) }}</p>
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-slate-500">Vehicle Make and Model</label>
                  <p class="mt-1 text-base text-slate-900 dark:text-white">
                    {{ loan.vehicle_make }} {{ loan.vehicle_model }}
                  </p>
                </div>
              </div>
            </section>

            <!-- Loan -->
            <section>
              <h3 class="text-lg md:text-xl font-semibold text-slate-800 dark:text-slate-200 border-b border-slate-200 dark:border-slate-700 pb-3 mb-6">
                Loan Details
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <div>
                  <label class="block text-sm font-medium text-slate-500">Deposit</label>
                  <p class="mt-1 text-base text-slate-900 dark:text-white">{{ fmtCurrency(loan.deposit_amount) }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-500">Loan Term</label>
                  <p class="mt-1 text-base text-slate-900 dark:text-white">{{ loan.term_months }} Months</p>
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-slate-500">Estimated Loan Amount</label>
                  <p class="mt-1 text-xl font-semibold text-slate-900 dark:text-white">
                    {{ fmtCurrency(loan.loan_amount) }}
                  </p>
                </div>
              </div>
            </section>

            <!-- Manage (status + delete) -->
            <section class="border-t border-slate-200 dark:border-slate-700 pt-6">
              <div class="flex flex-col md:flex-row items-start md:items-end gap-3">
                <div>
                  <label class="block text-sm font-medium text-slate-600 mb-1">Update Status</label>
                  <select v-model="statusForm.status" class="rounded-md border-slate-300 dark:border-slate-700 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="submitted">Submitted</option>
                    <option value="in_review">In Review</option>
                    <option value="approved">Approved</option>
                    <option value="declined">Declined</option>
                  </select>
                </div>
                <button
                  class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700 disabled:opacity-50"
                  :disabled="statusForm.processing"
                  @click="saveStatus"
                >
                  {{ statusForm.processing ? 'Saving…' : 'Save' }}
                </button>

                <button
                  class="inline-flex items-center rounded-md bg-rose-600 px-4 py-2 text-white hover:bg-rose-700 md:ml-auto"
                  @click="destroyLoan"
                >
                  Delete
                </button>
              </div>
            </section>

          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
