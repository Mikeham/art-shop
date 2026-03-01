<script setup lang="ts">
import GuestFooter from '@/components/GuestFooter.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

interface Product {
    id: number;
    name: string;
    image: string;
    price: string;
    slug: string | null;
    in_stock: boolean;
}

defineProps<{ products: Product[] }>();
</script>

<template>
    <Head title="Shop" />

    <GuestLayout title="Shop" :full-width="true">
        <!-- Hero banner -->
        <div
            class="relative overflow-hidden px-6 py-16 text-center"
            style="background: linear-gradient(135deg, #6b2737 0%, #a84a5a 40%, #c46b72 70%, #8b3a4a 100%)"
        >
            <div
                class="absolute inset-0 opacity-10"
                style="background-image: radial-gradient(circle, #ffffff 1px, transparent 1px); background-size: 32px 32px"
            />
            <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-rose-300/20 blur-[80px]" />
            <div class="relative">
                <p class="mb-3 text-xs font-medium tracking-[0.3em] text-rose-200 uppercase">Original Artwork</p>
                <h1 class="text-4xl font-bold text-white sm:text-5xl">Shop</h1>
            </div>
        </div>

        <!-- Product grid -->
        <div class="min-h-screen bg-gray-100">
            <div class="mx-auto max-w-7xl px-6 py-14">
                <div v-if="products.length === 0" class="py-32 text-center text-gray-400">
                    <p class="text-xl">No products available yet.</p>
                </div>

                <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <div
                        v-for="product in products"
                        :key="product.id"
                        class="group flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm transition-all duration-300 hover:shadow-xl"
                    >
                        <div class="relative aspect-square overflow-hidden bg-gray-100">
                            <img
                                v-if="product.image"
                                :src="product.image"
                                :alt="product.name"
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                :class="!product.in_stock ? 'opacity-40 grayscale' : ''"
                            />
                            <div v-else class="flex h-full w-full items-center justify-center text-sm text-gray-300">No image</div>
                            <div
                                v-if="!product.in_stock"
                                class="absolute top-0 left-0 right-0 py-4 text-center text-xs font-bold tracking-widest text-white uppercase"
                                style="background: linear-gradient(135deg, #6b2737 0%, #a84a5a 50%, #c46b72 100%)"
                            >
                                Sold Out
                            </div>
                        </div>

                        <div class="flex flex-1 flex-col p-5">
                            <h2 class="mb-1 leading-tight font-semibold text-gray-900">{{ product.name }}</h2>
                            <p class="mb-4 text-sm font-medium text-gray-500">{{ product.price }}</p>

                            <div class="mt-auto">
                                <Link
                                    v-if="product.slug"
                                    :href="`/shop/${product.slug}`"
                                    class="block w-full rounded-xl bg-black px-4 py-2.5 text-center text-sm font-semibold text-white transition-opacity hover:opacity-80"
                                >
                                    View Product
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <GuestFooter />
    </GuestLayout>
</template>
