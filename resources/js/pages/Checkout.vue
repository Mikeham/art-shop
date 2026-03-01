<script setup lang="ts">
import GuestFooter from '@/components/GuestFooter.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import type { AppPageProps, CartLine, ShippingOption } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const page = usePage<AppPageProps>();
const cart = computed(() => page.props.cart);
const errors = computed(() => page.props.errors as Record<string, string>);
const step = computed(() => page.props.step ?? 'address');
const shippingOptions = computed(() => page.props.shippingOptions ?? []);
const countries = computed(() => page.props.countries ?? []);

const form = ref({
    email: '',
    first_name: '',
    last_name: '',
    phone: '',
    line_one: '',
    city: '',
    postcode: '',
    country: 'GB',
});

const selectedShipping = ref('');
const redirecting = ref(false);

function submitAddress() {
    router.post('/checkout/address', form.value, { preserveScroll: true });
}

function submitShipping() {
    redirecting.value = true;
    router.post(
        '/checkout/session',
        { shipping_option: selectedShipping.value },
        { onFinish: () => { redirecting.value = false; } },
    );
}

function lineImage(line: CartLine): string | null {
    return line.image ?? null;
}

const selectedOption = computed<ShippingOption | undefined>(() =>
    shippingOptions.value.find((o) => o.identifier === selectedShipping.value),
);

const inputClass =
    'w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 transition bg-white';
const labelClass = 'block text-xs font-semibold uppercase tracking-[0.15em] text-gray-400 mb-1.5';
const errorClass = 'mt-1 text-xs text-red-500';
</script>

<template>
    <Head title="Checkout" />

    <GuestLayout title="Checkout" :full-width="true">
        <!-- Hero banner -->
        <div
            class="relative overflow-hidden px-6 py-16 text-center"
            style="background: linear-gradient(135deg, #6b2737 0%, #a84a5a 40%, #c46b72 70%, #8b3a4a 100%)"
        >
            <div
                class="absolute inset-0 opacity-10"
                style="background-image: radial-gradient(circle, #ffffff 1px, transparent 1px); background-size: 32px 32px"
            />
            <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-rose-300/20 blur-[80px]" />
            <div class="relative">
                <p class="mb-3 text-xs font-medium tracking-[0.3em] text-rose-200 uppercase">Almost there</p>
                <h1 class="text-4xl font-bold text-white sm:text-5xl">Checkout</h1>
            </div>
        </div>

        <div class="min-h-screen bg-gray-100">
            <div class="mx-auto max-w-5xl px-6 py-14">
                <div v-if="errors.checkout" class="mb-6 rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-sm text-red-700">
                    {{ errors.checkout }}
                </div>

                <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                    <!-- Left card: address form or shipping selection -->
                    <div class="rounded-2xl bg-white p-8 shadow-sm">

                        <!-- Step 1: Address form -->
                        <template v-if="step === 'address'">
                            <h2 class="mb-6 text-xs font-semibold tracking-[0.2em] text-gray-400 uppercase">Billing &amp; Shipping</h2>

                            <form @submit.prevent="submitAddress" class="space-y-4">
                                <!-- Email -->
                                <div>
                                    <label :class="labelClass">Email</label>
                                    <input
                                        v-model="form.email"
                                        type="email"
                                        required
                                        placeholder="jane@example.com"
                                        :class="[inputClass, errors.email ? 'border-red-400' : '']"
                                    />
                                    <p v-if="errors.email" :class="errorClass">{{ errors.email }}</p>
                                </div>

                                <!-- Name -->
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label :class="labelClass">First name</label>
                                        <input
                                            v-model="form.first_name"
                                            type="text"
                                            required
                                            :class="[inputClass, errors.first_name ? 'border-red-400' : '']"
                                        />
                                        <p v-if="errors.first_name" :class="errorClass">{{ errors.first_name }}</p>
                                    </div>
                                    <div>
                                        <label :class="labelClass">Last name</label>
                                        <input
                                            v-model="form.last_name"
                                            type="text"
                                            required
                                            :class="[inputClass, errors.last_name ? 'border-red-400' : '']"
                                        />
                                        <p v-if="errors.last_name" :class="errorClass">{{ errors.last_name }}</p>
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label :class="labelClass">Phone number</label>
                                    <input
                                        v-model="form.phone"
                                        type="tel"
                                        required
                                        placeholder="+44 7700 900000"
                                        :class="[inputClass, errors.phone ? 'border-red-400' : '']"
                                    />
                                    <p v-if="errors.phone" :class="errorClass">{{ errors.phone }}</p>
                                </div>

                                <!-- Address -->
                                <div>
                                    <label :class="labelClass">Address</label>
                                    <input v-model="form.line_one" type="text" required :class="[inputClass, errors.line_one ? 'border-red-400' : '']" />
                                    <p v-if="errors.line_one" :class="errorClass">{{ errors.line_one }}</p>
                                </div>

                                <!-- City + Postcode -->
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label :class="labelClass">City</label>
                                        <input v-model="form.city" type="text" required :class="[inputClass, errors.city ? 'border-red-400' : '']" />
                                        <p v-if="errors.city" :class="errorClass">{{ errors.city }}</p>
                                    </div>
                                    <div>
                                        <label :class="labelClass">Postcode</label>
                                        <input
                                            v-model="form.postcode"
                                            type="text"
                                            required
                                            :class="[inputClass, errors.postcode ? 'border-red-400' : '']"
                                        />
                                        <p v-if="errors.postcode" :class="errorClass">{{ errors.postcode }}</p>
                                    </div>
                                </div>

                                <!-- Country -->
                                <div>
                                    <label :class="labelClass">Country</label>
                                    <select v-model="form.country" :class="[inputClass, errors.country ? 'border-red-400' : '']">
                                        <option v-for="c in countries" :key="c.code" :value="c.code">{{ c.name }}</option>
                                    </select>
                                    <p v-if="errors.country" :class="errorClass">{{ errors.country }}</p>
                                </div>

                                <button
                                    type="submit"
                                    class="mt-2 w-full rounded-xl py-3 text-sm font-semibold text-white transition-opacity hover:opacity-85"
                                    style="background: linear-gradient(135deg, #6b2737, #c46b72)"
                                >
                                    Continue to Shipping &rarr;
                                </button>
                            </form>
                        </template>

                        <!-- Step 2: Shipping selection -->
                        <template v-else-if="step === 'shipping'">
                            <h2 class="mb-6 text-xs font-semibold tracking-[0.2em] text-gray-400 uppercase">Shipping Method</h2>

                            <div class="space-y-3">
                                <label
                                    v-for="option in shippingOptions"
                                    :key="option.identifier"
                                    class="flex cursor-pointer items-start gap-4 rounded-xl border p-4 transition"
                                    :class="selectedShipping === option.identifier
                                        ? 'border-rose-400 bg-rose-50'
                                        : 'border-gray-200 hover:border-rose-200'"
                                >
                                    <input
                                        type="radio"
                                        v-model="selectedShipping"
                                        :value="option.identifier"
                                        class="mt-0.5 accent-rose-500"
                                    />
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-900">{{ option.name }}</p>
                                        <p v-if="option.description" class="mt-0.5 text-xs text-gray-500">{{ option.description }}</p>
                                    </div>
                                    <span class="text-sm font-bold text-gray-900">{{ option.price }}</span>
                                </label>
                            </div>

                            <button
                                type="button"
                                :disabled="!selectedShipping || redirecting"
                                @click="submitShipping"
                                class="mt-6 w-full rounded-xl py-3 text-sm font-semibold text-white transition-opacity"
                                :class="(!selectedShipping || redirecting) ? 'cursor-not-allowed opacity-50' : 'hover:opacity-85'"
                                style="background: linear-gradient(135deg, #6b2737, #c46b72)"
                            >
                                {{ redirecting ? 'Redirecting…' : 'Confirm & Pay with Stripe →' }}
                            </button>

                            <button
                                type="button"
                                onclick="history.back()"
                                class="mt-4 w-full text-center text-xs text-gray-400 transition-colors hover:text-gray-700"
                            >
                                &larr; Back to address
                            </button>
                        </template>
                    </div>

                    <!-- Order summary -->
                    <div class="flex flex-col rounded-2xl bg-white p-8 shadow-sm">
                        <h2 class="mb-6 text-xs font-semibold tracking-[0.2em] text-gray-400 uppercase">Order Summary</h2>

                        <div v-if="cart && cart.lines.length > 0" class="flex flex-1 flex-col">
                            <ul class="flex-1 space-y-3">
                                <li v-for="line in cart.lines" :key="line.id" class="flex gap-4 rounded-xl bg-gray-50 p-3">
                                    <div class="h-14 w-14 flex-shrink-0 overflow-hidden rounded-lg bg-gray-100">
                                        <img
                                            v-if="lineImage(line)"
                                            :src="lineImage(line)!"
                                            :alt="line.product_name"
                                            class="h-full w-full object-cover"
                                        />
                                    </div>
                                    <div class="flex min-w-0 flex-1 flex-col justify-center">
                                        <p class="truncate text-sm font-semibold text-gray-900">{{ line.product_name }}</p>
                                        <p v-if="line.variant_label" class="text-xs text-gray-400">{{ line.variant_label }}</p>
                                        <p class="text-xs text-gray-400">Qty: {{ line.quantity }}</p>
                                    </div>
                                    <div class="flex-shrink-0 self-center text-sm font-bold text-gray-900">
                                        {{ line.sub_total }}
                                    </div>
                                </li>
                            </ul>

                            <!-- Shipping line (shown when option selected) -->
                            <div v-if="selectedOption" class="mt-3 flex items-center justify-between rounded-xl bg-gray-50 px-3 py-2">
                                <span class="text-sm text-gray-500">{{ selectedOption.name }}</span>
                                <span class="text-sm font-bold text-gray-900">{{ selectedOption.price }}</span>
                            </div>

                            <div class="mt-6 flex items-center justify-between border-t pt-4">
                                <span class="text-sm text-gray-500">Total</span>
                                <span class="text-lg font-bold text-gray-900">{{ cart.total }}</span>
                            </div>
                        </div>

                        <div v-else class="py-8 text-center text-sm text-gray-400">Your cart is empty.</div>

                        <button
                            v-if="step === 'address'"
                            onclick="history.back()"
                            class="mt-6 text-center text-xs text-gray-400 transition-colors hover:text-gray-700"
                        >
                            &larr; Edit cart
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <GuestFooter />
    </GuestLayout>
</template>
