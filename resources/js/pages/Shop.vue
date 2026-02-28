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

defineProps<{ products: Product[] }>();
</script>

<template>
    <Head title="Shop" />

    <GuestLayout title="Shop" description="Shop Page">
        <div class="py-10">
            <h1 class="text-3xl font-semibold mb-8 text-center">Shop</h1>

            <div v-if="products.length === 0" class="text-center text-gray-500 mt-20">
                <p class="text-xl">No products available yet.</p>
            </div>

            <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <div
                    v-for="product in products"
                    :key="product.id"
                    class="bg-white rounded-lg shadow overflow-hidden flex flex-col"
                >
                    <div class="aspect-square bg-gray-100 overflow-hidden">
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

                    <div class="p-4 flex flex-col flex-1">
                        <h2 class="font-semibold text-lg leading-tight mb-1">{{ product.name }}</h2>
                        <p class="text-gray-700 font-medium mb-4">{{ product.price }}</p>

                        <div class="mt-auto">
                            <Link
                                v-if="product.slug"
                                :href="`/shop/${product.slug}`"
                                class="block w-full text-center bg-black text-white py-2 px-4 rounded hover:bg-gray-800 transition-colors"
                            >
                                View Product
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
