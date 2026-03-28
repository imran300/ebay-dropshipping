<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

/**
 * @typedef {Object} SettingsData
 * @property {number} ebay_fee_rate
 * @property {number} default_shipping_cost
 * @property {number} low_stock_threshold
 * @property {number} min_margin_threshold
 */

const defaultSettings = {
    ebay_fee_rate: 12.95,
    default_shipping_cost: 0,
    low_stock_threshold: 5,
    min_margin_threshold: 0,
};

const props = defineProps({
    settings: {
        type: Object,
        default: () => ({
            ebay_fee_rate: 12.95,
            default_shipping_cost: 0,
            low_stock_threshold: 5,
            min_margin_threshold: 0,
        }),
        validator: (settings) => {
            if (settings === null || typeof settings !== 'object' || Array.isArray(settings)) {
                return false;
            }

            return [
                'ebay_fee_rate',
                'default_shipping_cost',
                'low_stock_threshold',
                'min_margin_threshold',
            ].every((key) => typeof settings[key] === 'number');
        },
    },
});

const form = useForm({
    ...defaultSettings,
    ...(props.settings ?? {}),
});

const page = usePage();
const flash = computed(() => page.props.flash ?? {});

const submit = () => {
    form.post(route('settings.store'));
};
</script>

<template>
    <Head title="Settings" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Settings</h2>
                <p class="mt-1 text-sm text-gray-600">Tune fee, shipping, stock, and margin assumptions for your store.</p>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div v-if="flash.success || flash.error" class="mb-4 space-y-2">
                    <div v-if="flash.success" class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ flash.success }}
                    </div>
                    <div v-if="flash.error" class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ flash.error }}
                    </div>
                </div>

                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <form class="space-y-6 p-6" @submit.prevent="submit">
                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <InputLabel for="ebay_fee_rate" value="eBay fee rate (%)" />
                                <TextInput id="ebay_fee_rate" v-model="form.ebay_fee_rate" type="number" min="0" max="100" step="0.01" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.ebay_fee_rate" />
                            </div>

                            <div>
                                <InputLabel for="default_shipping_cost" value="Default shipping cost" />
                                <TextInput id="default_shipping_cost" v-model="form.default_shipping_cost" type="number" min="0" step="0.01" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.default_shipping_cost" />
                            </div>

                            <div>
                                <InputLabel for="low_stock_threshold" value="Low stock threshold" />
                                <TextInput id="low_stock_threshold" v-model="form.low_stock_threshold" type="number" min="1" step="1" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.low_stock_threshold" />
                            </div>

                            <div>
                                <InputLabel for="min_margin_threshold" value="Minimum margin threshold" />
                                <TextInput id="min_margin_threshold" v-model="form.min_margin_threshold" type="number" min="0" step="0.01" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.min_margin_threshold" />
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Save settings
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
