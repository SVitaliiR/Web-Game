<template>
  <div class="absolute mt-2 w-50 border rounded-lg shadow-lg z-20 text-black bg-gray-200 p-2">
        <div class="text-center text-sm font-bold mb-2">
      <span v-if="level < 10">lvl: {{ level }} -> {{ level + 1 }}</span>
      <span v-else>lvl: {{ level }} (Max)</span>
    </div>
    <div class="flex justify-around">
      <button v-if="level < 10" @click="$emit('upgrade')" class="cursor-pointer bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-2 rounded">
        Upgrade
      </button>
      <button @click="$emit('destroy')" class="cursor-pointer bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-2 rounded">
        Destroy
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps({
  level: Number,
});
</script>