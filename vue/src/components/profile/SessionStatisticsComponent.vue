
<template>
  <div class=" pb-3 select-none">
    <div class="mx-[5%] text-indigo-600 font-semibold w-[90%] mt-2">
      <div class="flex flex-row hover:bg-gradient-to-b from-gray-200/50 to-slate-400/50">
        <span class="w-[75%] text-right"> Best </span>
        <span v-html="bestSolve" class="text-right w-2/3 tabular-nums"></span>
      </div>
      <div class="flex flex-row hover:bg-gradient-to-b from-gray-200/50 to-slate-400/50">
        <span class="w-[75%] text-right"> Worst </span>
        <span v-html="worstSolve" class="text-right w-2/3 tabular-nums"></span>
      </div>
      <div class="flex flex-row hover:bg-gradient-to-b from-gray-200/50 to-slate-400/50">
        <span class="w-[75%] text-right"> Average </span>
        <span v-html="showAverage" class="text-right w-2/3 tabular-nums"></span>
      </div>
      <div class="w-full border-gray-600/50"></div>
      <div class="flex flex-row hover:bg-gradient-to-b from-gray-200/50 to-slate-400/50">
        <span class="w-[75%] text-right"> Best Avg5 </span>
        <span v-html="showBestAvg5" class="text-right w-2/3 tabular-nums"></span>
      </div>
      <div class="w-full border-gray-600/50"></div>
      <div class="flex flex-row hover:bg-gradient-to-b from-gray-200/50 to-slate-400/50">
        <span class="w-[75%] text-right"> Best Avg12 </span>
        <span v-html="showBestAvg12" class="text-right w-2/3 tabular-nums"></span>
      </div>
    </div>
  </div>
</template>

<script setup>

import { ref } from 'vue'

const props = defineProps({
  times: Array
})

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
  if (props.times.length) {
    let lowest = props.times[0].plus2 ? props.times[0].time + 2000 : props.times[0].time
    props.times.forEach((solve) => {
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
  if (props.times.length) {
    let highest = props.times[0].plus2 ? props.times[0].time + 2000 : props.times[0].time
    props.times.forEach((solve) => {
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
  if (props.times.length) {
    let sum = 0
    props.times.forEach((solve) => {
      sum = solve.plus2 ? sum + solve.time + 2000 : sum + solve.time
    })
    showAverage.value = displayTime(sum / props.times.length)
  } else {
    showAverage.value = '<span class="text-gray-400">0.00</span>'
  }
}

function avg5() {
  if (props.times.length > 4) {
    let avg = countAvg5(props.times.slice(props.times.length - 5))
    showAvg5.value = displayTime(avg)
  } else {
    showAvg5.value = '<span class="text-gray-400">0.00</span>'
  }
}

function avg12() {
  if (props.times.length > 11) {
    let avg = countAvg12(props.times.slice(props.times.length - 12))
    showAvg12.value = displayTime(avg)
  } else {
    showAvg12.value = '<span class="text-gray-400">0.00</span>'
  }
}

function bestAvg5() {
  if (props.times.length > 4) {
    let currentAvg;
    let bestAvg = countAvg5(props.times.slice(props.times.length - 5))
    for (let i = props.times.length; i >= 5; i--) {
      currentAvg = countAvg5(props.times.slice(i - 5, i))
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
  if (props.times.length > 11) {
    let currentAvg;
    let bestAvg = countAvg12(props.times.slice(props.times.length - 12))
    for (let i = props.times.length; i >= 12; i--) {
      currentAvg = countAvg12(props.times.slice(i - 12, i)) //  slice(0, 12)
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

