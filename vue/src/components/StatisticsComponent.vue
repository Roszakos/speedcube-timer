
<template>
  <div class=" bg-gray-200 col-span-1">
    <span class="px-2 py-2 font-semibold text-xl text-indigo-800">Statistics</span>
    <div class="ml-3 text-indigo-600 font-semibold">
      <div class="flex flex-row">
        <span class="w-1/3 mr-5 text-right"> Best: </span>
        <span v-html="bestSolve"></span>
      </div>
      <div class="flex flex-row">
        <span class="w-1/3 mr-5 text-right"> Worst: </span>
        <span v-html="worstSolve"></span>
      </div>
      <div class="flex flex-row">
        <span class="w-1/3 mr-5 text-right"> Average: </span>
        <span v-html="showAverage"></span>
      </div>
      <div class="flex flex-row mt-2">
        <span class="w-1/3 mr-5 text-right"> Avg5: </span>
        <span v-html="showAvg5"></span>
      </div>
      <div class="flex flex-row">
        <span class="w-1/3 mr-5 text-right"> BestAvg5: </span>
        <span v-html="showBestAvg5"></span>
      </div>
      <div class="flex flex-row mt-2">
        <span class="w-1/3 mr-5 text-right"> Avg12: </span>
        <span v-html="showAvg12"></span>
      </div>
      <div class="flex flex-row">
        <span class="w-1/3 mr-5 text-right"> BestAvg12: </span>
        <span v-html="showBestAvg12"></span>
      </div>
    </div>
  </div>
</template>

<script setup>
import store from '../store';
import { computed, ref } from 'vue'

defineExpose({ calculateStats })

const times = computed(() => store.state.session.times)

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
    for (let i = times.value.length - 1; i >= 5; i--) {
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
    for (let i = times.value.length - 1; i >= 12; i--) {
      currentAvg = countAvg12(times.value.slice(i - 12, i))
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
  let avg = sum / 5
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

