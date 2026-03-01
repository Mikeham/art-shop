<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <footer class="bg-gray-100 border-t border-gray-200 py-10 px-6">
        <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-500">
            <p>&copy; {{ new Date().getFullYear() }} — All rights reserved.</p>
            <nav class="flex gap-6">
                <Link href="/gallery" class="hover:text-gray-900 transition-colors">Gallery</Link>
                <Link href="/shop" class="hover:text-gray-900 transition-colors">Shop</Link>
                <Link href="/commissions" class="hover:text-gray-900 transition-colors">Commissions</Link>
                <Link href="/contact" class="hover:text-gray-900 transition-colors">Contact</Link>
            </nav>
        </div>
    </footer>
</template>
