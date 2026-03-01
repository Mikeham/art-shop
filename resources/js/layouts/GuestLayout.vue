<script setup lang="ts">
import CartModal from '@/components/CartModal.vue';
import GuestHeader from '@/components/GuestHeader.vue';

defineProps<{
    title?: string;
    description?: string;
    fullWidth?: boolean;
}>();
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-200">
            <GuestHeader />

            <!-- Page Content -->
            <main>
                <div :class="fullWidth ? 'text-black' : 'container mx-auto text-black'">
                    <slot />
                </div>
            </main>
        </div>

        <CartModal />
    </div>
</template>
