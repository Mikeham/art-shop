<script setup lang="ts">
import GuestFooter from '@/components/GuestFooter.vue';
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
        <section
            class="relative flex min-h-[92vh] items-center justify-center overflow-hidden"
            style="background: linear-gradient(135deg, #6b2737 0%, #a84a5a 40%, #c46b72 70%, #8b3a4a 100%)"
        >
            <!-- Subtle grid texture overlay -->
            <div
                class="absolute inset-0 opacity-10"
                style="background-image: radial-gradient(circle, #ffffff 1px, transparent 1px); background-size: 32px 32px"
            />

            <!-- Soft gradient blobs -->
            <div class="absolute -top-32 -left-32 h-[500px] w-[500px] rounded-full bg-rose-300/20 blur-[120px]" />
            <div class="absolute -right-32 -bottom-32 h-[500px] w-[500px] rounded-full bg-pink-200/15 blur-[120px]" />

            <div class="relative z-10 mx-auto max-w-3xl px-6 text-center">
                <p class="mb-4 text-sm font-medium tracking-[0.3em] text-rose-300 uppercase">Original Artworks</p>
                <h1 class="mb-6 text-5xl leading-tight font-bold text-white sm:text-6xl lg:text-7xl">
                    Art That Speaks<br />
                    <span class="bg-gradient-to-r from-rose-400 to-pink-300 bg-clip-text text-transparent"> for Itself </span>
                </h1>
                <p class="mb-10 text-lg leading-relaxed text-white/75 sm:text-xl">
                    Prints, originals, and custom commissions — each piece made with care.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <Link
                        href="/shop"
                        class="rounded-full bg-white px-8 py-3 text-sm font-semibold text-gray-900 transition-colors hover:bg-gray-100"
                    >
                        Shop Now
                    </Link>
                    <Link
                        href="/gallery"
                        class="rounded-full border border-white/30 px-8 py-3 text-sm font-semibold text-white transition-colors hover:bg-white/10"
                    >
                        View Gallery
                    </Link>
                </div>
            </div>

            <!-- Scroll hint -->
            <div class="absolute bottom-8 left-1/2 flex -translate-x-1/2 flex-col items-center gap-1 text-xs tracking-widest text-white/50 uppercase">
                <span>Scroll</span>
                <div class="h-8 w-px animate-pulse bg-white/40" />
            </div>
        </section>

        <!-- ─── Featured Works ────────────────────────────────────── -->
        <section class="bg-gray-100 px-6 py-20">
            <div class="mx-auto max-w-6xl">
                <div class="mb-10 flex items-end justify-between">
                    <div>
                        <p class="mb-1 text-xs font-medium tracking-[0.25em] text-gray-400 uppercase">Selected works</p>
                        <h2 class="text-3xl font-bold text-gray-900">Featured Pieces</h2>
                    </div>
                    <Link href="/shop" class="hidden text-sm font-medium text-gray-500 transition-colors hover:text-gray-900 sm:block">
                        See all &rarr;
                    </Link>
                </div>

                <div v-if="featured.length" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <Link
                        v-for="product in featured"
                        :key="product.id"
                        :href="product.slug ? `/shop/${product.slug}` : '/shop'"
                        class="group overflow-hidden rounded-2xl bg-white shadow-sm transition-shadow hover:shadow-xl"
                    >
                        <div class="aspect-square overflow-hidden bg-gray-100">
                            <img
                                v-if="product.thumbnail"
                                :src="product.thumbnail"
                                :alt="product.name"
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                            />
                            <div v-else class="flex h-full w-full items-center justify-center text-sm text-gray-300">No image</div>
                        </div>
                        <div class="flex items-center justify-between p-5">
                            <p class="font-semibold text-gray-900">{{ product.name }}</p>
                            <p class="text-sm font-medium text-gray-500">{{ product.price }}</p>
                        </div>
                    </Link>
                </div>

                <div class="mt-8 text-center sm:hidden">
                    <Link href="/shop" class="text-sm font-medium text-gray-500 transition-colors hover:text-gray-900"> See all &rarr; </Link>
                </div>
            </div>
        </section>

        <!-- ─── Commissions ───────────────────────────────────────── -->
        <section class="relative overflow-hidden bg-gray-900 px-6 py-20">
            <div
                class="absolute inset-0 opacity-10"
                style="background-image: radial-gradient(circle, #ffffff 1px, transparent 1px); background-size: 32px 32px"
            />
            <div class="absolute top-0 right-0 h-96 w-96 rounded-full bg-rose-500/20 blur-[100px]" />

            <div class="relative mx-auto max-w-3xl text-center">
                <p class="mb-3 text-xs font-medium tracking-[0.25em] text-rose-300 uppercase">Made for you</p>
                <h2 class="mb-5 text-4xl font-bold text-white">Custom Commissions</h2>
                <p class="mb-10 text-lg leading-relaxed text-gray-400">
                    Want something unique? I take on a limited number of commissions each month — portraits, fan art, and more. Get in touch to
                    discuss your idea.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <Link
                        href="/commissions"
                        class="rounded-full bg-rose-500 px-8 py-3 text-sm font-semibold text-white transition-colors hover:bg-rose-400"
                    >
                        Learn More
                    </Link>
                    <Link
                        href="/contact"
                        class="rounded-full border border-white/20 px-8 py-3 text-sm font-semibold text-white transition-colors hover:bg-white/10"
                    >
                        Get in Touch
                    </Link>
                </div>
            </div>
        </section>

        <GuestFooter />
    </GuestLayout>
</template>
