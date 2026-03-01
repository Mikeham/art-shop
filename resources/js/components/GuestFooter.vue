<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <footer class="border-t border-gray-200 bg-gray-100 px-6 py-10">
        <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-4 text-sm text-gray-500 sm:flex-row">
            <p>&copy; {{ new Date().getFullYear() }} — All rights reserved.</p>
            <nav class="flex gap-6">
                <Link href="/gallery" class="transition-colors hover:text-gray-900">Gallery</Link>
                <Link href="/shop" class="transition-colors hover:text-gray-900">Shop</Link>
                <Link href="/commissions" class="transition-colors hover:text-gray-900">Commissions</Link>
                <Link href="/contact" class="transition-colors hover:text-gray-900">Contact</Link>
            </nav>
        </div>
    </footer>
</template>
