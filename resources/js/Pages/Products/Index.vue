<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    products: Array,
});

const formatCurrency = (value) =>
    new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(Number(value || 0));

const destroyProduct = (product) => {
    if (!confirm(`Delete ${product.title}?`)) {
        return;
    }

    router.delete(route('products.destroy', product.id));
};
</script>

<template>
    <Head title="Products" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">Products</h2>
                    <p class="mt-1 text-sm text-gray-600">Track product cost, pricing, stock, and listing status.</p>
                </div>
                <Link
                    :href="route('products.create')"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500"
                >
                    Add product
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div v-if="products.length" class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Product</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Supplier</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Cost</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Target</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Stock</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="product in products" :key="product.id">
                                        <td class="px-4 py-4">
                                            <div class="font-medium text-gray-900">{{ product.title }}</div>
                                            <div class="text-sm text-gray-500">{{ product.category || 'Uncategorized' }}</div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-700">{{ product.supplier_name || 'Manual source' }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-700">{{ formatCurrency(product.cost) }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-700">{{ formatCurrency(product.target_price) }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-700">{{ product.stock_quantity }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-700">{{ product.listing_status }}</td>
                                        <td class="px-4 py-4 text-right text-sm">
                                            <Link :href="route('products.edit', product.id)" class="mr-4 font-medium text-indigo-600 hover:text-indigo-500">Edit</Link>
                                            <button class="font-medium text-red-600 hover:text-red-500" @click="destroyProduct(product)">Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div v-else class="rounded-lg border border-dashed border-gray-300 p-8 text-center">
                            <h3 class="text-base font-semibold text-gray-900">No products yet</h3>
                            <p class="mt-2 text-sm text-gray-600">Add your first product to start tracking margin and stock.</p>
                            <Link
                                :href="route('products.create')"
                                class="mt-4 inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500"
                            >
                                Create product
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
