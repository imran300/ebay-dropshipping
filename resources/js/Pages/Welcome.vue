<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Ebay Dropshipping" />

    <div class="min-h-screen bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(79,70,229,0.28),_transparent_42%),radial-gradient(circle_at_bottom_right,_rgba(14,165,233,0.22),_transparent_30%)]"></div>
        <div class="relative mx-auto flex min-h-screen max-w-7xl flex-col px-6 py-8 lg:px-8">
            <header class="flex items-center justify-between">
                <div class="text-lg font-semibold tracking-tight">Dropship Control</div>
                <div class="flex items-center gap-3">
                    <Link
                        :href="route('login')"
                        class="rounded-md px-4 py-2 text-sm font-medium text-slate-200 transition hover:bg-white/10"
                    >
                        Log in
                    </Link>
                    <Link
                        v-if="route().has('register')"
                        :href="route('register')"
                        class="rounded-md bg-indigo-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-400"
                    >
                        Register
                    </Link>
                </div>
            </header>

            <main class="flex flex-1 flex-col justify-center py-20">
                <div class="grid gap-12 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
                    <div class="max-w-3xl">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-300">eBay dropshipping platform</p>
                        <h1 class="mt-4 text-5xl font-semibold tracking-tight text-white sm:text-6xl">
                            Increase sales with a system for products, listings, orders, and margin.
                        </h1>
                        <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">
                            Track what you can sell, what is live on eBay, what is pending fulfillment, and where the profit is actually coming from.
                        </p>

                        <div class="mt-8 flex flex-wrap gap-4">
                            <Link
                                :href="route('dashboard')"
                                class="rounded-md bg-white px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-slate-200"
                            >
                                Open dashboard
                            </Link>
                            <a
                                href="#features"
                                class="rounded-md border border-white/15 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/5"
                            >
                                See features
                            </a>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/5 p-6 shadow-2xl shadow-black/30 backdrop-blur">
                        <div class="rounded-xl bg-slate-900/80 p-5">
                            <div class="text-sm text-slate-400">Today at a glance</div>
                            <div class="mt-5 grid grid-cols-2 gap-4">
                                <div class="rounded-xl bg-white/5 p-4">
                                    <div class="text-xs uppercase tracking-wide text-slate-400">Products</div>
                                    <div class="mt-2 text-2xl font-semibold">Catalog</div>
                                </div>
                                <div class="rounded-xl bg-white/5 p-4">
                                    <div class="text-xs uppercase tracking-wide text-slate-400">Listings</div>
                                    <div class="mt-2 text-2xl font-semibold">Live</div>
                                </div>
                                <div class="rounded-xl bg-white/5 p-4">
                                    <div class="text-xs uppercase tracking-wide text-slate-400">Orders</div>
                                    <div class="mt-2 text-2xl font-semibold">Queue</div>
                                </div>
                                <div class="rounded-xl bg-white/5 p-4">
                                    <div class="text-xs uppercase tracking-wide text-slate-400">Margin</div>
                                    <div class="mt-2 text-2xl font-semibold">Track</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <section id="features" class="mt-20 grid gap-6 md:grid-cols-3">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                        <h2 class="text-lg font-semibold">Product catalog</h2>
                        <p class="mt-3 text-sm leading-6 text-slate-300">
                            Keep product cost, target price, stock, supplier, and listing status in one place.
                        </p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                        <h2 class="text-lg font-semibold">Listing workflow</h2>
                        <p class="mt-3 text-sm leading-6 text-slate-300">
                            Turn products into drafts, active listings, and sold items without losing track of revisions.
                        </p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                        <h2 class="text-lg font-semibold">Orders and margin</h2>
                        <p class="mt-3 text-sm leading-6 text-slate-300">
                            Monitor sales, fulfillment, and profit so you can double down on the items that move.
                        </p>
                    </div>
                </section>
            </main>
        </div>
    </div>
</template>
