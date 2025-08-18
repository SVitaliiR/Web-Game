<template>
  <div class="absolute mt-2 w-50 border rounded-lg shadow-lg z-20 text-black bg-gray-200 p-2">
    <div class="text-center text-sm font-bold mb-2">lvl: 0 -> 1</div>
    <div class="flex justify-around">
      <button @click="$emit('upgrade')" class="cursor-pointer bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-2 rounded">
        Upgrade
      </button>
      <button @click="$emit('destroy')" class="cursor-pointer bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-2 rounded">
        Destroy
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
</script>
