<script setup lang="ts">
import { ref, onMounted } from 'vue';

interface Leader {
  name: string;
  total: number;
}

const leaders = ref<Leader[]>([]);


async function fetchLeaderBoard() {
  const res = await fetch('/leaderboard');
  const data = await res.json();
  leaders.value = data.leaders;
}
onMounted(fetchLeaderBoard);
</script>
<template>
  <div class="p-2">
    <ul>
      <li v-for="(leader, i) in leaders" :key="leader.name" class="flex justify-between">
        <span>{{ i + 1 }}. {{ leader.name }}</span>
        <span>{{ leader.total }}</span>
      </li>
    </ul>
  </div>
</template>