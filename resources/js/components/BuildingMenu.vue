<template>
  <div class="absolute mt-2 w-40 border rounded-lg shadow-lg z-20 text-black bg-gray-200 p-2">
    <ul class="flex flex-col items-start py-2">
      <li @click="$emit('upgrade')" class="cursor-pointer hover:bg-gray-100 rounded-full p-1 mb-1 flex items-center gap-2">
        <span>Upgrade</span>
      </li>
      <li @click="$emit('move')" class="cursor-pointer hover:bg-gray-100 rounded-full p-1 mb-1 flex items-center gap-2">
        <span>Move</span>
      </li>
      <li @click="$emit('destroy')" class="cursor-pointer hover:bg-gray-100 rounded-full p-1 mb-1 flex items-center gap-2">
        <span>Destroy</span>
      </li>
    </ul>
  </div>
</template>

<script setup lang="ts">
</script>
