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
        <div class="relative py-16 px-6 text-center overflow-hidden" style="background: linear-gradient(135deg, #6b2737 0%, #a84a5a 40%, #c46b72 70%, #8b3a4a 100%)">
            <div class="absolute inset-0 opacity-10"
                 style="background-image: radial-gradient(circle, #ffffff 1px, transparent 1px); background-size: 32px 32px;" />
            <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-rose-300/20 blur-[80px]" />
            <div class="relative">
                <p class="text-rose-200 tracking-[0.3em] text-xs uppercase font-medium mb-3">Thank you</p>
                <h1 class="text-4xl sm:text-5xl font-bold text-white">Order Confirmed</h1>
            </div>
        </div>

        <div class="bg-gray-100 min-h-screen">
            <div class="max-w-lg mx-auto px-6 py-14">

                <div class="bg-white rounded-2xl shadow-sm p-10 text-center">

                    <!-- Checkmark -->
                    <div class="mb-6 flex items-center justify-center">
                        <span class="flex h-16 w-16 items-center justify-center rounded-full text-white text-2xl font-bold"
                              style="background: linear-gradient(135deg, #6b2737, #c46b72)">
                            ✓
                        </span>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mb-1">Payment Successful</h2>
                    <p class="text-gray-400 text-sm mb-8">Your order has been placed. We'll be in touch soon!</p>

                    <div class="mb-8 space-y-3 text-sm">
                        <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                            <span class="text-xs font-semibold uppercase tracking-[0.15em] text-gray-400">Order reference</span>
                            <span class="font-semibold text-gray-900">#{{ order.reference }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-1">
                            <span class="text-xs font-semibold uppercase tracking-[0.15em] text-gray-400">Total paid</span>
                            <span class="text-lg font-bold text-gray-900">{{ order.total }}</span>
                        </div>
                    </div>

                    <Link
                        href="/shop"
                        class="block w-full rounded-xl bg-black py-3 text-sm font-semibold text-white hover:opacity-80 transition-opacity"
                    >
                        Continue Shopping
                    </Link>
                </div>

            </div>
        </div>

        <GuestFooter />

    </GuestLayout>
</template>
