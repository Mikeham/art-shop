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
    return variant.options.map((o) => o.value).join(', ') || variant.sku;
}

function handleAddToCart(variantId: number) {
    adding.value = variantId;
    addToCart(variantId, 1, () => {
        adding.value = null;
    });
}
</script>

<template>
    <Head :title="product.name" />

    <GuestLayout :title="product.name" :full-width="true">
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
                <Link
                    href="/shop"
                    class="mb-4 inline-flex items-center gap-1 text-xs font-medium tracking-widest text-rose-200 uppercase transition-colors hover:text-white"
                >
                    &larr; Back to Shop
                </Link>
                <h1 class="text-4xl font-bold text-white sm:text-5xl">{{ product.name }}</h1>
            </div>
        </div>

        <!-- Content -->
        <div class="min-h-screen bg-gray-100">
            <div class="mx-auto max-w-6xl px-6 py-14">
                <div class="grid grid-cols-1 gap-12 md:grid-cols-2">
                    <!-- Images -->
                    <div>
                        <div class="mb-4 aspect-square overflow-hidden rounded-2xl bg-white shadow-sm">
                            <img v-if="activeImage" :src="activeImage" :alt="product.name" class="h-full w-full object-cover" />
                            <div v-else class="flex h-full w-full items-center justify-center text-sm text-gray-300">No image</div>
                        </div>

                        <div v-if="product.images.length > 1" class="flex flex-wrap gap-2">
                            <button
                                v-for="(img, i) in product.images"
                                :key="i"
                                @click="activeImage = img"
                                class="h-16 w-16 overflow-hidden rounded-xl border-2 transition-all"
                                :class="activeImage === img ? 'border-rose-400 opacity-100' : 'border-transparent opacity-50 hover:opacity-80'"
                            >
                                <img :src="img" :alt="product.name" class="h-full w-full object-cover" />
                            </button>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="flex flex-col">
                        <h2 class="mb-4 text-3xl font-bold text-gray-900">{{ product.name }}</h2>

                        <div v-if="product.description" class="mb-8 leading-relaxed text-gray-600" v-html="product.description" />

                        <!-- Variants -->
                        <div v-if="product.variants.length > 0">
                            <h3 class="mb-3 text-xs font-semibold tracking-[0.2em] text-gray-400 uppercase">Available options</h3>

                            <p v-if="cartError" class="mb-4 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700">
                                {{ cartError }}
                            </p>

                            <div class="flex flex-col gap-3">
                                <div
                                    v-for="variant in product.variants"
                                    :key="variant.id"
                                    class="flex items-center justify-between rounded-2xl border border-gray-200 bg-white px-5 py-4 shadow-sm"
                                >
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ variantLabel(variant) }}</p>
                                        <p class="mt-0.5 text-xs text-gray-400">{{ variant.sku }}</p>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <span class="font-semibold text-gray-900">{{ variant.price }}</span>
                                        <button
                                            @click="handleAddToCart(variant.id)"
                                            :disabled="!variant.in_stock || adding === variant.id"
                                            class="rounded-xl px-5 py-2 text-sm font-medium transition-opacity"
                                            :class="
                                                !variant.in_stock
                                                    ? 'cursor-not-allowed bg-gray-100 text-gray-400'
                                                    : adding === variant.id
                                                      ? 'cursor-not-allowed bg-black text-white opacity-50'
                                                      : 'bg-black text-white hover:opacity-80'
                                            "
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
