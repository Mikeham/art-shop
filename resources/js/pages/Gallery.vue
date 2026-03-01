<script setup lang="ts">
import GuestFooter from '@/components/GuestFooter.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

interface GalleryProduct {
    id: number;
    name: string;
    description: string;
    thumbnail: string;
    images: string[];
}

defineProps<{ products: GalleryProduct[] }>();

const modal = ref<GalleryProduct | null>(null);
const activeIndex = ref(0);

function openModal(product: GalleryProduct) {
    modal.value = product;
    activeIndex.value = 0;
}

function closeModal() {
    modal.value = null;
}

function prev() {
    if (!modal.value) return;
    activeIndex.value = (activeIndex.value - 1 + modal.value.images.length) % modal.value.images.length;
}

function next() {
    if (!modal.value) return;
    activeIndex.value = (activeIndex.value + 1) % modal.value.images.length;
}

function onKey(e: KeyboardEvent) {
    if (!modal.value) return;
    if (e.key === 'Escape') closeModal();
    if (e.key === 'ArrowLeft') prev();
    if (e.key === 'ArrowRight') next();
}
</script>

<template>
    <Head title="Gallery" />

    <GuestLayout title="Gallery" :full-width="true">

        <!-- Hero banner -->
        <div class="relative py-16 px-6 text-center overflow-hidden" style="background: linear-gradient(135deg, #6b2737 0%, #a84a5a 40%, #c46b72 70%, #8b3a4a 100%)">
            <div class="absolute inset-0 opacity-10"
                 style="background-image: radial-gradient(circle, #ffffff 1px, transparent 1px); background-size: 32px 32px;" />
            <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-rose-300/20 blur-[80px]" />
            <div class="relative">
                <p class="text-rose-200 tracking-[0.3em] text-xs uppercase font-medium mb-3">Portfolio</p>
                <h1 class="text-4xl sm:text-5xl font-bold text-white">Gallery</h1>
            </div>
        </div>

        <!-- Grid -->
        <div class="bg-gray-100 min-h-screen">
            <div class="max-w-7xl mx-auto px-6 py-14" @keydown="onKey" tabindex="-1">

                <div v-if="products.length === 0" class="text-center text-gray-400 py-32">
                    <p class="text-xl">No artwork yet.</p>
                </div>

                <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <button
                        v-for="product in products"
                        :key="product.id"
                        class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden text-left focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-400 flex flex-row h-72"
                        @click="openModal(product)"
                    >
                        <!-- Image — left half -->
                        <div class="w-1/2 flex-shrink-0 overflow-hidden bg-gray-100">
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

                        <!-- Description — right half -->
                        <div class="w-1/2 flex flex-col justify-between p-5">
                            <div>
                                <p class="font-semibold text-gray-900 text-base mb-2">{{ product.name }}</p>
                                <div v-if="product.description" class="text-sm text-gray-500 leading-relaxed line-clamp-4" v-html="product.description" />
                            </div>
                            <span class="text-xs text-gray-400 mt-3">
                                {{ product.images.length }} {{ product.images.length === 1 ? 'photo' : 'photos' }} — click to view
                            </span>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <GuestFooter />

        <!-- Modal -->
        <Teleport to="body">
            <Transition name="modal-fade">
                <div
                    v-if="modal"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4"
                    @click.self="closeModal"
                    @keydown="onKey"
                    tabindex="0"
                >
                    <div class="absolute inset-0 bg-black/80" @click="closeModal" />

                    <div class="relative z-10 w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

                        <!-- Header -->
                        <div class="flex items-center justify-between px-6 py-4 border-b flex-shrink-0">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">{{ modal.name }}</h2>
                                <p class="text-xs text-gray-400">{{ activeIndex + 1 }} / {{ modal.images.length }}</p>
                            </div>
                            <button
                                @click="closeModal"
                                class="rounded-full p-2 hover:bg-rose-50 text-gray-400 hover:text-rose-500 transition-colors"
                                aria-label="Close"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Main image -->
                        <div class="relative flex-1 bg-gray-50 flex items-center justify-center overflow-hidden min-h-0">
                            <img
                                :src="modal.images[activeIndex]"
                                :alt="modal.name"
                                class="max-h-full max-w-full object-contain"
                            />

                            <button
                                v-if="modal.images.length > 1"
                                @click="prev"
                                class="absolute left-3 rounded-full p-2 text-white bg-black/60 hover:bg-black/80 transition-colors"
                                aria-label="Previous"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>

                            <button
                                v-if="modal.images.length > 1"
                                @click="next"
                                class="absolute right-3 rounded-full p-2 text-white bg-black/60 hover:bg-black/80 transition-colors"
                                aria-label="Next"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>

                        <!-- Thumbnails -->
                        <div
                            v-if="modal.images.length > 1"
                            class="flex gap-2 px-4 py-3 overflow-x-auto flex-shrink-0 border-t bg-gray-50"
                        >
                            <button
                                v-for="(img, i) in modal.images"
                                :key="i"
                                @click="activeIndex = i"
                                class="flex-shrink-0 w-14 h-14 rounded-lg overflow-hidden border-2 transition-all"
                                :class="activeIndex === i ? 'border-rose-400 opacity-100' : 'border-transparent opacity-50 hover:opacity-80'"
                            >
                                <img :src="img" :alt="modal.name" class="w-full h-full object-cover" />
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>

    </GuestLayout>
</template>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active {
    transition: opacity 0.2s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
    opacity: 0;
}
</style>
