<script setup>
import DashboardCard from '@/Components/DashboardCard.vue';
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
                    <DashboardCard title="Products">
                        <div class="mt-2 text-3xl font-semibold text-gray-900">{{ stats.products }}</div>
                    </DashboardCard>
                    <DashboardCard title="Active listings">
                        <div class="mt-2 text-3xl font-semibold text-gray-900">{{ stats.active_listings }}</div>
                    </DashboardCard>
                    <DashboardCard title="Pending orders">
                        <div class="mt-2 text-3xl font-semibold text-gray-900">{{ stats.pending_orders }}</div>
                    </DashboardCard>
                    <DashboardCard title="Potential profit">
                        <div class="mt-2 text-3xl font-semibold text-gray-900">{{ formatCurrency(stats.potential_profit) }}</div>
                    </DashboardCard>
                </div>

                <div class="grid gap-6 xl:grid-cols-3">
                    <DashboardCard variant="panel" title="Recent products">
                        <div v-for="product in recent_products" :key="product.id" class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ product.title }}</div>
                            <div class="text-sm text-gray-500">{{ product.category || 'Uncategorized' }} - {{ formatCurrency(product.target_price) }}</div>
                        </div>
                        <div v-if="!recent_products.length" class="px-6 py-8 text-sm text-gray-500">No products yet.</div>
                    </DashboardCard>

                    <DashboardCard variant="panel" title="Recent listings">
                        <div v-for="listing in recent_listings" :key="listing.id" class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ listing.title }}</div>
                            <div class="text-sm text-gray-500">{{ listing.product?.title || 'Unknown product' }} - {{ listing.status }}</div>
                        </div>
                        <div v-if="!recent_listings.length" class="px-6 py-8 text-sm text-gray-500">No listings yet.</div>
                    </DashboardCard>

                    <DashboardCard variant="panel" title="Recent orders">
                        <div v-for="order in recent_orders" :key="order.id" class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ order.order_number || `Order #${order.id}` }}</div>
                            <div class="text-sm text-gray-500">{{ order.product?.title || 'Unknown product' }} - {{ order.fulfillment_status }}</div>
                        </div>
                        <div v-if="!recent_orders.length" class="px-6 py-8 text-sm text-gray-500">No orders yet.</div>
                    </DashboardCard>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
