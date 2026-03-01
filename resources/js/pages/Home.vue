<script setup lang="ts">
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

interface FeaturedProduct {
    id: number;
    name: string;
    thumbnail: string;
    slug: string | null;
    price: string;
}

defineProps<{ featured: FeaturedProduct[] }>();
</script>

<template>
    <Head title="Home" />

    <GuestLayout :full-width="true">

        <!-- ─── Hero ─────────────────────────────────────────────── -->
        <section class="relative flex items-center justify-center min-h-[92vh] overflow-hidden" style="background: linear-gradient(135deg, #6b2737 0%, #a84a5a 40%, #c46b72 70%, #8b3a4a 100%)">
            <!-- Subtle grid texture overlay -->
            <div class="absolute inset-0 opacity-10"
                 style="background-image: radial-gradient(circle, #ffffff 1px, transparent 1px); background-size: 32px 32px;" />

            <!-- Soft gradient blobs -->
            <div class="absolute -top-32 -left-32 w-[500px] h-[500px] rounded-full bg-rose-300/20 blur-[120px]" />
            <div class="absolute -bottom-32 -right-32 w-[500px] h-[500px] rounded-full bg-pink-200/15 blur-[120px]" />

            <div class="relative z-10 text-center px-6 max-w-3xl mx-auto">
                <p class="text-rose-300 tracking-[0.3em] text-sm uppercase font-medium mb-4">Original Artworks</p>
                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold text-white leading-tight mb-6">
                    Art That Speaks<br />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-400 to-pink-300">
                        for Itself
                    </span>
                </h1>
                <p class="text-white/75 text-lg sm:text-xl mb-10 leading-relaxed">
                    Prints, originals, and custom commissions — each piece made with care.
                </p>
                <div class="flex flex-wrap gap-4 justify-center">
                    <Link
                        href="/shop"
                        class="px-8 py-3 rounded-full bg-white text-gray-900 font-semibold text-sm hover:bg-gray-100 transition-colors"
                    >
                        Shop Now
                    </Link>
                    <Link
                        href="/gallery"
                        class="px-8 py-3 rounded-full border border-white/30 text-white font-semibold text-sm hover:bg-white/10 transition-colors"
                    >
                        View Gallery
                    </Link>
                </div>
            </div>

            <!-- Scroll hint -->
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-1 text-white/50 text-xs tracking-widest uppercase">
                <span>Scroll</span>
                <div class="w-px h-8 bg-white/40 animate-pulse" />
            </div>
        </section>

        <!-- ─── Featured Works ────────────────────────────────────── -->
        <section class="bg-gray-100 py-20 px-6">
            <div class="max-w-6xl mx-auto">
                <div class="flex items-end justify-between mb-10">
                    <div>
                        <p class="text-xs tracking-[0.25em] uppercase text-gray-400 font-medium mb-1">Selected works</p>
                        <h2 class="text-3xl font-bold text-gray-900">Featured Pieces</h2>
                    </div>
                    <Link href="/shop" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors hidden sm:block">
                        See all &rarr;
                    </Link>
                </div>

                <div v-if="featured.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <Link
                        v-for="product in featured"
                        :key="product.id"
                        :href="product.slug ? `/shop/${product.slug}` : '/shop'"
                        class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow"
                    >
                        <div class="aspect-square overflow-hidden bg-gray-100">
                            <img
                                v-if="product.thumbnail"
                                :src="product.thumbnail"
                                :alt="product.name"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            />
                            <div v-else class="w-full h-full flex items-center justify-center text-gray-300 text-sm">
                                No image
                            </div>
                        </div>
                        <div class="p-5 flex items-center justify-between">
                            <p class="font-semibold text-gray-900">{{ product.name }}</p>
                            <p class="text-sm font-medium text-gray-500">{{ product.price }}</p>
                        </div>
                    </Link>
                </div>

                <div class="mt-8 text-center sm:hidden">
                    <Link href="/shop" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">
                        See all &rarr;
                    </Link>
                </div>
            </div>
        </section>

        <!-- ─── Commissions ───────────────────────────────────────── -->
        <section class="bg-gray-900 py-20 px-6 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10"
                 style="background-image: radial-gradient(circle, #ffffff 1px, transparent 1px); background-size: 32px 32px;" />
            <div class="absolute top-0 right-0 w-96 h-96 rounded-full bg-rose-500/20 blur-[100px]" />

            <div class="relative max-w-3xl mx-auto text-center">
                <p class="text-xs tracking-[0.25em] uppercase text-rose-300 font-medium mb-3">Made for you</p>
                <h2 class="text-4xl font-bold text-white mb-5">Custom Commissions</h2>
                <p class="text-gray-400 text-lg leading-relaxed mb-10">
                    Want something unique? I take on a limited number of commissions each month — portraits,
                    fan art, and more. Get in touch to discuss your idea.
                </p>
                <div class="flex flex-wrap gap-4 justify-center">
                    <Link
                        href="/commissions"
                        class="px-8 py-3 rounded-full bg-rose-500 hover:bg-rose-400 text-white font-semibold text-sm transition-colors"
                    >
                        Learn More
                    </Link>
                    <Link
                        href="/contact"
                        class="px-8 py-3 rounded-full border border-white/20 text-white font-semibold text-sm hover:bg-white/10 transition-colors"
                    >
                        Get in Touch
                    </Link>
                </div>
            </div>
        </section>

        <!-- ─── Footer strip ─────────────────────────────────────── -->
        <section class="bg-gray-100 border-t border-gray-200 py-10 px-6">
            <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-500">
                <p>&copy; {{ new Date().getFullYear() }} — All rights reserved.</p>
                <nav class="flex gap-6">
                    <Link href="/gallery" class="hover:text-gray-900 transition-colors">Gallery</Link>
                    <Link href="/shop" class="hover:text-gray-900 transition-colors">Shop</Link>
                    <Link href="/commissions" class="hover:text-gray-900 transition-colors">Commissions</Link>
                    <Link href="/contact" class="hover:text-gray-900 transition-colors">Contact</Link>
                </nav>
            </div>
        </section>

    </GuestLayout>
</template>
