<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed, reactive, onMounted, defineProps } from 'vue';
import BuildingMenu from '../components/BuildingMenu.vue';



const props = defineProps({
  buildings: Array as () => Array<{id: number; building_name: string; position: number}>,
});

// Breadcrumbs for navigation
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

// Holds the indices of the squares that have been clicked
const clickedSquares = ref<number[]>([]);

// Form for gathering resources
const form = reactive({
  resource: 'rock',
})

// State for the resource gathering button
const isClicked = ref(false);

// Computed property to determine the fill duration for the resource gathering animation
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

// buildings Drop down list source/functionality
const buildingsList = [
    { name: 'Castle', img: '/images/castle.svg', cost: {} },
    { name: 'Quarry', img: '/images/quarry.svg', cost: {} },
    { name: 'Farm', img: '/images/farm.svg', cost: {} },
    { name: 'Sawmill', img: '/images/sawmill.svg', cost: {} },
    { name: 'Mine', img: '/images/mine.svg', cost: {} }
]
// State for the building dropdown visibility
const buildingDropdownOpen = ref(false);
// The currently active square for building placement
const activeSquare = ref<number | null>(null);

const handleSquareClickState = ref(false)

const buildingMenuOpen = ref(false);

// Handles clicking on a square in the grid
function handleSquareClick(n: number) {
  if (squares.value[n]) {
    handleSquareClickState.value = true;
    activeSquare.value = n;
    buildingMenuOpen.value = true;
  } else {
    handleSquareClickState.value = true;
    if (!clickedSquares.value.includes(n)) {
      clickedSquares.value.push(n);
    }
    activeSquare.value = n;
    buildingDropdownOpen.value = true;
  }
}

// Handles selecting a building from the dropdown
async function selectBuilding(building: { name: string; img: string; cost: Record<string, number> }) {
  if (activeSquare.value !== null) {
    await placeBuilding(building.name, activeSquare.value);
    squares.value[activeSquare.value] = building.img;
  }
  buildingDropdownOpen.value = false;
  activeSquare.value = null;
}

async function placeBuilding(buildingName: string, position: number) {
  try {
    await router.post('/buildings', {
      building_name: buildingName,
      position: position,
    });
    } catch (error) {
    console.error('Failed to place building:', error);
    }
};

async function destroyBuilding(position: number) {
  const building = props.buildings.find(b => b.position === position);
  if (!building) return;

  try {
    await router.delete(`/buildings/${building.id}`);
    squares.value[position] = null;
    buildingMenuOpen.value = false;
    activeSquare.value = null;
  } catch (error) {
    console.error('Failed to destroy building:', error);
  }
}

// The state of the grid squares, holding building names
const squares = ref<(string | null)[]>(Array(50).fill(null));

// Handles the click event for gathering resources
async function handleClick() {
    isClicked.value = true;
    form.resource = selectedResource.value; // Update the resource in the form
    router.post('/gather-resource', form, {
      onFinish: () => {
        setTimeout(() => {
          isClicked.value = false;
          fetchResources(); // Fetch after fill animation
        }, fillDuration.value);
      }
    });
}

// The player's resources
const resources = ref([
  { value: 'rock', label: 'Rock', img: '/images/rock.png', count: 0 },
  { value: 'wood', label: 'Wood', img: '/images/wood.svg', count: 0 },
  { value: 'food', label: 'Food', img: '/images/food.png', count: 0 },
  { value: 'gold', label: 'Gold', img: '/images/gold.png', count: 0 },
]);



// Fetches the player's resources from the API
async function fetchResources() {
  try {
    const response = await fetch('/resources');
    if (!response.ok) throw new Error('Failed to fetch resources');
    const data = await response.json();
    if (data.resources) {
      resources.value = resources.value.map(r => {
        const found = data.resources.find((res: any) => res.type === r.value);
        return { ...r, count: found ? found.quantity : 0 };
      });
    }
  } catch {
    // Optionally handle error
  }
}

// Fetch resources when the component is mounted
onMounted(() => {
  if (props.buildings) {
    props.buildings.forEach(building => {
      const buildingInfo = buildingsList.find(b => b.name === building.building_name);
      if (buildingInfo) {
        squares.value[building.position] = buildingInfo.img;
      }
    });
  }
  fetchResources()
});

// State for the resource selection dropdown visibility
const dropdownOpen = ref(false);

// Handles selecting a resource from the dropdown
function selectResource(resource: any) {
  selectedResource.value = resource.value;
  dropdownOpen.value = false;
}

// The currently selected resource for gathering
const selectedResource = ref('rock');

// Computed property for dynamic styling based on the selected resource
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

// Calculates the left position for the building dropdown
const calcLeftPosition = (n) => {
    const col = (n - 1) % 7;
    return `${col * 13}%`;
};

// Calculates the top position for the building dropdown
const calTopPosition = (n) => {
    const row = Math.floor((n - 1) / 7);
    return `${row * 13}%`;
};

// Closes the building dropdown
function closeBuildingDropdown() {
  if (!handleSquareClickState.value) {
    buildingDropdownOpen.value = false;
    buildingMenuOpen.value = false;
    activeSquare.value = null;
  }
  handleSquareClickState.value = false;

}
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs" @click="closeBuildingDropdown">
      <!-- Building selection dropdown -->
      <!-- <select v-model="selectedBuilding" class="mb-4 p-2 border rounded">
        <option v-for="building in buildings" :key="building.name" :value="building.name">
          {{ building.name }} ({{ building.cost.wood }} Wood, {{ building.cost.stone }} Stone,)
        </option>
      </select> -->
      <!-- Display player's resources in the top right corner -->
      <div class="absolute top-4 right-4 flex flex-col items-end gap-2 z-20">
          <div v-for="resource in resources" :key="resource.value + '-top'" class="flex items-center gap-1">
              <span class="text-xs font-bold text-white-700 min-w-[18px] text-right">{{ resource.count }}</span>
              <img :src="resource.img" :alt="resource.label" class="w-7 h-7 rounded-full border border-gray-200 shadow bg-white" />
          </div>
      </div> 
        <div
            class="flex h-full flex-1 gap-4 flex-col rounded-b-xl relative bg-green-900"
            style="background-image: url('/images/Background.jpg'); background-size: cover; background-position: center;"
        >
            <!-- Main game grid -->
            <div class="absolute inset-0 flex items-center justify-center z-10 overflow-visible">
              <div class="grid grid-cols-7 grid-rows-7 gap-2 relative">
                <!-- Grid squares for building -->
                <div
                  v-for="n in 49"
                  :key="n"
                  @click="handleSquareClick(n)"
                  class="w-20 h-20 flex items-center justify-center border-2 border-gray-300 rounded-lg cursor-pointer transition bg-transparent relative"
                  :style="{background: clickedSquares.includes(n) ? 'transparent' : 'transparent',
                  opacity: clickedSquares.includes(n) ? 0.5 : 1 }"
                  >
                  <!-- Show ddl only for active aquare -->
                  <img v-if="squares[n]" :src="squares[n]" alt="" class="w-19 h-19" />
                </div>
<!--  -->
                <!-- Building dropdown for each square -->
               <div
                  v-for="n in 49"
                  :key="n">
                  <BuildingMenu
                    v-if="buildingMenuOpen && activeSquare === n"
                    :style="`left: `+ calcLeftPosition(n) + `; top: `+ calTopPosition(n) + `;`"
                    class="absolute mt-2 w-40 border rounded-lg shadow-lg z-20 text-black bg-gray-200 p-2"
                    @upgrade="() => {} /* Placeholder for upgrade action */"
                    @move="() => {} /* Placeholder for move action */"
                    @destroy="destroyBuilding(n)"
                  />
                  <div
                    v-if="buildingDropdownOpen && activeSquare === n"
                    :style="`left: `+ calcLeftPosition(n) + `; top: `+ calTopPosition(n) + `;`"
                    class="absolute mt-2 w-40 border rounded-lg shadow-lg z-20 text-black bg-gray-200 p-2"
                    >
                      <ul class="flex flex-col items-start py-2">
                        <li
                          v-for="building in buildingsList"
                          :key="building.name"
                          @click.stop="selectBuilding(building)"
                          class="cursor-pointer hover:bg-gray-100 rounded-full p-1 mb-1 flex items-center gap-2"
                        >
                          <img :src="building.img" alt="" class="w-8  h-8 rounded-full" />
                          <span class="ml-2">{{ building.name }}</span>
                        </li>
                      </ul> 
                  </div>
                </div>
            </div>
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">   
            </div>
            <!-- Resource gathering controls -->
            <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 z-10 flex items-center gap-4">
                <!-- Resource gathering button -->
                <button
                    class="w-30 h-30 rounded-full shadow relative overflow-hidden transition-all duration-500 flex items-center justify-center text-2xl"
                    :class="resourceStyles.button"
                    @click="handleClick"
                    :disabled="isClicked"
                >
                    <span
                        class="absolute left-0 bottom-0 w-full bg-white z-0"
                        :class="isClicked ? 'transition-all' : ''"
                        :style="{
                            height: isClicked ? '100%' : '0%',
                            transitionDuration: isClicked ? fillDuration + 'ms' : '0ms'
                        }"
                    ></span>
                    <span class_name="relative z-10">Click</span>
                </button>
                <!-- Resource selection dropdown -->
                <div class="relative">
                  <button
                    @click="dropdownOpen = !dropdownOpen"
                    class="h-14 w-14 rounded-full border flex items-center justify-center bg-white font-semibold transition focus:outline-none"
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
      </div>
    </AppLayout>
</template>
