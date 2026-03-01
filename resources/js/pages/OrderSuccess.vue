<script setup lang="ts">
import GuestFooter from '@/components/GuestFooter.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    order: {
        reference: string;
        total: string;
    };
}>();
</script>

<template>
    <Head title="Order Confirmed" />

    <GuestLayout title="Order Confirmed" :full-width="true">
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
                <p class="mb-3 text-xs font-medium tracking-[0.3em] text-rose-200 uppercase">Thank you</p>
                <h1 class="text-4xl font-bold text-white sm:text-5xl">Order Confirmed</h1>
            </div>
        </div>

        <div class="min-h-screen bg-gray-100">
            <div class="mx-auto max-w-lg px-6 py-14">
                <div class="rounded-2xl bg-white p-10 text-center shadow-sm">
                    <!-- Checkmark -->
                    <div class="mb-6 flex items-center justify-center">
                        <span
                            class="flex h-16 w-16 items-center justify-center rounded-full text-2xl font-bold text-white"
                            style="background: linear-gradient(135deg, #6b2737, #c46b72)"
                        >
                            ✓
                        </span>
                    </div>

                    <h2 class="mb-1 text-2xl font-bold text-gray-900">Payment Successful</h2>
                    <p class="mb-8 text-sm text-gray-400">Your order has been placed. We'll be in touch soon!</p>

                    <div class="mb-8 space-y-3 text-sm">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <span class="text-xs font-semibold tracking-[0.15em] text-gray-400 uppercase">Order reference</span>
                            <span class="font-semibold text-gray-900">#{{ order.reference }}</span>
                        </div>
                        <div class="flex items-center justify-between pt-1">
                            <span class="text-xs font-semibold tracking-[0.15em] text-gray-400 uppercase">Total paid</span>
                            <span class="text-lg font-bold text-gray-900">{{ order.total }}</span>
                        </div>
                    </div>

                    <Link
                        href="/shop"
                        class="block w-full rounded-xl bg-black py-3 text-sm font-semibold text-white transition-opacity hover:opacity-80"
                    >
                        Continue Shopping
                    </Link>
                </div>
            </div>
        </div>

        <GuestFooter />
    </GuestLayout>
</template>
