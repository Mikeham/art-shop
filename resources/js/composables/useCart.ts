import { router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const cartOpen = ref(false);

export function useCart() {
    const cart = computed(() => (usePage().props as Record<string, unknown>).cart as CartData | null);
    const itemCount = computed(() => cart.value?.item_count ?? 0);

    function openCart() {
        cartOpen.value = true;
    }

    function closeCart() {
        cartOpen.value = false;
    }

    function addToCart(variantId: number, quantity = 1, callbacks?: { onFinish?: () => void }) {
        router.post('/cart/add', { variant_id: variantId, quantity }, {
            preserveScroll: true,
            onSuccess: () => { cartOpen.value = true; },
            onFinish: callbacks?.onFinish,
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

    return {
        cart,
        itemCount,
        cartOpen,
        openCart,
        closeCart,
        addToCart,
        removeFromCart,
        updateQuantity,
    };
}

export interface CartLine {
    id: number;
    quantity: number;
    unit_price: string;
    sub_total: string;
    product_name: string;
    variant_label: string;
    image: string;
}

export interface CartData {
    total: string;
    item_count: number;
    lines: CartLine[];
}
