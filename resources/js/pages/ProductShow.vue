<script setup lang="ts">
import GuestFooter from '@/components/GuestFooter.vue';
import { useCart } from '@/composables/useCart';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface VariantOption {
    option: string;
    value: string;
}

interface Variant {
    id: number;
    sku: string;
    price: string;
    in_stock: boolean;
    options: VariantOption[];
}

interface Product {
    id: number;
    name: string;
    description: string;
    image: string;
    images: string[];
    variants: Variant[];
}

const props = defineProps<{ product: Product }>();

const activeImage = ref(props.product.images[0] ?? props.product.image);
const adding = ref<number | null>(null);

const { addToCart } = useCart();
const cartError = computed(() => (usePage().props.errors as Record<string, string>).cart ?? null);

function variantLabel(variant: Variant): string {
    return variant.options.map(o => o.value).join(', ') || variant.sku;
}

function handleAddToCart(variantId: number) {
    adding.value = variantId;
    addToCart(variantId, 1, () => { adding.value = null; });
}
</script>

<template>
    <Head :title="product.name" />

    <GuestLayout :title="product.name" :full-width="true">

        <!-- Hero banner -->
        <div class="relative py-16 px-6 text-center overflow-hidden" style="background: linear-gradient(135deg, #6b2737 0%, #a84a5a 40%, #c46b72 70%, #8b3a4a 100%)">
            <div class="absolute inset-0 opacity-10"
                 style="background-image: radial-gradient(circle, #ffffff 1px, transparent 1px); background-size: 32px 32px;" />
            <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-rose-300/20 blur-[80px]" />
            <div class="relative">
                <Link href="/shop" class="inline-flex items-center gap-1 text-rose-200 hover:text-white text-xs uppercase tracking-widest font-medium mb-4 transition-colors">
                    &larr; Back to Shop
                </Link>
                <h1 class="text-4xl sm:text-5xl font-bold text-white">{{ product.name }}</h1>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-gray-100 min-h-screen">
            <div class="max-w-6xl mx-auto px-6 py-14">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">

                    <!-- Images -->
                    <div>
                        <div class="aspect-square bg-white rounded-2xl overflow-hidden shadow-sm mb-4">
                            <img
                                v-if="activeImage"
                                :src="activeImage"
                                :alt="product.name"
                                class="w-full h-full object-cover"
                            />
                            <div v-else class="w-full h-full flex items-center justify-center text-gray-300 text-sm">
                                No image
                            </div>
                        </div>

                        <div v-if="product.images.length > 1" class="flex gap-2 flex-wrap">
                            <button
                                v-for="(img, i) in product.images"
                                :key="i"
                                @click="activeImage = img"
                                class="w-16 h-16 rounded-xl overflow-hidden border-2 transition-all"
                                :class="activeImage === img ? 'border-rose-400 opacity-100' : 'border-transparent opacity-50 hover:opacity-80'"
                            >
                                <img :src="img" :alt="product.name" class="w-full h-full object-cover" />
                        </button>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="flex flex-col">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ product.name }}</h2>

                        <div v-if="product.description" class="text-gray-600 mb-8 leading-relaxed" v-html="product.description" />

                        <!-- Variants -->
                        <div v-if="product.variants.length > 0">
                            <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-400 mb-3">
                                Available options
                            </h3>

                            <p v-if="cartError" class="mb-4 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700">
                                {{ cartError }}
                            </p>

                            <div class="flex flex-col gap-3">
                                <div
                                    v-for="variant in product.variants"
                                    :key="variant.id"
                                    class="flex items-center justify-between border border-gray-200 rounded-2xl px-5 py-4 bg-white shadow-sm"
                                >
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ variantLabel(variant) }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ variant.sku }}</p>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <span class="font-semibold text-gray-900">{{ variant.price }}</span>
                                        <button
                                            @click="handleAddToCart(variant.id)"
                                            :disabled="!variant.in_stock || adding === variant.id"
                                            class="text-sm px-5 py-2 rounded-xl font-medium transition-opacity"
                                            :class="!variant.in_stock
                                                ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                                : adding === variant.id
                                                    ? 'bg-black text-white opacity-50 cursor-not-allowed'
                                                    : 'bg-black text-white hover:opacity-80'"
                                        >
                                            {{ !variant.in_stock ? 'Sold out' : adding === variant.id ? 'Adding…' : 'Add to cart' }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <GuestFooter />

    </GuestLayout>
</template>
