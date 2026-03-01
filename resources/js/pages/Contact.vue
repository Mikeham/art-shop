<script setup lang="ts">
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

    <GuestLayout title="Contact">
        <div class="py-16 max-w-xl mx-auto px-4">
            <h1 class="text-3xl font-semibold mb-2 text-center">Get in touch</h1>
            <p class="text-center text-gray-500 mb-10 text-sm">Have a question or commission enquiry? Send me a message.</p>

            <!-- Success banner -->
            <div v-if="success" class="mb-6 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                {{ success }}
            </div>

            <form @submit.prevent="submit" class="bg-white rounded-xl shadow p-8 space-y-5">

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input
                        v-model="form.name"
                        type="text"
                        required
                        placeholder="Jane Smith"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black"
                        :class="{ 'border-red-500': errors.name }"
                    />
                    <p v-if="errors.name" class="mt-1 text-xs text-red-600">{{ errors.name }}</p>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        required
                        placeholder="jane@example.com"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black"
                        :class="{ 'border-red-500': errors.email }"
                    />
                    <p v-if="errors.email" class="mt-1 text-xs text-red-600">{{ errors.email }}</p>
                </div>

                <!-- Subject -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                    <input
                        v-model="form.subject"
                        type="text"
                        required
                        placeholder="Commission enquiry"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black"
                        :class="{ 'border-red-500': errors.subject }"
                    />
                    <p v-if="errors.subject" class="mt-1 text-xs text-red-600">{{ errors.subject }}</p>
                </div>

                <!-- Message -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                    <textarea
                        v-model="form.message"
                        required
                        rows="6"
                        placeholder="Tell me more..."
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black resize-none"
                        :class="{ 'border-red-500': errors.message }"
                    />
                    <p v-if="errors.message" class="mt-1 text-xs text-red-600">{{ errors.message }}</p>
                </div>

                <button
                    type="submit"
                    :disabled="sending"
                    class="w-full rounded-lg bg-black py-3 text-sm font-medium text-white transition-opacity"
                    :class="sending ? 'opacity-50 cursor-not-allowed' : 'hover:opacity-80'"
                >
                    {{ sending ? 'Sending…' : 'Send Message' }}
                </button>
            </form>
        </div>
    </GuestLayout>
</template>
