<template>
  <div class=" bg-gray-200 shadow-md rounded-l-[20px] col-span-1 overflow-y-auto">
    <span class="px-3 pt-2 font-semibold text-xl text-indigo-800 inline-block">Times</span>
    <div v-if="times.length" v-for="x in times.length" :key="x">
      <TimeListItem :index="times.length - (x - 1)" :time="times[times.length - x].time"
        :scramble="times[times.length - x].scramble" :plus2="times[times.length - x].plus2"
        :dnf="times[times.length - x].dnf" class="pr-3 pl-1" @plus2="plus2" @dnf="dnf" @deleteTime="deleteTime"
        @showSolveDetails="showSolveDetails" />
    </div>
  </div>
</template>

<script setup>
import TimeListItem from './TimeListItem.vue';
import { computed } from 'vue'
import store from '../store';

const emit = defineEmits(['updateStats', 'showSolveDetails'])

const times = computed(() => store.state.session.times)

function plus2(timeIndex) {
  store.state.session.times[timeIndex].plus2 = !store.state.session.times[timeIndex].plus2
  updateTime(timeIndex)
}

function dnf(timeIndex) {
  store.state.session.times[timeIndex].dnf = !store.state.session.times[timeIndex].dnf
  updateTime(timeIndex)
}

function deleteTime(timeIndex) {
  store.dispatch('deleteSolve', store.state.session.times[timeIndex].hash)
  store.state.session.times.splice(timeIndex, 1)
  emit('updateStats')
}

// Update time when plus2 or DNF is clicked
function updateTime(timeIndex) {
  store.dispatch('updateSolve', {
    hash: store.state.session.times[timeIndex].hash,
    plus2: store.state.session.times[timeIndex].plus2,
    dnf: store.state.session.times[timeIndex].dnf
  })
  emit('updateStats')
}

function showSolveDetails(index) {
  emit('showSolveDetails', index)
}
</script>


