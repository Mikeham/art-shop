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
                        <div class="flex items-center justify-between px-6 py-5" style="background: linear-gradient(135deg, #6b2737 0%, #a84a5a 40%, #c46b72 70%, #8b3a4a 100%)">
                            <div class="flex items-center gap-2.5">
                                <ShoppingBag class="h-5 w-5 text-white/80" />
                                <h2 class="text-base font-semibold text-white">Your Cart</h2>
                                <span v-if="cart && cart.lines.length > 0"
                                      class="flex h-5 w-5 items-center justify-center rounded-full bg-white/20 text-[10px] font-bold text-white">
                                    {{ cart.item_count }}
                                </span>
                            </div>
                            <button
                                @click="closeCart"
                                class="rounded-full p-1.5 text-white/70 hover:text-white hover:bg-white/15 transition-colors"
                            >
                                <X class="h-5 w-5" />
                            </button>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 overflow-y-auto px-6 py-5 bg-gray-50">

                            <!-- Error -->
                            <p v-if="cartError" class="mb-4 rounded-xl bg-red-50 border border-red-100 px-4 py-3 text-sm text-red-700">
                                {{ cartError }}
                            </p>

                            <!-- Empty state -->
                            <div v-if="!cart || cart.lines.length === 0"
                                 class="flex flex-col items-center justify-center h-full text-gray-400 py-16 gap-3">
                                <ShoppingBag class="h-12 w-12 opacity-30" />
                                <p class="text-base font-medium">Your cart is empty</p>
                                <p class="text-sm">Add some items to get started</p>
                            </div>

                            <!-- Line items -->
                            <ul v-else class="space-y-3">
                                <li
                                    v-for="line in cart.lines"
                                    :key="line.id"
                                    class="flex gap-4 bg-white rounded-2xl p-4 shadow-sm"
                                >
                                    <!-- Thumbnail -->
                                    <div class="h-18 w-18 flex-shrink-0 overflow-hidden rounded-xl bg-gray-100" style="width:4.5rem;height:4.5rem">
                                        <img
                                            v-if="line.image"
                                            :src="line.image"
                                            :alt="line.product_name"
                                            class="h-full w-full object-cover"
                                        />
                                    </div>

                                    <!-- Info -->
                                    <div class="flex flex-1 flex-col justify-between min-w-0">
                                        <div>
                                            <p class="font-semibold text-sm text-gray-900 truncate">{{ line.product_name }}</p>
                                            <p v-if="line.variant_label" class="text-xs text-gray-400 mt-0.5">{{ line.variant_label }}</p>
                                        </div>

                                        <div class="flex items-center justify-between mt-2">
                                            <!-- Quantity controls -->
                                            <div class="flex items-center gap-2">
                                                <button
                                                    @click="updateQuantity(line.id, line.quantity - 1)"
                                                    class="flex h-6 w-6 items-center justify-center rounded-full border border-gray-200 hover:border-gray-400 transition-colors"
                                                >
                                                    <Minus class="h-3 w-3" />
                                                </button>
                                                <span class="w-5 text-center text-sm font-semibold">{{ line.quantity }}</span>
                                                <button
                                                    @click="updateQuantity(line.id, line.quantity + 1)"
                                                    class="flex h-6 w-6 items-center justify-center rounded-full border border-gray-200 hover:border-gray-400 transition-colors"
                                                >
                                                    <Plus class="h-3 w-3" />
                                                </button>
                                            </div>

                                            <div class="flex items-center gap-3">
                                                <span class="text-sm font-bold text-gray-900">{{ line.sub_total }}</span>
                                                <button
                                                    @click="removeFromCart(line.id)"
                                                    class="text-gray-300 hover:text-red-400 transition-colors"
                                                >
                                                    <Trash2 class="h-4 w-4" />
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- Footer -->
                        <div v-if="cart && cart.lines.length > 0" class="border-t bg-white px-6 py-5 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Total</span>
                                <span class="text-lg font-bold text-gray-900">{{ cart.total }}</span>
                            </div>
                            <Link
                                href="/checkout"
                                @click="closeCart"
                                class="block w-full rounded-xl bg-black py-3 text-center text-sm font-semibold text-white hover:opacity-80 transition-opacity"
                            >
                                Checkout
                            </Link>
                            <button
                                @click="closeCart"
                                class="w-full rounded-xl border border-gray-200 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors"
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
