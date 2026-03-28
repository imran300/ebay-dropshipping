<script setup>
import { computed } from 'vue';
import {
    getDashboardCardClasses,
    shouldRenderDashboardCardTitle,
} from '@/Components/dashboardCard';

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

const classes = computed(() => getDashboardCardClasses(props.variant));
const showTitle = computed(() => shouldRenderDashboardCardTitle(props.title));
</script>

<template>
    <div :class="classes.rootClasses">
        <div v-if="showTitle" :class="classes.titleClasses">
            <h3 :class="classes.headingClasses">
                {{ title }}
            </h3>
        </div>
        <div :class="classes.bodyClasses">
            <slot />
        </div>
    </div>
</template>
