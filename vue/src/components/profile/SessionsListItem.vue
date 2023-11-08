
<template>
  <SessionSolveDetailsModal ref="solveDetailsModal" />
  <div class="w-full border-t-2"></div>
  <div class="flex text-sm px-1 py-3 justify-between text-left cursor-pointer hover:bg-slate-200 sessionItem"
    @click="showSessionDetails">
    <span :title="session.hash" class="w-[30%] lg:w-[23%]">{{ displaySessionHash(session.hash) }}...</span>
    <span class="w-[13%]">{{ session.puzzle }}</span>
    <span class="w-[13%]">{{ session.times.length }}</span>
    <span class="w-[13%] hidden lg:block">{{ findBest() }}</span>
    <span class="w-[13%] hidden lg:block">{{ findWorst() }}</span>
    <span class="w-[13%]">{{ average() }}</span>
  </div>
  <div v-if="showDetails"
    class="pb-2 bg-slate-100 grid grid-cols-3 cubesm:max-md:grid-cols-2 animate-drop-down relative overflow-hidden">
    <div class="h-[0.5rem] col-span-full"></div>
    <div class=" overflow-auto h-[9.5rem] scroll-smooth border-r-2 border-gray-400">
      <div v-for=" x  in  session.times.length " :key="x">
        <ProfileTimeListItem :index="x" :time="session.times[x - 1].time" :scramble="session.times[x - 1].scramble"
          :plus2="session.times[x - 1].plus2" :dnf="session.times[x - 1].dnf" class="pr-3 pl-1"
          @showSolveDetails="showTimeDetails" />
      </div>
    </div>
    <SessionStatisticsComponent :times="session.times"
      class="h-[9.5rem] md:border-r-2 border-gray-400 flex justify-center col-span-2 cubesm:col-span-1" />
    <div class="h-[9.5rem] hidden md:block">
      <span class="px-3 pt-1 font-semibold text-md md:text-xl text-slate-800 inline-block text-center w-full">Time
        details</span>
      <div v-if="solveDetails">
        <div class="flex justify-between px-2 md:px-5 font-semibold">
          <span>Time [{{ timeDetails.index }}] </span>
          <span> {{ displayTime(timeDetails.time) }}</span>
        </div>
        <div class="text-left px-2 font-semibold text-sm mt-2">
          Scramble:
          <span class="font-normal">{{ timeDetails.scramble }} </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import ProfileTimeListItem from './ProfileTimeListItem.vue'
import SessionStatisticsComponent from './SessionStatisticsComponent.vue';
import SessionSolveDetailsModal from '../modals/SessionSolveDetailsModal.vue';

const emit = defineEmits(['showNewDetails'])

const solveDetailsModal = ref(null)

let showDetails = ref(false)
let solveDetails = ref(false)

defineExpose({ closeDetails })

const props = defineProps({
  session: Object
})

let timeDetails = reactive({
  index: null,
  time: null,
  dnf: null,
  plus2: null,
  scramble: null
})

function displaySessionHash(hash) {
  return hash.slice(0, 10)
}

function showSessionDetails(e) {
  if (!showDetails.value) {
    emit('showNewDetails')

    if (e.target.parentNode.id == 'sessions-container') {
      if (e.target.style.backgroundColor == '') {
        e.target.style.backgroundColor = 'rgb(226, 232, 240)'
      } else {
        e.target.style.backgroundColor = ''
      }
    } else {
      if (e.target.parentNode.style.backgroundColor == '') {
        e.target.parentNode.style.backgroundColor = 'rgb(226, 232, 240)'
      } else {
        e.target.parentNode.style.backgroundColor = ''
      }
    }
    showDetails.value = !showDetails.value
  } else {
    emit('showNewDetails')
  }
}

function showTimeDetails(index) {
  timeDetails.index = index
  timeDetails.time = props.session.times[index - 1].time
  timeDetails.dnf = props.session.times[index - 1].dnf
  timeDetails.plus2 = props.session.times[index - 1].plus2
  timeDetails.scramble = props.session.times[index - 1].scramble
  solveDetails.value = true

  if (window.innerWidth < 768) {
    solveDetailsModal.value.showSolveDetails(index, props.session.times[index - 1].time, props.session.times[index - 1].scramble)
  }
}

function closeDetails() {
  showDetails.value = false
}

function displayTime(miliseconds) {
  let secondColon = ''
  let firstColon = ''

  let seconds = ((miliseconds) / 1000)
  let minutes = (parseInt(seconds / 60) > 0) ? parseInt(seconds / 60) : ''
  let hours = (parseInt(minutes / 60) > 0) ? parseInt(minutes / 60) : ''

  seconds = (seconds - minutes * 60).toFixed(2)

  if (minutes) {
    minutes = minutes - hours * 60
    secondColon = ':'
  }

  if (hours) {
    firstColon = ':'
  }

  return hours + firstColon + minutes + secondColon + seconds
}

function average() {
  if (props.session.times.length) {
    let sum = 0
    props.session.times.forEach((solve) => {
      sum = solve.plus2 ? sum + solve.time + 2000 : sum + solve.time
    })
    return displayTime(sum / props.session.times.length)
  }
}

function findBest() {
  let currentTime
  if (props.session.times.length) {
    let lowest = props.session.times[0].plus2 ? props.session.times[0].time + 2000 : props.session.times[0].time
    props.session.times.forEach((solve) => {
      currentTime = solve.plus2 ? solve.time + 2000 : solve.time
      if (currentTime < lowest) {
        lowest = currentTime
      }
    })
    return displayTime(lowest)
  }
}

function findWorst() {
  if (props.session.times.length) {
    let currentTime
    let highest = props.session.times[0].plus2 ? props.session.times[0].time + 2000 : props.session.times[0].time
    props.session.times.forEach((solve) => {
      currentTime = solve.plus2 ? solve.time + 2000 : solve.time
      if (currentTime > highest) {
        highest = currentTime
      }
    })
    return displayTime(highest)
  }
}
</script>
