<template>
  <div class=" bg-gray-200  md:h-[25rem] h-[20rem] select-none">
    <span
      class="mx-[5%] pt-2 font-semibold text-xl text-indigo-800 inline-block border-b-2 border-gray-400 w-[90%]">Times</span>
    <div v-if="!sessionLoading" class="mx-[5%] w-[90%] mt-3 overflow-y-auto h-[80%] md:h-[85%]">
      <div v-if="times.length">
        <div v-for="x in times.length" :key="x" id="allTimes">
          <TimeListItem :index="times.length - (x - 1)" :time="times[times.length - x].time"
            :scramble="times[times.length - x].scramble" :plus2="times[times.length - x].plus2"
            :dnf="times[times.length - x].dnf" class="pr-3 pl-1 time" @plus2="plus2" @dnf="dnf" @deleteTime="deleteTime"
            @showSolveDetails="showSolveDetails" />
        </div>
      </div>
    </div>
    <div v-else class="flex items-center justify-center flex-col h-[80%] w-full">
      <div class="animate-spin">
        <svg class="w-10 h-10" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" opacity=".25" />
          <path
            d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"
            class="spinner_z9k8" />
        </svg>
      </div>
    </div>
  </div>
</template>

<script setup>
import TimeListItem from './TimeListItem.vue';
import { computed } from 'vue'
import store from '../store';

defineExpose({ addRecentTime })
const emit = defineEmits(['updateStats', 'showSolveDetails'])

const sessionLoading = computed(() => store.state.session.loading)
const times = computed(() => store.state.session.times)


function addRecentTime() {
  if (document.getElementsByClassName('time')[0]) {
    document.getElementsByClassName('time')[0].classList.remove('animate-fade-in-down')
    setTimeout(() => {
      document.getElementsByClassName('time')[0].classList.add('animate-fade-in-down')
    }, 10)
  }
}

function plus2(timeIndex) {

  store.state.session.times[timeIndex].plus2 = !store.state.session.times[timeIndex].plus2
  updateTime(timeIndex)
  addRecentTime()

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


