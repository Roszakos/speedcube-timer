
<template>
  <div class=" bg-gray-200 pb-3 select-none min-h-[20rem]">
    <span
      class="mx-[5%] pt-2 font-semibold text-xl inline-block text-indigo-800 border-b-2 border-gray-400 w-[90%]">Statistics</span>
    <div v-if="!sessionLoading" class="mx-[5%] text-indigo-600 font-semibold w-[90%] mt-2">
      <div class="flex flex-row hover:bg-gradient-to-b from-gray-200/50 to-slate-400/50">
        <span class="w-[45%] text-right"> Best </span>
        <span v-html="bestSolve" class="text-right w-2/3 tabular-nums"></span>
      </div>
      <div class="flex flex-row hover:bg-gradient-to-b from-gray-200/50 to-slate-400/50">
        <span class="w-[45%] text-right"> Worst </span>
        <span v-html="worstSolve" class="text-right w-2/3 tabular-nums"></span>
      </div>
      <div class="flex flex-row hover:bg-gradient-to-b from-gray-200/50 to-slate-400/50">
        <span class="w-[45%] text-right"> Average </span>
        <span v-html="showAverage" class="text-right w-2/3 tabular-nums"></span>
      </div>
      <div class="w-full border-b-[1px] border-gray-600/50 my-3"></div>
      <div class="flex flex-row mt-2 hover:bg-gradient-to-b from-gray-200/50 to-slate-400/50">
        <span class=" w-[45%] text-right"> Avg5 </span>
        <span v-html="showAvg5" class="text-right w-2/3 tabular-nums"></span>
      </div>
      <div class="flex flex-row hover:bg-gradient-to-b from-gray-200/50 to-slate-400/50">
        <span class="w-[45%] text-right"> Best Avg5 </span>
        <span v-html="showBestAvg5" class="text-right w-2/3 tabular-nums"></span>
      </div>
      <div class="w-full border-b-[1px] border-gray-600/50 my-3"></div>
      <div class="flex flex-row mt-2 hover:bg-gradient-to-b from-gray-200/50 to-slate-400/50">
        <span class="w-[45%] text-right"> Avg12 </span>
        <span v-html="showAvg12" class="text-right w-2/3 tabular-nums"></span>
      </div>
      <div class="flex flex-row hover:bg-gradient-to-b from-gray-200/50 to-slate-400/50">
        <span class="w-[45%] text-right"> Best Avg12 </span>
        <span v-html="showBestAvg12" class="text-right w-2/3 tabular-nums"></span>
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
import store from '../store';
import { computed, ref } from 'vue'

defineExpose({ calculateStats })

const times = computed(() => store.state.session.times)
const sessionLoading = computed(() => store.state.session.loading)

let bestSolve = ref(null)
let worstSolve = ref(null)
let showAverage = ref(null)
let showAvg5 = ref(null)
let showBestAvg5 = ref(null)
let showAvg12 = ref(null)
let showBestAvg12 = ref(null)

calculateStats()

function calculateStats() {
  findBest()
  findWorst()
  average()
  avg5()
  bestAvg5()
  avg12()
  bestAvg12()
}

function findBest() {
  let currentTime
  if (times.value.length) {
    let lowest = times.value[0].plus2 ? times.value[0].time + 2000 : times.value[0].time
    times.value.forEach((solve) => {
      currentTime = solve.plus2 ? solve.time + 2000 : solve.time
      if (currentTime < lowest) {
        lowest = currentTime
      }
    })
    bestSolve.value = displayTime(lowest)
  } else {
    bestSolve.value = '<span class="text-gray-400">0.00</span>'
  }
}

function findWorst() {
  let currentTime
  if (times.value.length) {
    let highest = times.value[0].plus2 ? times.value[0].time + 2000 : times.value[0].time
    times.value.forEach((solve) => {
      currentTime = solve.plus2 ? solve.time + 2000 : solve.time
      if (currentTime > highest) {
        highest = currentTime
      }
    })
    worstSolve.value = displayTime(highest)
  } else {
    worstSolve.value = '<span class="text-gray-400">0.00</span>'
  }
}

function average() {
  if (times.value.length) {
    let sum = 0
    times.value.forEach((solve) => {
      sum = solve.plus2 ? sum + solve.time + 2000 : sum + solve.time
    })
    showAverage.value = displayTime(sum / times.value.length)
  } else {
    showAverage.value = '<span class="text-gray-400">0.00</span>'
  }
}

function avg5() {
  if (times.value.length > 4) {
    let avg = countAvg5(times.value.slice(times.value.length - 5))
    showAvg5.value = displayTime(avg)
  } else {
    showAvg5.value = '<span class="text-gray-400">0.00</span>'
  }
}

function avg12() {
  if (times.value.length > 11) {
    let avg = countAvg12(times.value.slice(times.value.length - 12))
    showAvg12.value = displayTime(avg)
  } else {
    showAvg12.value = '<span class="text-gray-400">0.00</span>'
  }
}

function bestAvg5() {
  if (times.value.length > 4) {
    let currentAvg;
    let bestAvg = countAvg5(times.value.slice(times.value.length - 5))
    for (let i = times.value.length; i >= 5; i--) {
      currentAvg = countAvg5(times.value.slice(i - 5, i))
      if (currentAvg < bestAvg) {
        bestAvg = currentAvg
      }
    }
    showBestAvg5.value = displayTime(bestAvg)
  } else {
    showBestAvg5.value = '<span class="text-gray-400">0.00</span>'
  }
}

function bestAvg12() {
  if (times.value.length > 11) {
    let currentAvg;
    let bestAvg = countAvg12(times.value.slice(times.value.length - 12))
    for (let i = times.value.length; i >= 12; i--) {
      currentAvg = countAvg12(times.value.slice(i - 12, i)) //  slice(0, 12)
      if (currentAvg < bestAvg) {
        bestAvg = currentAvg
      }
    }
    showBestAvg12.value = displayTime(bestAvg)
  } else {
    showBestAvg12.value = '<span class="text-gray-400">0.00</span>'
  }
}

function countAvg5(solves) {
  let sum = 0
  let currentTime
  let worst = solves[0].plus2 ? solves[0].time + 2000 : solves[0].time
  let best = solves[0].plus2 ? solves[0].time + 2000 : solves[0].time

  solves.forEach((solve) => {
    currentTime = solve.plus2 ? solve.time + 2000 : solve.time
    if (currentTime < best) {
      best = currentTime
    } else if (currentTime > worst) {
      worst = currentTime
    }
    sum = sum + currentTime
  })
  sum = sum - worst - best
  let avg = sum / 3
  return avg
}

function countAvg12(solves) {
  let sum = 0
  let currentTime
  let worst = solves[0].plus2 ? solves[0].time + 2000 : solves[0].time
  let best = solves[0].plus2 ? solves[0].time + 2000 : solves[0].time

  solves.forEach((solve) => {
    currentTime = solve.plus2 ? solve.time + 2000 : solve.time
    if (currentTime < best) {
      best = currentTime
    } else if (currentTime > worst) {
      worst = currentTime
    }
    sum = sum + currentTime
  })
  sum = sum - worst - best
  let avg = sum / 10
  return avg
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
</script>

