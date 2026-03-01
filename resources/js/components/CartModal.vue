<script setup lang="ts">
import { useCart } from '@/composables/useCart';
import { Link, usePage } from '@inertiajs/vue3';
import { Minus, Plus, ShoppingBag, Trash2, X } from 'lucide-vue-next';
import { computed } from 'vue';

const { cart, cartOpen, closeCart, removeFromCart, updateQuantity } = useCart();

const cartError = computed(() => (usePage().props.errors as Record<string, string>).cart ?? null);
</script>

<template>
    <Teleport to="body">
        <Transition name="cart-overlay">
            <div v-if="cartOpen" class="fixed inset-0 z-50 flex justify-end">
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeCart" />

                <!-- Panel -->
                <Transition name="cart-panel">
                    <div v-if="cartOpen" class="relative flex h-full w-full max-w-md flex-col bg-white text-black shadow-2xl">
                        <!-- Header -->
                        <div
                            class="flex items-center justify-between px-6 py-5"
                            style="background: linear-gradient(135deg, #6b2737 0%, #a84a5a 40%, #c46b72 70%, #8b3a4a 100%)"
                        >
                            <div class="flex items-center gap-2.5">
                                <ShoppingBag class="h-5 w-5 text-white/80" />
                                <h2 class="text-base font-semibold text-white">Your Cart</h2>
                                <span
                                    v-if="cart && cart.lines.length > 0"
                                    class="flex h-5 w-5 items-center justify-center rounded-full bg-white/20 text-[10px] font-bold text-white"
                                >
                                    {{ cart.item_count }}
                                </span>
                            </div>
                            <button @click="closeCart" class="rounded-full p-1.5 text-white/70 transition-colors hover:bg-white/15 hover:text-white">
                                <X class="h-5 w-5" />
                            </button>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 overflow-y-auto bg-gray-50 px-6 py-5">
                            <!-- Error -->
                            <p v-if="cartError" class="mb-4 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700">
                                {{ cartError }}
                            </p>

                            <!-- Empty state -->
                            <div
                                v-if="!cart || cart.lines.length === 0"
                                class="flex h-full flex-col items-center justify-center gap-3 py-16 text-gray-400"
                            >
                                <ShoppingBag class="h-12 w-12 opacity-30" />
                                <p class="text-base font-medium">Your cart is empty</p>
                                <p class="text-sm">Add some items to get started</p>
                            </div>

                            <!-- Line items -->
                            <ul v-else class="space-y-3">
                                <li v-for="line in cart.lines" :key="line.id" class="flex gap-4 rounded-2xl bg-white p-4 shadow-sm">
                                    <!-- Thumbnail -->
                                    <div class="h-18 w-18 flex-shrink-0 overflow-hidden rounded-xl bg-gray-100" style="width: 4.5rem; height: 4.5rem">
                                        <img v-if="line.image" :src="line.image" :alt="line.product_name" class="h-full w-full object-cover" />
                                    </div>

                                    <!-- Info -->
                                    <div class="flex min-w-0 flex-1 flex-col justify-between">
                                        <div>
                                            <p class="truncate text-sm font-semibold text-gray-900">{{ line.product_name }}</p>
                                            <p v-if="line.variant_label" class="mt-0.5 text-xs text-gray-400">{{ line.variant_label }}</p>
                                        </div>

                                        <div class="mt-2 flex items-center justify-between">
                                            <!-- Quantity controls -->
                                            <div class="flex items-center gap-2">
                                                <button
                                                    @click="updateQuantity(line.id, line.quantity - 1)"
                                                    class="flex h-6 w-6 items-center justify-center rounded-full border border-gray-200 transition-colors hover:border-gray-400"
                                                >
                                                    <Minus class="h-3 w-3" />
                                                </button>
                                                <span class="w-5 text-center text-sm font-semibold">{{ line.quantity }}</span>
                                                <button
                                                    @click="updateQuantity(line.id, line.quantity + 1)"
                                                    class="flex h-6 w-6 items-center justify-center rounded-full border border-gray-200 transition-colors hover:border-gray-400"
                                                >
                                                    <Plus class="h-3 w-3" />
                                                </button>
                                            </div>

                                            <div class="flex items-center gap-3">
                                                <span class="text-sm font-bold text-gray-900">{{ line.sub_total }}</span>
                                                <button @click="removeFromCart(line.id)" class="text-gray-300 transition-colors hover:text-red-400">
                                                    <Trash2 class="h-4 w-4" />
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- Footer -->
                        <div v-if="cart && cart.lines.length > 0" class="space-y-3 border-t bg-white px-6 py-5">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Total</span>
                                <span class="text-lg font-bold text-gray-900">{{ cart.total }}</span>
                            </div>
                            <Link
                                href="/checkout"
                                @click="closeCart"
                                class="block w-full rounded-xl bg-black py-3 text-center text-sm font-semibold text-white transition-opacity hover:opacity-80"
                            >
                                Checkout
                            </Link>
                            <button
                                @click="closeCart"
                                class="w-full rounded-xl border border-gray-200 py-2.5 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-50"
                            >
                                Continue Shopping
                            </button>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.cart-overlay-enter-active,
.cart-overlay-leave-active {
    transition: opacity 0.2s ease;
}
.cart-overlay-enter-from,
.cart-overlay-leave-to {
    opacity: 0;
}

.cart-panel-enter-active,
.cart-panel-leave-active {
    transition: transform 0.3s ease;
}
.cart-panel-enter-from,
.cart-panel-leave-to {
    transform: translateX(100%);
}
</style>
