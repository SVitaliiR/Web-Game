<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

import { ref, computed } from 'vue';


const isClicked = ref(false);
const fillDuration = computed(() => {
    switch (selectedResource.value) {
        case 'rock':
        default:
            return 1000; // 1 second
        case 'wood':
            return 2000; // 2 seconds
        case 'food':
            return 3500; // 3.5 seconds
        case 'gold':
            return 7000; // 7 seconds
    }
});

function handleClick() {
    isClicked.value = true;
    setTimeout(() => {
        isClicked.value = false;
    }, fillDuration.value);
}


const resources = [
  { value: 'rock', label: 'Rock', img: '/images/rock.png', count: 0 },
  { value: 'wood', label: 'Wood', img: '/images/wood.svg', count: 0 },
  { value: 'food', label: 'Food', img: '/images/food.png', count: 0 },
  { value: 'gold', label: 'Gold', img: '/images/gold.png', count: 0 },
];

const dropdownOpen = ref(false);

function selectResource(resource) {
  selectedResource.value = resource.value;
  dropdownOpen.value = false;
}

const selectedResource = ref('rock');

const resourceStyles = computed(() => {
    switch (selectedResource.value) {
        case 'wood':
            return {
                button: 'border-5 border-yellow-900 text-yellow-900 hover:bg-yellow-900/10',
                select: 'border-yellow-900 text-yellow-900 focus:ring-yellow-900',
            };
        case 'food':
            return {
                button: 'border-5 border-red-600 text-red-600 hover:bg-red-500/10',
                select: 'border-red-500 text-red-500 focus:ring-red-500',
            };
        case 'gold':
            return {
                button: 'border-5 border-yellow-500 text-yellow-500 hover:bg-yellow-100/10',
                select: 'border-yellow-400 text-yellow-500 focus:ring-yellow-400',
            };
        case 'rock':
        default:
            return {
                button: 'border-5 border-gray-400 text-gray-500 hover:bg-gray-200/10',
                select: 'border-gray-400 text-gray-500 focus:ring-gray-400',
            };
    }
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 gap-4 flex-col rounded-xl relative"
            style="background-image: url('data:image/svg+xml;utf8,<svg width=\"60\" height=\"69.28\" viewBox=\"0 0 60 69.28\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"><polygon points=\"30,0 60,17.32 60,51.96 30,69.28 0,51.96 0,17.32\" fill=\"%23f3f4f6\" stroke=\"%23d1d5db\" stroke-width=\"2\" /></svg>'); background-size: 60px 69.28px;"
        >
            <!-- Resource images in top right corner -->
            <div class="absolute top-4 right-4 flex flex-col gap-2 z-20">
                <div v-for="resource in resources" :key="resource.value + '-top'" class="flex items-center gap-1">
                    <span class="text-xs font-bold text-white-700 min-w-[18px] text-right">{{ resource.count }}</span>
                    <img :src="resource.img" :alt="resource.label" class="w-7 h-7 rounded-full border border-gray-200 shadow bg-white" />
                </div>
            </div>
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
               
            </div>
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
            </div>
            <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 z-10 flex items-center gap-4">
                <button
                    class="w-30 h-30 rounded-full shadow relative overflow-hidden transition-all duration-500 flex items-center justify-center text-2xl"
                    :class="resourceStyles.button"
                    @click="handleClick"
                >
                    <span
                        class="absolute left-0 bottom-0 w-full bg-white z-0"
                        :class="isClicked ? 'transition-all' : ''"
                        :style="{
                            height: isClicked ? '100%' : '0%',
                            transitionDuration: isClicked ? fillDuration + 'ms' : '0ms'
                        }"
                    ></span>
                    <span class="relative z-10">Click</span>
                </button>
                <div class="relative">
                  <button
                    @click="dropdownOpen = !dropdownOpen"
                    class="h-12 w-12 rounded-full border flex items-center justify-center bg-white font-semibold transition focus:outline-none"
                    :class="resourceStyles.select"
                    type="button"
                  >
                    <img
                      :src="resources.find(r => r.value === selectedResource)?.img"
                      alt=""
                      class="w-10 h-10 rounded-full"
                    />
                    <svg class="w-4 h-4 absolute right-1 top-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </button>
                  <div
                    v-if="dropdownOpen"
                    class="absolute left-0 bottom-10 mb-1 w-full bg-white border rounded-lg shadow z-20"
                  >
                    <ul class="flex flex-col items-center py-2">
                      <li
                        v-for="resource in resources"
                        :key="resource.value"
                        @click="selectResource(resource)"
                        class="cursor-pointer hover:bg-gray-100 rounded-full p-1 mb-1"
                      >
                        <img :src="resource.img" alt="" class="w-10 h-10 rounded-full" />
                      </li>
                    </ul>
                  </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
