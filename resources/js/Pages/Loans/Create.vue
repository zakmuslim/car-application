<script setup>
import { useForm } from "@inertiajs/vue3";
import { onMounted, ref, computed } from "vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const vehicles = ref({});
const makes = computed(() => Object.keys(vehicles.value?.[form.vehicle_type] || {}));
const models = computed(
  () => vehicles.value?.[form.vehicle_type]?.[form.vehicle_make] || []
);

const form = useForm({
  applicant_full_name: "",
  email: "",
  phone: "",
  date_of_birth: "",
  vehicle_type: "",
  vehicle_make: "",
  vehicle_model: "",
  purchase_price: 0,
  deposit_amount: 0,
  term_months: 12,
  consent_to_credit_check: false,
});

onMounted(async () => {
  vehicles.value = await (await fetch("/vehicles")).json();
});

function submit() {
  form.post(route("loans.store"));
}

function onTypeChange() {
  form.vehicle_make = "";
  form.vehicle_model = "";
}

function onMakeChange() {
  form.vehicle_model = "";
}

</script>

<Head title="Apply" />
<template>
  <AuthenticatedLayout>
  <div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Vehicle Loan Application</h1>
    <form @submit.prevent="submit" class="space-y-6">
      <div>
        <label class="block text-sm font-medium">Full Name</label>
        <input v-model="form.applicant_full_name" type="text" class="mt-1 input" />
        <p v-if="form.errors.applicant_full_name" class="error">
          {{ form.errors.applicant_full_name }}
        </p>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium">Email</label>
          <input v-model="form.email" type="email" class="mt-1 input" />
          <p v-if="form.errors.email" class="error">{{ form.errors.email }}</p>
        </div>
        <div>
          <label class="block text-sm font-medium">Phone</label>
          <input v-model="form.phone" type="tel" class="mt-1 input" />
          <p v-if="form.errors.phone" class="error">{{ form.errors.phone }}</p>
        </div>
      </div>
      <div>
        <label class="block text-sm font-medium">Date of Birth</label>
        <input v-model="form.date_of_birth" type="date" class="mt-1 input" />
        <p class="text-xs text-gray-500">Must be 18+ years.</p>
        <p v-if="form.errors.date_of_birth" class="error">
          {{ form.errors.date_of_birth }}
        </p>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium">Vehicle Type</label>
          <select v-model="form.vehicle_type" @change="onTypeChange" class="mt-1 input">
            <option value="" disabled>Select...</option>
            <option value="car">Car</option>
            <option value="ute">Ute</option>
            <option value="truck">Truck</option>
            <option value="van">Van</option>
            <option value="suv">SUV</option>
          </select>
          <p v-if="form.errors.vehicle_type" class="error">
            {{ form.errors.vehicle_type }}
          </p>
        </div>
        <div>
          <label class="block text-sm font-medium">Make</label>
          <select
            v-model="form.vehicle_make"
            @change="onMakeChange"
            class="mt-1 input"
            :disabled="!form.vehicle_type"
          >
            <option value="" disabled>Select...</option>
            <option v-for="m in makes" :key="m" :value="m">{{ m }}</option>
          </select>
          <p v-if="form.errors.vehicle_make" class="error">
            {{ form.errors.vehicle_make }}
          </p>
        </div>
        <div>
          <label class="block text-sm font-medium">Model</label>
          <select
            v-model="form.vehicle_model"
            class="mt-1 input"
            :disabled="!form.vehicle_make"
          >
            <option value="" disabled>Select...</option>
            <option v-for="mm in models" :key="mm" :value="mm">{{ mm }}</option>
          </select>
          <p v-if="form.errors.vehicle_model" class="error">
            {{ form.errors.vehicle_model }}
          </p>
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium">Purchase Price (AUD)</label>
          <input
            v-model.number="form.purchase_price"
            type="number"
            min="0"
            step="0.01"
            class="mt-1 input"
          />
          <p v-if="form.errors.purchase_price" class="error">
            {{ form.errors.purchase_price }}
          </p>
        </div>
        <div>
          <label class="block text-sm font-medium">Deposit Amount (AUD)</label>
          <input
            v-model.number="form.deposit_amount"
            type="number"
            min="0"
            step="0.01"
            class="mt-1 input"
          />
          <p v-if="form.errors.deposit_amount" class="error">
            {{ form.errors.deposit_amount }}
          </p>
        </div>
        <div>
          <label class="block text-sm font-medium">Term (months)</label>
          <input
            v-model.number="form.term_months"
            type="number"
            min="6"
            max="96"
            class="mt-1 input"
          />
          <p v-if="form.errors.term_months" class="error">
            {{ form.errors.term_months }}
          </p>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <input
          id="consent"
          v-model="form.consent_to_credit_check"
          type="checkbox"
          class="rounded"
        />
        <label for="consent" class="text-sm">I consent to a credit check.</label>
      </div>
      <p v-if="form.errors.consent_to_credit_check" class="error">
        {{ form.errors.consent_to_credit_check }}
      </p>
      <button :disabled="form.processing" class="btn-primary">
        <span v-if="form.processing">Submitting…</span><span v-else>Submit</span>
      </button>
    </form>
  </div>
  </AuthenticatedLayout>
</template>
<style scoped>
.input {
  @apply w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500;
}
.btn-primary {
  @apply rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700 disabled:opacity-50;
}
.error {
  @apply text-sm text-red-600 mt-1;
}
</style>
