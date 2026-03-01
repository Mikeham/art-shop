<script setup lang="ts">
import GuestFooter from '@/components/GuestFooter.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import type { AppPageProps, CartLine } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const page = usePage<AppPageProps>();
const cart = computed(() => page.props.cart);
const errors = computed(() => page.props.errors as Record<string, string>);

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

const redirecting = ref(false);

const countries = [
    { code: 'GB', name: 'United Kingdom' },
    { code: 'US', name: 'United States' },
    { code: 'CA', name: 'Canada' },
    { code: 'AU', name: 'Australia' },
    { code: 'DE', name: 'Germany' },
    { code: 'FR', name: 'France' },
    { code: 'NL', name: 'Netherlands' },
    { code: 'IE', name: 'Ireland' },
    { code: 'NZ', name: 'New Zealand' },
];

function submit() {
    redirecting.value = true;
    router.post('/checkout/session', form.value, {
        onFinish: () => {
            redirecting.value = false;
        },
    });
}

function lineImage(line: CartLine): string | null {
    return line.image ?? null;
}

const inputClass = 'w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 transition bg-white';
const labelClass = 'block text-xs font-semibold uppercase tracking-[0.15em] text-gray-400 mb-1.5';
const errorClass = 'mt-1 text-xs text-red-500';
</script>

<template>
    <Head title="Checkout" />

    <GuestLayout title="Checkout" :full-width="true">

        <!-- Hero banner -->
        <div class="relative py-16 px-6 text-center overflow-hidden" style="background: linear-gradient(135deg, #6b2737 0%, #a84a5a 40%, #c46b72 70%, #8b3a4a 100%)">
            <div class="absolute inset-0 opacity-10"
                 style="background-image: radial-gradient(circle, #ffffff 1px, transparent 1px); background-size: 32px 32px;" />
            <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-rose-300/20 blur-[80px]" />
            <div class="relative">
                <p class="text-rose-200 tracking-[0.3em] text-xs uppercase font-medium mb-3">Almost there</p>
                <h1 class="text-4xl sm:text-5xl font-bold text-white">Checkout</h1>
            </div>
        </div>

        <div class="bg-gray-100 min-h-screen">
            <div class="max-w-5xl mx-auto px-6 py-14">

                <div v-if="errors.checkout" class="mb-6 rounded-2xl bg-red-50 border border-red-100 px-5 py-4 text-sm text-red-700">
                    {{ errors.checkout }}
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                    <!-- Address form -->
                    <div class="bg-white rounded-2xl shadow-sm p-8">
                        <h2 class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-400 mb-6">Billing &amp; Shipping</h2>

                        <form @submit.prevent="submit" class="space-y-4">

                            <!-- Email -->
                            <div>
                                <label :class="labelClass">Email</label>
                                <input v-model="form.email" type="email" required
                                    placeholder="jane@example.com"
                                    :class="[inputClass, errors.email ? 'border-red-400' : '']" />
                                <p v-if="errors.email" :class="errorClass">{{ errors.email }}</p>
                            </div>

                            <!-- Name -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label :class="labelClass">First name</label>
                                    <input v-model="form.first_name" type="text" required
                                        :class="[inputClass, errors.first_name ? 'border-red-400' : '']" />
                                    <p v-if="errors.first_name" :class="errorClass">{{ errors.first_name }}</p>
                                </div>
                                <div>
                                    <label :class="labelClass">Last name</label>
                                    <input v-model="form.last_name" type="text" required
                                        :class="[inputClass, errors.last_name ? 'border-red-400' : '']" />
                                    <p v-if="errors.last_name" :class="errorClass">{{ errors.last_name }}</p>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label :class="labelClass">Phone number</label>
                                <input v-model="form.phone" type="tel" required
                                    placeholder="+44 7700 900000"
                                    :class="[inputClass, errors.phone ? 'border-red-400' : '']" />
                                <p v-if="errors.phone" :class="errorClass">{{ errors.phone }}</p>
                            </div>

                            <!-- Address -->
                            <div>
                                <label :class="labelClass">Address</label>
                                <input v-model="form.line_one" type="text" required
                                    :class="[inputClass, errors.line_one ? 'border-red-400' : '']" />
                                <p v-if="errors.line_one" :class="errorClass">{{ errors.line_one }}</p>
                            </div>

                            <!-- City + Postcode -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label :class="labelClass">City</label>
                                    <input v-model="form.city" type="text" required
                                        :class="[inputClass, errors.city ? 'border-red-400' : '']" />
                                    <p v-if="errors.city" :class="errorClass">{{ errors.city }}</p>
                                </div>
                                <div>
                                    <label :class="labelClass">Postcode</label>
                                    <input v-model="form.postcode" type="text" required
                                        :class="[inputClass, errors.postcode ? 'border-red-400' : '']" />
                                    <p v-if="errors.postcode" :class="errorClass">{{ errors.postcode }}</p>
                                </div>
                            </div>

                            <!-- Country -->
                            <div>
                                <label :class="labelClass">Country</label>
                                <select v-model="form.country"
                                    :class="[inputClass, errors.country ? 'border-red-400' : '']">
                                    <option v-for="c in countries" :key="c.code" :value="c.code">{{ c.name }}</option>
                                </select>
                                <p v-if="errors.country" :class="errorClass">{{ errors.country }}</p>
                            </div>

                            <button
                                type="submit"
                                :disabled="redirecting"
                                class="mt-2 w-full rounded-xl py-3 text-sm font-semibold text-white transition-opacity"
                                :class="redirecting ? 'opacity-50 cursor-not-allowed' : 'hover:opacity-85'"
                                style="background: linear-gradient(135deg, #6b2737, #c46b72)"
                            >
                                {{ redirecting ? 'Redirecting…' : 'Pay with Stripe →' }}
                            </button>
                        </form>
                    </div>

                    <!-- Order summary -->
                    <div class="bg-white rounded-2xl shadow-sm p-8 flex flex-col">
                        <h2 class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-400 mb-6">Order Summary</h2>

                        <div v-if="cart && cart.lines.length > 0" class="flex flex-col flex-1">
                            <ul class="space-y-3 flex-1">
                                <li v-for="line in cart.lines" :key="line.id"
                                    class="flex gap-4 bg-gray-50 rounded-xl p-3">
                                    <div class="h-14 w-14 flex-shrink-0 overflow-hidden rounded-lg bg-gray-100">
                                        <img v-if="lineImage(line)" :src="lineImage(line)!"
                                            :alt="line.product_name"
                                            class="h-full w-full object-cover" />
                                    </div>
                                    <div class="flex flex-1 flex-col justify-center min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ line.product_name }}</p>
                                        <p v-if="line.variant_label" class="text-xs text-gray-400">{{ line.variant_label }}</p>
                                        <p class="text-xs text-gray-400">Qty: {{ line.quantity }}</p>
                                    </div>
                                    <div class="text-sm font-bold text-gray-900 self-center flex-shrink-0">
                                        {{ line.sub_total }}
                                    </div>
                                </li>
                            </ul>

                            <div class="mt-6 pt-4 border-t flex items-center justify-between">
                                <span class="text-sm text-gray-500">Total</span>
                                <span class="text-lg font-bold text-gray-900">{{ cart.total }}</span>
                            </div>
                        </div>

                        <div v-else class="text-gray-400 text-sm text-center py-8">
                            Your cart is empty.
                        </div>

                        <button onclick="history.back()"
                            class="mt-6 text-center text-xs text-gray-400 hover:text-gray-700 transition-colors">
                            &larr; Edit cart
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <GuestFooter />

    </GuestLayout>
</template>
