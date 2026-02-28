<script setup lang="ts">
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

interface Product {
    id: number;
    name: string;
    image: string;
    price: string;
    slug: string | null;
}

defineProps<{ product: Product }>();
</script>

<template>
    <Head :title="product.name" />

    <GuestLayout :title="product.name" description="Product">
        <div class="py-10 max-w-4xl mx-auto">
            <Link href="/shop" class="text-sm text-gray-500 hover:text-black mb-6 inline-block">&larr; Back to Shop</Link>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-4">
                <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                    <img
                        v-if="product.image"
                        :src="product.image"
                        :alt="product.name"
                        class="w-full h-full object-cover"
                    />
                    <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                        No image
                    </div>
                </div>

                <div class="flex flex-col justify-center">
                    <h1 class="text-3xl font-semibold mb-4">{{ product.name }}</h1>
                    <p class="text-2xl font-medium text-gray-700 mb-6">{{ product.price }}</p>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
