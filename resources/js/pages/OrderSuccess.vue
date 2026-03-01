<script setup lang="ts">
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

    <GuestLayout title="Order Confirmed">
        <div class="flex min-h-[60vh] items-center justify-center py-16">
            <div class="max-w-md w-full bg-white rounded-lg shadow p-10 text-center">
                <div class="mb-4 flex items-center justify-center">
                    <span class="flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-3xl">
                        ✓
                    </span>
                </div>

                <h1 class="text-2xl font-semibold mb-2">Payment Successful</h1>
                <p class="text-gray-500 mb-6 text-sm">Thank you for your order!</p>

                <div class="mb-6 space-y-2 text-sm">
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-500">Order reference</span>
                        <span class="font-medium">#{{ order.reference }}</span>
                    </div>
                    <div class="flex justify-between pt-1">
                        <span class="text-gray-500">Total paid</span>
                        <span class="font-semibold">{{ order.total }}</span>
                    </div>
                </div>

                <Link
                    href="/shop"
                    class="inline-block w-full rounded-lg bg-black py-3 text-sm font-medium text-white hover:opacity-80 transition-opacity"
                >
                    Continue Shopping
                </Link>
            </div>
        </div>
    </GuestLayout>
</template>
