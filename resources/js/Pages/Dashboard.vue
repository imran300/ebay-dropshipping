<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    stats: Object,
    recent_products: Array,
    recent_listings: Array,
    recent_orders: Array,
});

const formatCurrency = (value) =>
    new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(Number(value || 0));
</script>

<template>
    <Head title="Overview" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Overview</h2>
                <p class="mt-1 text-sm text-gray-600">Track products, listings, orders, and margin in one place.</p>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <div class="text-sm text-gray-500">Products</div>
                        <div class="mt-2 text-3xl font-semibold text-gray-900">{{ stats.products }}</div>
                    </div>
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <div class="text-sm text-gray-500">Active listings</div>
                        <div class="mt-2 text-3xl font-semibold text-gray-900">{{ stats.active_listings }}</div>
                    </div>
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <div class="text-sm text-gray-500">Pending orders</div>
                        <div class="mt-2 text-3xl font-semibold text-gray-900">{{ stats.pending_orders }}</div>
                    </div>
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <div class="text-sm text-gray-500">Potential profit</div>
                        <div class="mt-2 text-3xl font-semibold text-gray-900">{{ formatCurrency(stats.potential_profit) }}</div>
                    </div>
                </div>

                <div class="grid gap-6 xl:grid-cols-3">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h3 class="text-base font-semibold text-gray-900">Recent products</h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            <div v-for="product in recent_products" :key="product.id" class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ product.title }}</div>
                                <div class="text-sm text-gray-500">{{ product.category || 'Uncategorized' }} - {{ formatCurrency(product.target_price) }}</div>
                            </div>
                            <div v-if="!recent_products.length" class="px-6 py-8 text-sm text-gray-500">No products yet.</div>
                        </div>
                    </div>

                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h3 class="text-base font-semibold text-gray-900">Recent listings</h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            <div v-for="listing in recent_listings" :key="listing.id" class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ listing.title }}</div>
                                <div class="text-sm text-gray-500">{{ listing.product?.title || 'Unknown product' }} - {{ listing.status }}</div>
                            </div>
                            <div v-if="!recent_listings.length" class="px-6 py-8 text-sm text-gray-500">No listings yet.</div>
                        </div>
                    </div>

                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h3 class="text-base font-semibold text-gray-900">Recent orders</h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            <div v-for="order in recent_orders" :key="order.id" class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ order.order_number || `Order #${order.id}` }}</div>
                                <div class="text-sm text-gray-500">{{ order.product?.title || 'Unknown product' }} - {{ order.fulfillment_status }}</div>
                            </div>
                            <div v-if="!recent_orders.length" class="px-6 py-8 text-sm text-gray-500">No orders yet.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
