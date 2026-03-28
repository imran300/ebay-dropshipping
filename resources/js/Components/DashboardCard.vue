<script setup>
import { computed } from 'vue';

const props = defineProps({
    title: {
        type: String,
        default: '',
    },
    variant: {
        type: String,
        default: 'stat',
        validator: (value) => ['stat', 'panel'].includes(value),
    },
});

const rootClasses = computed(() =>
    props.variant === 'panel'
        ? 'overflow-hidden bg-blue-100 shadow-sm ring-1 ring-blue-200 sm:rounded-lg'
        : 'rounded-lg bg-blue-100 p-6 shadow-sm ring-1 ring-blue-200'
);

const bodyClasses = computed(() =>
    props.variant === 'panel' ? 'divide-y divide-gray-100' : ''
);

const titleClasses = computed(() =>
    props.variant === 'panel'
        ? 'border-b border-gray-200 px-6 py-4'
        : 'mb-2 text-sm text-gray-500'
);
</script>

<template>
    <div :class="rootClasses">
        <div v-if="title" :class="titleClasses">
            <h3
                :class="
                    variant === 'panel'
                        ? 'text-base font-semibold text-gray-900'
                        : ''
                "
            >
                {{ title }}
            </h3>
        </div>
        <div :class="bodyClasses">
            <slot />
        </div>
    </div>
</template>
