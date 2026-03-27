<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    listings: Array,
});
</script>

<template>
    <Head title="Listings" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Listings</h2>
                <p class="mt-1 text-sm text-gray-600">Track what is drafted, active, paused, and sold on eBay.</p>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div v-if="listings.length" class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Listing</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Product</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Price</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Qty</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="listing in listings" :key="listing.id">
                                        <td class="px-4 py-4">
                                            <div class="font-medium text-gray-900">{{ listing.title }}</div>
                                            <div class="text-sm text-gray-500">{{ listing.marketplace_item_id || 'Not synced' }}</div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-700">{{ listing.product?.title || 'Unknown product' }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-700">${{ listing.price }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-700">{{ listing.quantity }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-700">{{ listing.status }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div v-else class="rounded-lg border border-dashed border-gray-300 p-8 text-center">
                            <h3 class="text-base font-semibold text-gray-900">No listings yet</h3>
                            <p class="mt-2 text-sm text-gray-600">Create products first, then turn them into live eBay listings.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
