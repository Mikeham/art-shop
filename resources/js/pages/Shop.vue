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
}

defineProps<{ products: Product[] }>();
</script>

<template>
    <Head title="Shop" />

    <GuestLayout title="Shop" :full-width="true">

        <!-- Hero banner -->
        <div class="relative py-16 px-6 text-center overflow-hidden" style="background: linear-gradient(135deg, #6b2737 0%, #a84a5a 40%, #c46b72 70%, #8b3a4a 100%)">
            <div class="absolute inset-0 opacity-10"
                 style="background-image: radial-gradient(circle, #ffffff 1px, transparent 1px); background-size: 32px 32px;" />
            <div class="absolute -top-24 -left-24 w-72 h-72 rounded-full bg-rose-300/20 blur-[80px]" />
            <div class="relative">
                <p class="text-rose-200 tracking-[0.3em] text-xs uppercase font-medium mb-3">Original Artwork</p>
                <h1 class="text-4xl sm:text-5xl font-bold text-white">Shop</h1>
            </div>
        </div>

        <!-- Product grid -->
        <div class="bg-gray-100 min-h-screen">
            <div class="max-w-7xl mx-auto px-6 py-14">

                <div v-if="products.length === 0" class="text-center text-gray-400 py-32">
                    <p class="text-xl">No products available yet.</p>
                </div>

                <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <div
                        v-for="product in products"
                        :key="product.id"
                        class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col"
                    >
                        <div class="aspect-square bg-gray-100 overflow-hidden">
                            <img
                                v-if="product.image"
                                :src="product.image"
                                :alt="product.name"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            />
                            <div v-else class="w-full h-full flex items-center justify-center text-gray-300 text-sm">
                                No image
                            </div>
                        </div>

                        <div class="p-5 flex flex-col flex-1">
                            <h2 class="font-semibold text-gray-900 leading-tight mb-1">{{ product.name }}</h2>
                            <p class="text-sm text-gray-500 font-medium mb-4">{{ product.price }}</p>

                            <div class="mt-auto">
                                <Link
                                    v-if="product.slug"
                                    :href="`/shop/${product.slug}`"
                                    class="block w-full text-center py-2.5 px-4 rounded-xl text-sm font-semibold text-white bg-black hover:opacity-80 transition-opacity"
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
