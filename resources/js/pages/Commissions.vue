<script setup lang="ts">
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Clock } from 'lucide-vue-next';

</script>

<template>
    <Head title="Home" />

    <GuestLayout title="home" description="Home Page">
        <div class="grid grid-cols-1 justify-center">
            <Clock class="mx-auto h-10 w-10" style="margin-top:40vh;" />
            <p class="mx-auto text-3xl mt-2" >
                Coming soon...
            </p>
        </div>
    </GuestLayout>
</template>
