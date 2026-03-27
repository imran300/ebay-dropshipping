<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    sku: '',
    category: '',
    supplier_name: '',
    source_url: '',
    cost: '',
    target_price: '',
    stock_quantity: 0,
    listing_status: 'draft',
    notes: '',
});

const submit = () => {
    form.post(route('products.store'));
};
</script>

<template>
    <Head title="Create product" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Create product</h2>
                <p class="mt-1 text-sm text-gray-600">Capture the product details you need to estimate margin and track stock.</p>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <form class="space-y-6 p-6" @submit.prevent="submit">
                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <InputLabel for="title" value="Title" />
                                <TextInput id="title" v-model="form.title" type="text" class="mt-1 block w-full" required />
                                <InputError class="mt-2" :message="form.errors.title" />
                            </div>
                            <div>
                                <InputLabel for="sku" value="SKU" />
                                <TextInput id="sku" v-model="form.sku" type="text" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.sku" />
                            </div>
                            <div>
                                <InputLabel for="category" value="Category" />
                                <TextInput id="category" v-model="form.category" type="text" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.category" />
                            </div>
                            <div>
                                <InputLabel for="supplier_name" value="Supplier" />
                                <TextInput id="supplier_name" v-model="form.supplier_name" type="text" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.supplier_name" />
                            </div>
                            <div>
                                <InputLabel for="source_url" value="Source URL" />
                                <TextInput id="source_url" v-model="form.source_url" type="url" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.source_url" />
                            </div>
                            <div>
                                <InputLabel for="listing_status" value="Listing status" />
                                <select id="listing_status" v-model="form.listing_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="draft">Draft</option>
                                    <option value="ready">Ready</option>
                                    <option value="active">Active</option>
                                    <option value="paused">Paused</option>
                                    <option value="sold">Sold</option>
                                    <option value="archived">Archived</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.listing_status" />
                            </div>
                            <div>
                                <InputLabel for="cost" value="Cost" />
                                <TextInput id="cost" v-model="form.cost" type="number" min="0" step="0.01" class="mt-1 block w-full" required />
                                <InputError class="mt-2" :message="form.errors.cost" />
                            </div>
                            <div>
                                <InputLabel for="target_price" value="Target price" />
                                <TextInput id="target_price" v-model="form.target_price" type="number" min="0" step="0.01" class="mt-1 block w-full" required />
                                <InputError class="mt-2" :message="form.errors.target_price" />
                            </div>
                            <div>
                                <InputLabel for="stock_quantity" value="Stock quantity" />
                                <TextInput id="stock_quantity" v-model="form.stock_quantity" type="number" min="0" step="1" class="mt-1 block w-full" required />
                                <InputError class="mt-2" :message="form.errors.stock_quantity" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="notes" value="Notes" />
                            <textarea
                                id="notes"
                                v-model="form.notes"
                                rows="5"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                            <InputError class="mt-2" :message="form.errors.notes" />
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <Link :href="route('products.index')" class="text-sm font-medium text-gray-600 hover:text-gray-900">Cancel</Link>
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Save product</PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
