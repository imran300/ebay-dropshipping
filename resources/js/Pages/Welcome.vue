<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Welcome" />

    <div class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0">
        <div class="mb-6 text-2xl font-semibold text-gray-800">
            Laravel
        </div>

        <div class="w-full max-w-md overflow-hidden bg-white px-6 py-8 shadow-md sm:rounded-lg">
            <div class="flex items-center justify-center gap-4">
                <Link
                    :href="route('login')"
                    class="rounded-md bg-gray-800 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-700"
                >
                    Log in
                </Link>
                <Link
                    v-if="route().has('register')"
                    :href="route('register')"
                    class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    Register
                </Link>
            </div>
        </div>
    </div>
</template>
