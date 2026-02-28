<script setup lang="ts">
import { useCart } from '@/composables/useCart';
import { X, Minus, Plus, Trash2 } from 'lucide-vue-next';

const { cart, cartOpen, closeCart, removeFromCart, updateQuantity } = useCart();
</script>

<template>
    <Teleport to="body">
        <Transition name="cart-overlay">
            <div
                v-if="cartOpen"
                class="fixed inset-0 z-50 flex justify-end"
            >
                <!-- Backdrop -->
                <div
                    class="absolute inset-0 bg-black/50"
                    @click="closeCart"
                />

                <!-- Panel -->
                <Transition name="cart-panel">
                    <div
                        v-if="cartOpen"
                        class="relative flex h-full w-full max-w-md flex-col bg-white text-black shadow-xl"
                    >
                        <!-- Header -->
                        <div class="flex items-center justify-between border-b px-6 py-4">
                            <h2 class="text-lg font-semibold">Your Cart</h2>
                            <button
                                @click="closeCart"
                                class="rounded-full p-1 hover:bg-gray-100 transition-colors"
                            >
                                <X class="h-5 w-5" />
                            </button>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 overflow-y-auto px-6 py-4">
                            <!-- Empty state -->
                            <div
                                v-if="!cart || cart.lines.length === 0"
                                class="flex flex-col items-center justify-center h-full text-gray-500 py-12"
                            >
                                <p class="text-lg">Your cart is empty</p>
                                <p class="text-sm mt-1">Add some items to get started</p>
                            </div>

                            <!-- Line items -->
                            <ul v-else class="divide-y divide-gray-100">
                                <li
                                    v-for="line in cart.lines"
                                    :key="line.id"
                                    class="flex gap-4 py-4"
                                >
                                    <!-- Thumbnail -->
                                    <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-lg bg-gray-100">
                                        <img
                                            v-if="line.image"
                                            :src="line.image"
                                            :alt="line.product_name"
                                            class="h-full w-full object-cover"
                                        />
                                    </div>

                                    <!-- Info -->
                                    <div class="flex flex-1 flex-col justify-between">
                                        <div>
                                            <p class="font-medium text-sm">{{ line.product_name }}</p>
                                            <p v-if="line.variant_label" class="text-xs text-gray-500 mt-0.5">{{ line.variant_label }}</p>
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <!-- Quantity controls -->
                                            <div class="flex items-center gap-2">
                                                <button
                                                    @click="updateQuantity(line.id, line.quantity - 1)"
                                                    class="flex h-7 w-7 items-center justify-center rounded-full border border-gray-300 hover:bg-gray-100 transition-colors"
                                                >
                                                    <Minus class="h-3 w-3" />
                                                </button>
                                                <span class="w-6 text-center text-sm font-medium">{{ line.quantity }}</span>
                                                <button
                                                    @click="updateQuantity(line.id, line.quantity + 1)"
                                                    class="flex h-7 w-7 items-center justify-center rounded-full border border-gray-300 hover:bg-gray-100 transition-colors"
                                                >
                                                    <Plus class="h-3 w-3" />
                                                </button>
                                            </div>

                                            <div class="flex items-center gap-3">
                                                <span class="text-sm font-semibold">{{ line.sub_total }}</span>
                                                <button
                                                    @click="removeFromCart(line.id)"
                                                    class="text-gray-400 hover:text-red-500 transition-colors"
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
                        <div v-if="cart && cart.lines.length > 0" class="border-t px-6 py-4 space-y-4">
                            <div class="flex items-center justify-between text-base font-semibold">
                                <span>Total</span>
                                <span>{{ cart.total }}</span>
                            </div>
                            <button
                                @click="closeCart"
                                class="w-full rounded-lg border border-black py-2.5 text-sm font-medium hover:bg-gray-50 transition-colors"
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
