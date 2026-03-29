<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    announcementHtml: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <Head title="Buggy Announcement Demo" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Buggy Announcement Demo
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="space-y-4 p-6 text-gray-900">
                        <p class="text-sm text-gray-600">
                            Safe default: request content is escaped before it is
                            displayed.
                        </p>

                        <div
                            class="rounded border border-slate-200 bg-slate-50 p-4 whitespace-pre-wrap"
                        >
                            {{ announcementHtml }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
