<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import { onMounted, ref, computed } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const vehicles = ref({})
const step = ref(1)

const f1 = ref(null), f2 = ref(null), f3 = ref(null), f4 = ref(null)

function stepValid(n) {
  const map = { 1: f1, 2: f2, 3: f3, 4: f4 }
  const el = map[n]?.value
  return !!el && el.checkValidity()
}

function nextFrom(n) {
  const map = { 1: f1, 2: f2, 3: f3 }
  const el = map[n]?.value
  if (!el.checkValidity()) {
    // shows browser hints next to the first invalid input (without page reload)
    el.reportValidity()
    return
  }
  step.value = n + 1
}

const form = useForm({
  first_name: '', last_name: '',
  email: '', phone: '', date_of_birth: '',
  vehicle_type: '', vehicle_make: '', vehicle_model: '',
  purchase_price: 0, deposit_amount: 0, term_months: 12,
  consent_to_credit_check: false,
})

// derived fields / helpers
const makes  = computed(() => Object.keys(vehicles.value?.[form.vehicle_type] || {}))
const models = computed(() => vehicles.value?.[form.vehicle_type]?.[form.vehicle_make] || [])
const loanAmount = computed(() => Number(form.purchase_price || 0) - Number(form.deposit_amount || 0))

onMounted(async () => {
  vehicles.value = await (await fetch('/vehicles')).json()
})

function next()   { if (step.value < 4) step.value++ }
function back()   { if (step.value > 1) step.value-- }
function onType() { form.vehicle_make=''; form.vehicle_model='' }
function onMake() { form.vehicle_model='' }

function submit() {
  // combine first/last into the field your backend expects
  const payload = {
    ...form.data(),
    applicant_full_name: `${form.first_name ?? ''} ${form.last_name ?? ''}`.trim(),
  }
  form.transform(() => payload).post(route('loans.store'))
}
</script>

<template>
  <Head title="Vehicle Loan Application" />
  <AuthenticatedLayout>
  <div class="min-h-screen dark:bg-gray-800 text-slate-800">
    <main class="flex items-center justify-center py-10 px-4">
      <div class="w-full max-w-2xl">
        <div class="bg-white p-8 rounded-xl shadow-lg border border-slate-200">
          <!-- Step header / progress -->
          <div class="space-y-4">
            <p class="text-sm font-medium text-[#1193d4]">Step {{ step }} of 4</p>
            <div class="w-full bg-slate-200 rounded-full h-2">
              <div class="bg-[#1193d4] h-2 rounded-full transition-all" :style="{ width: (step*25) + '%' }"/>
            </div>

            <h2 class="text-3xl font-bold" v-if="step===1">Tell us about yourself</h2>
            <h2 class="text-3xl font-bold" v-else-if="step===2">Your vehicle</h2>
            <h2 class="text-3xl font-bold" v-else-if="step===3">Financing details</h2>
            <h2 class="text-3xl font-bold" v-else>Consent & submit</h2>

            <p class="text-slate-500" v-if="step===1">
              Let's start with some basic information. This helps us tailor the best loan options for you.
            </p>
          </div>

          <!-- STEP 1: Personal -->
          <form ref="f1" novalidate v-if="step===1" class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-6" @submit.prevent="nextFrom(1)">
            <div>
              <label class="block text-sm font-medium">First name</label>
              <input v-model="form.first_name" required type="text" class="mt-1 w-full rounded-lg border-slate-300 bg-[#f6f7f8] focus:border-[#1193d4] focus:ring-[#1193d4]" placeholder="Enter your first name"/>
              <p v-if="form.errors.applicant_full_name" class="text-sm text-red-600 mt-1">{{ form.errors.applicant_full_name }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium">Last name</label>
              <input v-model="form.last_name" required type="text" class="mt-1 w-full rounded-lg border-slate-300 bg-[#f6f7f8] focus:border-[#1193d4] focus:ring-[#1193d4]" placeholder="Enter your last name"/>
            </div>
            <div class="sm:col-span-2">
              <label class="block text-sm font-medium">Email address</label>
              <input v-model="form.email" required type="email" class="mt-1 w-full rounded-lg border-slate-300 bg-[#f6f7f8] focus:border-[#1193d4] focus:ring-[#1193d4]" placeholder="Enter your email"/>
              <p v-if="form.errors.email" class="text-sm text-red-600 mt-1">{{ form.errors.email }}</p>
            </div>
            <div class="sm:col-span-2">
              <label class="block text-sm font-medium">Phone number</label>
              <input v-model="form.phone" required type="tel" class="mt-1 w-full rounded-lg border-slate-300 bg-[#f6f7f8] focus:border-[#1193d4] focus:ring-[#1193d4]" placeholder="Enter your phone number"/>
              <p v-if="form.errors.phone" class="text-sm text-red-600 mt-1">{{ form.errors.phone }}</p>
            </div>
            <div class="sm:col-span-2">
              <label class="block text-sm font-medium">Date of birth</label>
              <input v-model="form.date_of_birth" :max="new Date(Date.now() - 18*365.25*24*3600*1000).toISOString().slice(0,10)" required type="date" class="mt-1 w-full rounded-lg border-slate-300 bg-[#f6f7f8] focus:border-[#1193d4] focus:ring-[#1193d4]"/>
              <p class="text-xs text-slate-500 mt-1">Must be 18+ years.</p>
              <p v-if="form.errors.date_of_birth" class="text-sm text-red-600 mt-1">{{ form.errors.date_of_birth }}</p>
            </div>
            <div class="sm:col-span-2 flex justify-end pt-2">
              <button class="btn w-10 sm:w-auto py-3 px-6 rounded-lg text-black bg-[#1193d4] hover:bg-[#0e7eb4]">Continue</button>
            </div>
          </form>

          <!-- STEP 2: Vehicle -->
          <form ref="f2" novalidate v-else-if="step===2" class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-6" @submit.prevent="nextFrom(2)">
            <div>
              <label class="block text-sm font-medium">Vehicle type</label>
              <select required v-model="form.vehicle_type" @change="onType" class="form-select mt-1 w-full rounded-lg border-slate-300 bg-[#f6f7f8] focus:border-[#1193d4] focus:ring-[#1193d4]">
                <option value="" disabled>Select a type</option>
                <option value="car">Car</option><option value="ute">Ute</option>
                <option value="truck">Truck</option><option value="van">Van</option><option value="suv">SUV</option>
              </select>
              <p v-if="form.errors.vehicle_type" class="text-sm text-red-600 mt-1">{{ form.errors.vehicle_type }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium">Make</label>
              <select required v-model="form.vehicle_make" @change="onMake" :disabled="!form.vehicle_type" class="form-select mt-1 w-full rounded-lg border-slate-300 bg-[#f6f7f8] focus:border-[#1193d4] focus:ring-[#1193d4]">
                <option value="" disabled>Select a make</option>
                <option v-for="m in makes" :key="m" :value="m">{{ m }}</option>
              </select>
              <p v-if="form.errors.vehicle_make" class="text-sm text-red-600 mt-1">{{ form.errors.vehicle_make }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium">Model</label>
              <select required v-model="form.vehicle_model" :disabled="!form.vehicle_make" class="form-select mt-1 w-full rounded-lg border-slate-300 bg-[#f6f7f8] focus:border-[#1193d4] focus:ring-[#1193d4]">
                <option value="" disabled>Select a model</option>
                <option v-for="mm in models" :key="mm" :value="mm">{{ mm }}</option>
              </select>
              <p v-if="form.errors.vehicle_model" class="text-sm text-red-600 mt-1">{{ form.errors.vehicle_model }}</p>
            </div>
            <div class="sm:col-span-3 flex justify-between pt-2">
              <button type="button" class="py-3 px-6 rounded-lg border border-slate-300 hover:bg-slate-50" @click="back">Back</button>
              <button class="py-3 px-6 rounded-lg text-white bg-[#1193d4] hover:bg-[#0e7eb4]">Continue</button>
            </div>
          </form>

          <!-- STEP 3: Finance -->
          <form ref="f3" novalidate v-else-if="step===3" class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-6" @submit.prevent="nextFrom(3)">
            <div>
              <label class="block text-sm font-medium">Purchase price (AUD)</label>
              <input v-model.number="form.purchase_price" type="number" min="0" step="0.01" class="mt-1 w-full rounded-lg border-slate-300 bg-[#f6f7f8] focus:border-[#1193d4] focus:ring-[#1193d4]"/>
              <p v-if="form.errors.purchase_price" class="text-sm text-red-600 mt-1">{{ form.errors.purchase_price }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium">Deposit (AUD)</label>
              <input v-model.number="form.deposit_amount" type="number" min="0" step="0.01" class="mt-1 w-full rounded-lg border-slate-300 bg-[#f6f7f8] focus:border-[#1193d4] focus:ring-[#1193d4]"/>
              <p v-if="form.errors.deposit_amount" class="text-sm text-red-600 mt-1">{{ form.errors.deposit_amount }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium">Term (months)</label>
              <input v-model.number="form.term_months" type="number" min="6" max="96" class="mt-1 w-full rounded-lg border-slate-300 bg-[#f6f7f8] focus:border-[#1193d4] focus:ring-[#1193d4]"/>
              <p v-if="form.errors.term_months" class="text-sm text-red-600 mt-1">{{ form.errors.term_months }}</p>
            </div>
            <div class="sm:col-span-3">
              <div class="p-4 rounded-lg bg-slate-50 border border-slate-200">
                <div class="text-sm text-slate-600">Estimated loan amount</div>
                <div class="text-2xl font-semibold">A${{ Number(loanAmount).toLocaleString() }}</div>
              </div>
            </div>
            <div class="sm:col-span-3 flex justify-between pt-2">
              <button type="button" class="py-3 px-6 rounded-lg border border-slate-300 hover:bg-slate-50" @click="back">Back</button>
              <button class="py-3 px-6 rounded-lg text-white bg-[#1193d4] hover:bg-[#0e7eb4]">Continue</button>
            </div>
          </form>

          <!-- STEP 4: Consent & Submit -->
          <form ref="f4" v-else class="mt-6 space-y-6" @submit.prevent="submit">
            <label class="flex items-center gap-3">
              <input v-model="form.consent_to_credit_check" type="checkbox" class="rounded border-slate-300 text-[#1193d4] focus:ring-[#1193d4]">
              <span>I consent to a credit check.</span>
            </label>
            <p v-if="form.errors.consent_to_credit_check" class="text-sm text-red-600">{{ form.errors.consent_to_credit_check }}</p>

            <div class="flex justify-between pt-2">
              <button type="submit" class="py-3 px-6 rounded-lg border border-slate-300 hover:bg-slate-50" @click="back">Back</button>
              <button type="submit" :disabled="form.processing" class="py-3 px-6 rounded-lg text-white bg-[#1193d4] hover:bg-[#0e7eb4] disabled:opacity-50">
                <span v-if="form.processing">Submitting…</span>
                <span v-else>Submit application</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.form-select{
  background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
  background-position:right .5rem center; background-repeat:no-repeat; background-size:1.25em 1.25em; padding-right:2.25rem;
}
</style>
