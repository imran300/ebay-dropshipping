<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    orders: Array,
});

const page = usePage();

const flash = computed(() => page.props.flash ?? {});

const checkoutOrder = (orderId) => {
    router.post(route('orders.checkout', orderId));
};

const formatAmount = (value) => {
    const amount = Number(value ?? 0);

    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(Number.isNaN(amount) ? 0 : amount);
};
</script>

<template>
    <Head title="Orders" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Orders</h2>
                <p class="mt-1 text-sm text-gray-600">Monitor fulfillment progress, tracking, and exception handling.</p>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div v-if="flash.success || flash.error" class="mb-4 space-y-2">
                    <div v-if="flash.success" class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ flash.success }}
                    </div>
                    <div v-if="flash.error" class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ flash.error }}
                    </div>
                </div>

                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div v-if="orders.length" class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Order</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Product</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Buyer</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Payment</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Sale</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="order in orders" :key="order.id">
                                        <td class="px-4 py-4">
                                            <div class="font-medium text-gray-900">{{ order.order_number || `Order #${order.id}` }}</div>
                                            <div class="text-sm text-gray-500">{{ order.ordered_at || 'Pending date' }}</div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-700">{{ order.product?.title || order.listing?.title || 'Unknown product' }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-700">{{ order.buyer_name || 'Unknown buyer' }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-700">{{ order.fulfillment_status }}</td>
                                        <td class="px-4 py-4 text-sm">
                                            <span
                                                class="rounded-full px-2.5 py-1 text-xs font-medium"
                                                :class="{
                                                    'bg-emerald-100 text-emerald-700': order.payment_status === 'paid',
                                                    'bg-amber-100 text-amber-700': order.payment_status === 'pending',
                                                    'bg-gray-100 text-gray-700': order.payment_status !== 'paid' && order.payment_status !== 'pending',
                                                }"
                                            >
                                                {{ order.payment_status || 'unpaid' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-700">{{ formatAmount(order.sale_price) }}</td>
                                        <td class="px-4 py-4 text-right">
                                            <button
                                                v-if="order.payment_status !== 'paid'"
                                                type="button"
                                                class="inline-flex items-center rounded-md bg-gray-900 px-3 py-2 text-xs font-semibold uppercase tracking-wide text-white transition hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                                                @click="checkoutOrder(order.id)"
                                            >
                                                Pay with Stripe
                                            </button>
                                            <span v-else class="text-xs font-medium uppercase tracking-wide text-emerald-700">Paid</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div v-else class="rounded-lg border border-dashed border-gray-300 p-8 text-center">
                            <h3 class="text-base font-semibold text-gray-900">No orders yet</h3>
                            <p class="mt-2 text-sm text-gray-600">Orders will appear here once you start selling on eBay.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
