<script setup lang="ts">
import { useCart } from '@/composables/useCart';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

interface VariantOption {
    option: string;
    value: string;
}

interface Variant {
    id: number;
    sku: string;
    price: string;
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

    <GuestLayout :title="product.name">
        <div class="py-10">
            <Link href="/shop" class="text-sm text-gray-500 hover:text-black mb-8 inline-block">&larr; Back to Shop</Link>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">

                <!-- Images -->
                <div>
                    <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden mb-3">
                        <img
                            v-if="activeImage"
                            :src="activeImage"
                            :alt="product.name"
                            class="w-full h-full object-cover"
                        />
                        <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                            No image
                        </div>
                    </div>

                    <div v-if="product.images.length > 1" class="flex gap-2 flex-wrap">
                        <button
                            v-for="(img, i) in product.images"
                            :key="i"
                            @click="activeImage = img"
                            class="w-16 h-16 rounded overflow-hidden border-2 transition-colors"
                            :class="activeImage === img ? 'border-black' : 'border-transparent'"
                        >
                            <img :src="img" :alt="product.name" class="w-full h-full object-cover" />
                        </button>
                    </div>
                </div>

                <!-- Details -->
                <div class="flex flex-col">
                    <h1 class="text-3xl font-semibold mb-3">{{ product.name }}</h1>

                    <div v-if="product.description" class="text-gray-600 mb-6 leading-relaxed" v-html="product.description" />

                    <!-- Variants -->
                    <div v-if="product.variants.length > 0">
                        <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500 mb-3">
                            Available options
                        </h2>

                        <div class="flex flex-col gap-3">
                            <div
                                v-for="variant in product.variants"
                                :key="variant.id"
                                class="flex items-center justify-between border border-gray-200 rounded-lg px-4 py-3 bg-white"
                            >
                                <div>
                                    <p class="font-medium">{{ variantLabel(variant) }}</p>
                                    <p class="text-sm text-gray-500">{{ variant.sku }}</p>
                                </div>

                                <div class="flex items-center gap-4">
                                    <span class="font-semibold">{{ variant.price }}</span>
                                    <button
                                        @click="handleAddToCart(variant.id)"
                                        :disabled="adding === variant.id"
                                        class="bg-black text-white text-sm px-4 py-2 rounded transition-opacity"
                                        :class="adding === variant.id ? 'opacity-50 cursor-not-allowed' : 'hover:opacity-80'"
                                    >
                                        {{ adding === variant.id ? 'Adding…' : 'Add to cart' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
