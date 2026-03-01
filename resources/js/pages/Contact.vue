<script setup lang="ts">
import GuestFooter from '@/components/GuestFooter.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const page = usePage();
const errors = computed(() => page.props.errors as Record<string, string>);
const success = computed(() => (page.props.flash as Record<string, string> | null)?.success ?? null);

const form = ref({
    name: '',
    email: '',
    subject: '',
    message: '',
});

const sending = ref(false);

function submit() {
    sending.value = true;
    router.post('/contact', form.value, {
        onSuccess: () => {
            form.value = { name: '', email: '', subject: '', message: '' };
        },
        onFinish: () => {
            sending.value = false;
        },
    });
}
</script>

<template>
    <Head title="Contact" />

    <GuestLayout title="Contact" :full-width="true">

        <!-- Hero banner -->
        <div class="relative py-16 px-6 text-center overflow-hidden" style="background: linear-gradient(135deg, #6b2737 0%, #a84a5a 40%, #c46b72 70%, #8b3a4a 100%)">
            <div class="absolute inset-0 opacity-10"
                 style="background-image: radial-gradient(circle, #ffffff 1px, transparent 1px); background-size: 32px 32px;" />
            <div class="absolute -top-24 -left-24 w-72 h-72 rounded-full bg-rose-300/20 blur-[80px]" />
            <div class="relative">
                <p class="text-rose-200 tracking-[0.3em] text-xs uppercase font-medium mb-3">Say hello</p>
                <h1 class="text-4xl sm:text-5xl font-bold text-white">Get in Touch</h1>
                <p class="text-white/75 mt-3 text-sm">Have a question or commission enquiry? Send me a message.</p>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-gray-100 min-h-screen">
            <div class="max-w-xl mx-auto px-6 py-14">

                <!-- Success banner -->
                <div v-if="success" class="mb-6 rounded-2xl bg-green-50 border border-green-200 px-5 py-4 text-sm text-green-800">
                    {{ success }}
                </div>

                <form @submit.prevent="submit" class="bg-white rounded-2xl shadow-sm p-8 space-y-5">

                    <!-- Name -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-[0.15em] text-gray-400 mb-1.5">Name</label>
                        <input
                            v-model="form.name"
                            type="text"
                            required
                            placeholder="Jane Smith"
                            class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 transition"
                            :class="{ 'border-red-400': errors.name }"
                        />
                        <p v-if="errors.name" class="mt-1 text-xs text-red-500">{{ errors.name }}</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-[0.15em] text-gray-400 mb-1.5">Email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            required
                            placeholder="jane@example.com"
                            class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 transition"
                            :class="{ 'border-red-400': errors.email }"
                        />
                        <p v-if="errors.email" class="mt-1 text-xs text-red-500">{{ errors.email }}</p>
                    </div>

                    <!-- Subject -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-[0.15em] text-gray-400 mb-1.5">Subject</label>
                        <input
                            v-model="form.subject"
                            type="text"
                            required
                            placeholder="Commission enquiry"
                            class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 transition"
                            :class="{ 'border-red-400': errors.subject }"
                        />
                        <p v-if="errors.subject" class="mt-1 text-xs text-red-500">{{ errors.subject }}</p>
                    </div>

                    <!-- Message -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-[0.15em] text-gray-400 mb-1.5">Message</label>
                        <textarea
                            v-model="form.message"
                            required
                            rows="6"
                            placeholder="Tell me more..."
                            class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-400 transition resize-none"
                            :class="{ 'border-red-400': errors.message }"
                        />
                        <p v-if="errors.message" class="mt-1 text-xs text-red-500">{{ errors.message }}</p>
                    </div>

                    <button
                        type="submit"
                        :disabled="sending"
                        class="w-full rounded-xl py-3 text-sm font-semibold text-white transition-opacity"
                        :class="sending ? 'opacity-50 cursor-not-allowed' : 'hover:opacity-80'"
                        style="background: black"
                    >
                        {{ sending ? 'Sending…' : 'Send Message' }}
                    </button>

                </form>
            </div>
        </div>

        <GuestFooter />

    </GuestLayout>
</template>
