<script setup lang="ts">
import GuestHeader from '@/components/GuestHeader.vue';


defineProps<{
    title?: string;
    description?: string;
}>();

</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-200">
            <GuestHeader />

            <!-- Page Content -->
            <main>
                <div class="container mx-auto text-black">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
