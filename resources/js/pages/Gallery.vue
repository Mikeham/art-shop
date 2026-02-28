<script setup lang="ts">
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps(['images']);
</script>

<template>
    <Head title="Home" />

    <GuestLayout title="home" description="Home Page">
        <div class="grid grid-cols-3 justify-center">

            <div v-for="image in images" class="m-4">
                <img :alt="image.name" :src="image.path" class=""/>
                <p>{{ image.name }}</p>
                <p v-if="description != ''">{{ image.description }}</p>
            </div>
        </div>
    </GuestLayout>
</template>
