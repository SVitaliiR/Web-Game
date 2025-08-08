<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue';
import { Head } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <Head :title="title" />

    <div class="flex min-h-screen flex-col items-center bg-background p-4 pt-16 sm:justify-center sm:pt-0">
        <div class="w-full max-w-md">
            <div class="mb-8 flex justify-center">
                <AppLogo class="h-10" />
            </div>

            <div class="mb-6 text-center">
                <h1 v-if="title" class="text-2xl font-bold tracking-tight">{{ title }}</h1>
                <p v-if="description" class="text-muted-foreground">{{ description }}</p>
            </div>

            <div class="rounded-lg border bg-card p-6 text-card-foreground shadow-sm">
                <slot />
            </div>
        </div>
    </div>
</template>