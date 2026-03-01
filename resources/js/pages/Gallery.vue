<script setup lang="ts">
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

    <GuestLayout title="Gallery" description="Gallery Page">
        <div class="py-10" @keydown="onKey" tabindex="-1">
            <h1 class="text-3xl font-semibold mb-8 text-center">Gallery</h1>

            <div v-if="products.length === 0" class="text-center text-gray-500 mt-20">
                <p class="text-xl">No artwork yet.</p>
            </div>

            <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <button
                    v-for="product in products"
                    :key="product.id"
                    class="group bg-white rounded-xl shadow hover:shadow-lg transition-shadow overflow-hidden text-left focus:outline-none focus-visible:ring-2 focus-visible:ring-black"
                    @click="openModal(product)"
                >
                    <!-- Main image -->
                    <div class="aspect-square overflow-hidden bg-gray-100">
                        <img
                            v-if="product.thumbnail"
                            :src="product.thumbnail"
                            :alt="product.name"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                        />
                        <div v-else class="w-full h-full flex items-center justify-center text-gray-400 text-sm">
                            No image
                        </div>
                    </div>

                    <!-- Card footer -->
                    <div class="px-4 py-3 flex items-center justify-between">
                        <p class="font-medium text-sm truncate">{{ product.name }}</p>
                        <span class="text-xs text-gray-400 ml-2 flex-shrink-0">
                            {{ product.images.length }} {{ product.images.length === 1 ? 'photo' : 'photos' }}
                        </span>
                    </div>
                </button>
            </div>
        </div>

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
                    <!-- Backdrop -->
                    <div class="absolute inset-0 bg-black/80" @click="closeModal" />

                    <!-- Panel -->
                    <div class="relative z-10 w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

                        <!-- Header -->
                        <div class="flex items-center justify-between px-6 py-4 border-b flex-shrink-0">
                            <div>
                                <h2 class="text-lg font-semibold">{{ modal.name }}</h2>
                                <p class="text-xs text-gray-400">{{ activeIndex + 1 }} / {{ modal.images.length }}</p>
                            </div>
                            <button
                                @click="closeModal"
                                class="rounded-full p-2 hover:bg-gray-100 transition-colors"
                                aria-label="Close"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Main image -->
                        <div class="relative flex-1 bg-gray-100 flex items-center justify-center overflow-hidden min-h-0">
                            <img
                                :src="modal.images[activeIndex]"
                                :alt="modal.name"
                                class="max-h-full max-w-full object-contain"
                            />

                            <!-- Prev -->
                            <button
                                v-if="modal.images.length > 1"
                                @click="prev"
                                class="absolute left-3 rounded-full bg-black/40 hover:bg-black/60 text-white p-2 transition-colors"
                                aria-label="Previous"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>

                            <!-- Next -->
                            <button
                                v-if="modal.images.length > 1"
                                @click="next"
                                class="absolute right-3 rounded-full bg-black/40 hover:bg-black/60 text-white p-2 transition-colors"
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
                                class="flex-shrink-0 w-14 h-14 rounded-lg overflow-hidden border-2 transition-colors"
                                :class="activeIndex === i ? 'border-black' : 'border-transparent opacity-60 hover:opacity-100'"
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
