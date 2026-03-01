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
    commission_type: '',
    subject: '',
    size: '',
    budget: '',
    timeframe: '',
    details: '',
    reference_link: '',
});

const sending = ref(false);

function submit() {
    sending.value = true;
    router.post('/commissions', form.value, {
        onSuccess: () => {
            form.value = {
                name: '',
                email: '',
                commission_type: '',
                subject: '',
                size: '',
                budget: '',
                timeframe: '',
                details: '',
                reference_link: '',
            };
        },
        onFinish: () => {
            sending.value = false;
        },
    });
}

const inputClass =
    'w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm transition focus:outline-none focus:ring-2 focus:ring-rose-400';
const labelClass = 'mb-1.5 block text-xs font-semibold tracking-[0.15em] text-gray-400 uppercase';
const errorClass = 'mt-1 text-xs text-red-500';

const steps = [
    {
        number: '01',
        title: 'Send an Enquiry',
        body: 'Fill in the form below with as much detail as you can — the subject, size, any reference images, and your budget. The more I know, the better I can bring your vision to life.',
    },
    {
        number: '02',
        title: 'We Chat It Through',
        body: "I'll get back to you within a few days to discuss your idea, confirm the details, and give you a final quote. I take on a limited number of commissions each month so spaces are precious.",
    },
    {
        number: '03',
        title: 'Deposit & Work Begins',
        body: "Once we're both happy, a 50% deposit secures your slot. I'll keep you updated with progress photos throughout so you can see your piece coming to life.",
    },
    {
        number: '04',
        title: 'Final Payment & Delivery',
        body: "When the painting is complete and you're delighted with it, the remaining balance is due. Your artwork is then carefully packaged and sent on its way to you.",
    },
];

const commissionTypes = [
    'Pet Portrait',
    'Wildlife / Animal',
    'Landscape',
    'Glow in the Dark',
    'Fan Art',
    'Abstract',
    'Other',
];
</script>

<template>
    <Head title="Commissions" />

    <GuestLayout title="Commissions" :full-width="true">
        <!-- Hero -->
        <div
            class="relative overflow-hidden px-6 py-16 text-center"
            style="background: linear-gradient(135deg, #6b2737 0%, #a84a5a 40%, #c46b72 70%, #8b3a4a 100%)"
        >
            <div
                class="absolute inset-0 opacity-10"
                style="background-image: radial-gradient(circle, #ffffff 1px, transparent 1px); background-size: 32px 32px"
            />
            <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-rose-300/20 blur-[80px]" />
            <div class="relative">
                <p class="mb-3 text-xs font-medium tracking-[0.3em] text-rose-200 uppercase">Made for you</p>
                <h1 class="text-4xl font-bold text-white sm:text-5xl">Commissions</h1>
                <p class="mt-3 text-sm text-white/75">A one-of-a-kind piece created just for you.</p>
            </div>
        </div>

        <div class="bg-gray-100">
            <!-- Intro -->
            <div class="mx-auto max-w-3xl px-6 py-16">
                <p class="mb-1 text-xs font-medium tracking-[0.25em] text-gray-400 uppercase">The process</p>
                <h2 class="mb-5 text-3xl font-bold text-gray-900">How it works</h2>
                <p class="mb-12 text-base leading-relaxed text-gray-600">
                    I love bringing your ideas to life — whether it's a beloved pet, a favourite landscape, or something that just sparks joy.
                    Commissions are taken on a limited basis each month so I can give every piece the attention it deserves.
                    Here's what to expect from start to finish.
                </p>

                <!-- Process steps -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div
                        v-for="step in steps"
                        :key="step.number"
                        class="rounded-2xl bg-white p-6 shadow-sm"
                    >
                        <span
                            class="mb-4 inline-block text-2xl font-bold"
                            style="background: linear-gradient(135deg, #6b2737, #c46b72); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text"
                        >{{ step.number }}</span>
                        <h3 class="mb-2 text-base font-bold text-gray-900">{{ step.title }}</h3>
                        <p class="text-sm leading-relaxed text-gray-500">{{ step.body }}</p>
                    </div>
                </div>
            </div>

            <!-- Enquiry form -->
            <div class="border-t border-gray-200 bg-white">
                <div class="mx-auto max-w-2xl px-6 py-16">
                    <p class="mb-1 text-xs font-medium tracking-[0.25em] text-gray-400 uppercase">Get started</p>
                    <h2 class="mb-8 text-3xl font-bold text-gray-900">Commission Enquiry</h2>

                    <div v-if="success" class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-800">
                        {{ success }}
                    </div>

                    <form @submit.prevent="submit" class="space-y-5">
                        <!-- Name + Email -->
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                            <div>
                                <label :class="labelClass">Your name</label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    required
                                    placeholder="Jane Smith"
                                    :class="[inputClass, errors.name ? 'border-red-400' : '']"
                                />
                                <p v-if="errors.name" :class="errorClass">{{ errors.name }}</p>
                            </div>
                            <div>
                                <label :class="labelClass">Email</label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    required
                                    placeholder="jane@example.com"
                                    :class="[inputClass, errors.email ? 'border-red-400' : '']"
                                />
                                <p v-if="errors.email" :class="errorClass">{{ errors.email }}</p>
                            </div>
                        </div>

                        <!-- Type -->
                        <div>
                            <label :class="labelClass">Type of commission</label>
                            <select
                                v-model="form.commission_type"
                                required
                                :class="[inputClass, errors.commission_type ? 'border-red-400' : '']"
                            >
                                <option value="" disabled>Select a type…</option>
                                <option v-for="type in commissionTypes" :key="type" :value="type">{{ type }}</option>
                            </select>
                            <p v-if="errors.commission_type" :class="errorClass">{{ errors.commission_type }}</p>
                        </div>

                        <!-- Subject -->
                        <div>
                            <label :class="labelClass">Subject / description</label>
                            <input
                                v-model="form.subject"
                                type="text"
                                required
                                placeholder="e.g. My golden retriever Biscuit"
                                :class="[inputClass, errors.subject ? 'border-red-400' : '']"
                            />
                            <p v-if="errors.subject" :class="errorClass">{{ errors.subject }}</p>
                        </div>

                        <!-- Size + Budget -->
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                            <div>
                                <label :class="labelClass">Desired size <span class="normal-case text-gray-300">(optional)</span></label>
                                <input
                                    v-model="form.size"
                                    type="text"
                                    placeholder="e.g. A4, 30×30cm"
                                    :class="[inputClass, errors.size ? 'border-red-400' : '']"
                                />
                                <p v-if="errors.size" :class="errorClass">{{ errors.size }}</p>
                            </div>
                            <div>
                                <label :class="labelClass">Budget <span class="normal-case text-gray-300">(optional)</span></label>
                                <input
                                    v-model="form.budget"
                                    type="text"
                                    placeholder="e.g. £100–£150"
                                    :class="[inputClass, errors.budget ? 'border-red-400' : '']"
                                />
                                <p v-if="errors.budget" :class="errorClass">{{ errors.budget }}</p>
                            </div>
                        </div>

                        <!-- Timeframe -->
                        <div>
                            <label :class="labelClass">Timeframe / deadline <span class="normal-case text-gray-300">(optional)</span></label>
                            <input
                                v-model="form.timeframe"
                                type="text"
                                placeholder="e.g. Needed by Christmas, no rush"
                                :class="[inputClass, errors.timeframe ? 'border-red-400' : '']"
                            />
                            <p v-if="errors.timeframe" :class="errorClass">{{ errors.timeframe }}</p>
                        </div>

                        <!-- Reference link -->
                        <div>
                            <label :class="labelClass">Reference image link <span class="normal-case text-gray-300">(optional)</span></label>
                            <input
                                v-model="form.reference_link"
                                type="url"
                                placeholder="https://…"
                                :class="[inputClass, errors.reference_link ? 'border-red-400' : '']"
                            />
                            <p v-if="errors.reference_link" :class="errorClass">{{ errors.reference_link }}</p>
                            <p class="mt-1 text-xs text-gray-400">Link to a Google Drive, Dropbox, or any image URL.</p>
                        </div>

                        <!-- Details -->
                        <div>
                            <label :class="labelClass">Additional details</label>
                            <textarea
                                v-model="form.details"
                                required
                                rows="5"
                                placeholder="Tell me anything else that will help — colours, mood, what the piece means to you…"
                                :class="[inputClass, 'resize-none', errors.details ? 'border-red-400' : '']"
                            />
                            <p v-if="errors.details" :class="errorClass">{{ errors.details }}</p>
                        </div>

                        <button
                            type="submit"
                            :disabled="sending"
                            class="w-full rounded-xl py-3 text-sm font-semibold text-white transition-opacity"
                            :class="sending ? 'cursor-not-allowed opacity-50' : 'hover:opacity-85'"
                            style="background: linear-gradient(135deg, #6b2737, #c46b72)"
                        >
                            {{ sending ? 'Sending…' : 'Send Enquiry →' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <GuestFooter />
    </GuestLayout>
</template>
