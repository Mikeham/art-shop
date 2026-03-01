<script setup lang="ts">
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
</script>

<template>
    <Head title="Checkout" />

    <GuestLayout title="Checkout">
        <div class="py-10">
            <h1 class="text-3xl font-semibold mb-8 text-center">Checkout</h1>

            <div v-if="errors.checkout" class="mb-6 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700 max-w-4xl mx-auto">
                {{ errors.checkout }}
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 max-w-4xl mx-auto">

                <!-- Address form -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-5">Billing &amp; Shipping</h2>

                    <form @submit.prevent="submit" class="space-y-4">
                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input
                                v-model="form.email"
                                type="email"
                                required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black"
                                :class="{ 'border-red-500': errors.email }"
                            />
                            <p v-if="errors.email" class="mt-1 text-xs text-red-600">{{ errors.email }}</p>
                        </div>

                        <!-- Name row -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">First name</label>
                                <input
                                    v-model="form.first_name"
                                    type="text"
                                    required
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black"
                                    :class="{ 'border-red-500': errors.first_name }"
                                />
                                <p v-if="errors.first_name" class="mt-1 text-xs text-red-600">{{ errors.first_name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Last name</label>
                                <input
                                    v-model="form.last_name"
                                    type="text"
                                    required
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black"
                                    :class="{ 'border-red-500': errors.last_name }"
                                />
                                <p v-if="errors.last_name" class="mt-1 text-xs text-red-600">{{ errors.last_name }}</p>
                            </div>
                        </div>

                        <!-- Address line 1 -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <input
                                v-model="form.line_one"
                                type="text"
                                required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black"
                                :class="{ 'border-red-500': errors.line_one }"
                            />
                            <p v-if="errors.line_one" class="mt-1 text-xs text-red-600">{{ errors.line_one }}</p>
                        </div>

                        <!-- City + Postcode -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <input
                                    v-model="form.city"
                                    type="text"
                                    required
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black"
                                    :class="{ 'border-red-500': errors.city }"
                                />
                                <p v-if="errors.city" class="mt-1 text-xs text-red-600">{{ errors.city }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Postcode</label>
                                <input
                                    v-model="form.postcode"
                                    type="text"
                                    required
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black"
                                    :class="{ 'border-red-500': errors.postcode }"
                                />
                                <p v-if="errors.postcode" class="mt-1 text-xs text-red-600">{{ errors.postcode }}</p>
                            </div>
                        </div>

                        <!-- Country -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                            <select
                                v-model="form.country"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black"
                                :class="{ 'border-red-500': errors.country }"
                            >
                                <option v-for="c in countries" :key="c.code" :value="c.code">{{ c.name }}</option>
                            </select>
                            <p v-if="errors.country" class="mt-1 text-xs text-red-600">{{ errors.country }}</p>
                        </div>

                        <button
                            type="submit"
                            :disabled="redirecting"
                            class="mt-2 w-full rounded-lg bg-black py-3 text-sm font-medium text-white transition-opacity"
                            :class="redirecting ? 'opacity-50 cursor-not-allowed' : 'hover:opacity-80'"
                        >
                            {{ redirecting ? 'Redirecting…' : 'Pay with Stripe →' }}
                        </button>
                    </form>
                </div>

                <!-- Order summary -->
                <div class="bg-white rounded-lg shadow p-6 flex flex-col">
                    <h2 class="text-lg font-semibold mb-5">Order Summary</h2>

                    <div v-if="cart && cart.lines.length > 0">
                        <ul class="divide-y divide-gray-100 flex-1">
                            <li
                                v-for="line in cart.lines"
                                :key="line.id"
                                class="flex gap-4 py-4"
                            >
                                <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-lg bg-gray-100">
                                    <img
                                        v-if="lineImage(line)"
                                        :src="lineImage(line)!"
                                        :alt="line.product_name"
                                        class="h-full w-full object-cover"
                                    />
                                </div>
                                <div class="flex flex-1 flex-col justify-center">
                                    <p class="text-sm font-medium">{{ line.product_name }}</p>
                                    <p v-if="line.variant_label" class="text-xs text-gray-500">{{ line.variant_label }}</p>
                                    <p class="text-xs text-gray-500">Qty: {{ line.quantity }}</p>
                                </div>
                                <div class="text-sm font-semibold self-center">
                                    {{ line.sub_total }}
                                </div>
                            </li>
                        </ul>

                        <div class="mt-4 border-t pt-4 flex items-center justify-between text-base font-semibold">
                            <span>Total</span>
                            <span>{{ cart.total }}</span>
                        </div>
                    </div>

                    <div v-else class="text-gray-500 text-sm text-center py-8">
                        Your cart is empty.
                    </div>

                    <button
                        onclick="history.back()"
                        class="mt-6 text-center text-sm text-gray-500 hover:text-black underline"
                    >
                        &larr; Edit cart
                    </button>
                </div>

            </div>
        </div>
    </GuestLayout>
</template>
