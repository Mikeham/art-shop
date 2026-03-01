<script setup lang="ts">
import { Sheet, SheetContent, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { useCart } from '@/composables/useCart';
import { urlIsActive } from '@/lib/utils';
import { Link, usePage } from '@inertiajs/vue3';
import { Menu, ShoppingCart } from 'lucide-vue-next';
import { computed } from 'vue';

const page = usePage();

const navItems = [
    { title: 'Home', href: '/' },
    { title: 'Gallery', href: '/gallery' },
    { title: 'Shop', href: '/shop' },
    { title: 'Commissions', href: '/commissions' },
    { title: 'Contact', href: '/contact' },
];

const isActive = computed(() => (href: string) => urlIsActive(href, page.url));

const { itemCount, openCart } = useCart();
</script>

<template>
    <header class="sticky top-0 z-40 w-full bg-white/95 shadow-sm backdrop-blur-sm">
        <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-6">
            <!-- Logo -->
            <Link href="/" class="group flex items-center gap-3">
                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl"
                    style="background: linear-gradient(135deg, #a84a5a, #c46b72)"
                >
                    <span class="text-base leading-none font-bold text-white">L</span>
                </div>
                <span class="text-xl font-bold tracking-tight text-gray-900 transition-colors group-hover:text-rose-600">
                    <span class="[word-spacing:-2px]">Lulu T</span> Creates
                </span>
            </Link>

            <!-- Desktop nav -->
            <nav class="hidden items-center gap-1 lg:flex">
                <Link
                    v-for="item in navItems"
                    :key="item.href"
                    :href="item.href"
                    class="relative rounded-lg px-4 py-2 text-sm font-medium transition-colors"
                    :class="isActive(item.href) ? 'text-rose-600' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'"
                >
                    {{ item.title }}
                    <!-- Active underline -->
                    <span
                        v-if="isActive(item.href)"
                        class="absolute right-4 bottom-0 left-4 h-0.5 rounded-full"
                        style="background: linear-gradient(90deg, #a84a5a, #c46b72)"
                    />
                </Link>
            </nav>

            <!-- Right side -->
            <div class="flex items-center gap-3">
                <!-- Cart -->
                <button
                    @click="openCart"
                    class="relative flex h-10 w-10 items-center justify-center rounded-xl text-gray-600 transition-colors hover:bg-gray-100 hover:text-gray-900"
                    aria-label="Open cart"
                >
                    <ShoppingCart class="h-5 w-5" />
                    <span
                        v-if="itemCount > 0"
                        class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full text-[10px] font-bold text-white"
                        style="background: linear-gradient(135deg, #a84a5a, #c46b72)"
                        >{{ itemCount }}</span
                    >
                </button>

                <!-- Mobile menu trigger -->
                <div class="lg:hidden">
                    <Sheet>
                        <SheetTrigger as-child>
                            <button
                                class="flex h-10 w-10 items-center justify-center rounded-xl text-gray-600 transition-colors hover:bg-gray-100 hover:text-gray-900"
                            >
                                <Menu class="h-5 w-5" />
                            </button>
                        </SheetTrigger>
                        <SheetContent side="left" class="w-72 p-0">
                            <SheetTitle class="sr-only">Navigation</SheetTitle>

                            <!-- Drawer header -->
                            <div class="flex items-center gap-3 border-b px-6 py-5">
                                <div
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl"
                                    style="background: linear-gradient(135deg, #a84a5a, #c46b72)"
                                >
                                    <span class="text-sm font-bold text-white">L</span>
                                </div>
                                <span class="text-lg font-bold text-gray-900">Lulu T Creates</span>
                            </div>

                            <nav class="flex flex-col gap-1 px-3 py-4">
                                <Link
                                    v-for="item in navItems"
                                    :key="item.href"
                                    :href="item.href"
                                    class="flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition-colors"
                                    :class="isActive(item.href) ? 'bg-rose-50 text-rose-600' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900'"
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
