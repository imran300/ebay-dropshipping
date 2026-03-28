<script setup>
import { computed } from 'vue';
import {
    getDashboardCardClasses,
    mergeDashboardCardClasses,
    safeShouldRenderDashboardCardTitle,
} from '@/Components/DashboardCardHelpers';

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

const classes = computed(() => {
    try {
        return mergeDashboardCardClasses(
            getDashboardCardClasses(props.variant),
            props.variant,
        );
    } catch {
        return mergeDashboardCardClasses(null, props.variant);
    }
});

const showTitle = computed(() => safeShouldRenderDashboardCardTitle(props.title));
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
