<script setup lang="ts">
import { Sheet, SheetContent, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { useCart } from '@/composables/useCart';
import { urlIsActive } from '@/lib/utils';
import { Link, usePage } from '@inertiajs/vue3';
import { Menu, ShoppingCart } from 'lucide-vue-next';
import { computed } from 'vue';

const page = usePage();

const navItems = [
    { title: 'Home',        href: '/' },
    { title: 'Gallery',     href: '/gallery' },
    { title: 'Shop',        href: '/shop' },
    { title: 'Commissions', href: '/commissions' },
    { title: 'Contact',     href: '/contact' },
];

const isActive = computed(() => (href: string) => urlIsActive(href, page.url));

const { itemCount, openCart } = useCart();
</script>

<template>
    <header class="sticky top-0 z-40 w-full bg-white/95 backdrop-blur-sm shadow-sm">
        <div class="mx-auto flex h-20 items-center justify-between px-6 max-w-7xl">

            <!-- Logo -->
            <Link href="/" class="flex items-center gap-3 group">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl shrink-0"
                     style="background: linear-gradient(135deg, #a84a5a, #c46b72)">
                    <span class="text-white font-bold text-base leading-none">L</span>
                </div>
                <span class="font-bold text-xl tracking-tight text-gray-900 group-hover:text-rose-600 transition-colors">
                    <span class="[word-spacing:-2px]">Lulu T</span> Creates
                </span>
            </Link>

            <!-- Desktop nav -->
            <nav class="hidden lg:flex items-center gap-1">
                <Link
                    v-for="item in navItems"
                    :key="item.href"
                    :href="item.href"
                    class="relative px-4 py-2 text-sm font-medium rounded-lg transition-colors"
                    :class="isActive(item.href)
                        ? 'text-rose-600'
                        : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100'"
                >
                    {{ item.title }}
                    <!-- Active underline -->
                    <span
                        v-if="isActive(item.href)"
                        class="absolute bottom-0 left-4 right-4 h-0.5 rounded-full"
                        style="background: linear-gradient(90deg, #a84a5a, #c46b72)"
                    />
                </Link>
            </nav>

            <!-- Right side -->
            <div class="flex items-center gap-3">
                <!-- Cart -->
                <button
                    @click="openCart"
                    class="relative flex h-10 w-10 items-center justify-center rounded-xl text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors"
                    aria-label="Open cart"
                >
                    <ShoppingCart class="h-5 w-5" />
                    <span
                        v-if="itemCount > 0"
                        class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full text-[10px] font-bold text-white"
                        style="background: linear-gradient(135deg, #a84a5a, #c46b72)"
                    >{{ itemCount }}</span>
                </button>

                <!-- Mobile menu trigger -->
                <div class="lg:hidden">
                    <Sheet>
                        <SheetTrigger as-child>
                            <button class="flex h-10 w-10 items-center justify-center rounded-xl text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                                <Menu class="h-5 w-5" />
                            </button>
                        </SheetTrigger>
                        <SheetContent side="left" class="w-72 p-0">
                            <SheetTitle class="sr-only">Navigation</SheetTitle>

                            <!-- Drawer header -->
                            <div class="flex items-center gap-3 px-6 py-5 border-b">
                                <div class="flex h-9 w-9 items-center justify-center rounded-xl shrink-0"
                                     style="background: linear-gradient(135deg, #a84a5a, #c46b72)">
                                    <span class="text-white font-bold text-sm">L</span>
                                </div>
                                <span class="font-bold text-lg text-gray-900">Lulu T Creates</span>
                            </div>

                            <nav class="flex flex-col px-3 py-4 gap-1">
                                <Link
                                    v-for="item in navItems"
                                    :key="item.href"
                                    :href="item.href"
                                    class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors"
                                    :class="isActive(item.href)
                                        ? 'bg-rose-50 text-rose-600'
                                        : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900'"
                                >
                                    {{ item.title }}
                                </Link>
                            </nav>
                        </SheetContent>
                    </Sheet>
                </div>
            </div>

        </div>
    </header>
</template>
