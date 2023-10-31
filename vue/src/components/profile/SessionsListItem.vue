
<template>
  <div class="w-full border-t-2"></div>
  <div class="flex text-sm px-1 mt-1 py-2 justify-between text-left">
    <span class="w-[26%]">{{ session.hash }}</span>
    <span class="w-[13%]">{{ session.puzzle }}</span>
    <span class="w-[13%]">{{ session.times.length }}</span>
    <span class="w-[13%]">{{ findBest() }}</span>
    <span class="w-[13%]">{{ findWorst() }}</span>
    <span class="w-[13%]">{{ average() }}</span>
    <span class="w-[13%] cursor-pointer text-sky-600 select-none" @click="showSessionDetails">details</span>
  </div>
  <div v-if="showDetails" class="pb-2">
    <div class=" overflow-y-auto w-[33%] h-40 scroll-smooth border-r-2">
      <span class="px-3 pt-2 font-semibold text-xl text-indigo-800 inline-block">Times</span>
      <div v-for=" x  in  session.times.length " :key="x">
        <ProfileTimeListItem :index="x" :time="session.times[x - 1].time" :scramble="session.times[x - 1].scramble"
          :plus2="session.times[x - 1].plus2" :dnf="session.times[x - 1].dnf" class="pr-3 pl-1"
          @showSolveDetails="showSolveDetails" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import ProfileTimeListItem from './ProfileTimeListItem.vue'

let showDetails = ref(false)

const props = defineProps({
  session: Object
})

function showSessionDetails() {
  showDetails.value = !showDetails.value
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
