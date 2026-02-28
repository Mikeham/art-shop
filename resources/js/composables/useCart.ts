import type { AppPageProps } from '@/types';
import { router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const cartOpen = ref(false);

export function useCart() {
    const cart = computed(() => usePage<AppPageProps>().props.cart);
    const itemCount = computed(() => cart.value?.item_count ?? 0);

    function openCart() { cartOpen.value = true; }
    function closeCart() { cartOpen.value = false; }

    function addToCart(variantId: number, quantity = 1, onFinish?: () => void) {
        router.post('/cart/add', { variant_id: variantId, quantity }, {
            preserveScroll: true,
            onSuccess: () => { cartOpen.value = true; },
            onFinish,
        });
    }

    function removeFromCart(cartLineId: number) {
        router.delete(`/cart/lines/${cartLineId}`, { preserveScroll: true });
    }

    function updateQuantity(cartLineId: number, quantity: number) {
        if (quantity === 0) {
            removeFromCart(cartLineId);
            return;
        }
        router.patch(`/cart/lines/${cartLineId}`, { quantity }, { preserveScroll: true });
    }

    return { cart, itemCount, cartOpen, openCart, closeCart, addToCart, removeFromCart, updateQuantity };
}
