
<template>
  <div class="h-full lg:mt-10">
    <span class="xl:text-6xl text-5xl font-bold">
      Overview
    </span>
    <div class="m-auto xl:w-[80%] w-[90%] mt-12 grid grid-cols-2 sm:text-2xl text-xl gap-2 font-semibold h-[80%]">
      <div class="col-span-2 text-2xl text-left pl-4 lg:pl-12 mb-[-5px]">Progress graph</div>
      <BarGraph v-if="chartDataReady" class="col-span-2  m-auto" :chartData="chartData" />
      <div
        class="border-2 border-slate-700 flex items-center justify-center flex-col relative col-span-2 lg:col-span-1 max-lg:order-1">
        <div class="text-4xl tracking-wide">TOTAL SOLVES</div>
        <div class="mt-2 text-6xl font-bold" id="totalSolves">
        </div>
      </div>
      <div class=" grid lg:grid-flow-row grid-flow-col gap-2 col-span-2 lg:col-span-1 max-lg:order-2">
        <div class="border-2 border-slate-700 flex flex-col justify-between">
          <div class="pl-3 pt-1 text-left pr-1 w-full">BEST TIME</div>
          <div class="text-4xl font-bold w-full text-right pr-2 pb-1">{{ bestSolve }}</div>
        </div>
        <div class="border-2 border-slate-700 flex  flex-col justify-between">
          <div class="pl-3 pt-1 text-left pr-1 w-full">WORST TIME</div>
          <div class="text-4xl font-bold w-full text-right pr-2 pb-1">{{ worstSolve }}</div>
        </div>
      </div>
      <div class=" grid lg:grid-flow-row grid-flow-col gap-2 col-span-2 lg:col-span-1 max-lg:order-4">
        <div class="border-2 border-slate-700 flex flex-col justify-between">
          <div class="pl-3 pt-1 text-left pr-1 w-full">BEST AVERAGE OF 5</div>
          <div class="text-4xl font-bold  w-full text-right pr-2 pb-1">{{ avg5 }}</div>
        </div>
        <div class="border-2 border-slate-700 flex  flex-col justify-between">
          <div class="pl-3 pt-1 text-left pr-1 w-full">BEST AVERAGE OF 12</div>
          <div class=" text-4xl font-bold  w-full text-right pr-2 pb-1">{{ avg12 }}</div>
        </div>
      </div>
      <div
        class="border-2 border-slate-700 flex items-center justify-center flex-col relative col-span-2 lg:col-span-1 max-lg:order-3">
        <div class="text-4xl tracking-wide">AVERAGE TIME</div>
        <div class="mt-2 text-6xl font-bold">{{ avg }}</div>
      </div>
      <!-- <div class="col-span-2 border-2 border-slate-700 flex items-center justify-center flex-col relative max-lg:order-5">
        <div class="text-4xl tracking-wide px-1">BEST SESSION</div>
        <div class="text-2xl tracking-wide px-1">(AVERAGE)</div>
        <div class="mt-4 text-6xl font-bold">{{ sessionAvg }}</div>
      </div> -->
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import BarGraph from '../graphs/BarGraph.vue';


const props = defineProps({
  sessions: Array
})

let chartDataReady = false
let chartData = {
  labels: [],
  datasets: []
}

const totalSolves = ref(null)
const bestSolve = ref(null)
const worstSolve = ref(null)
const avg = ref(null)
const avg5 = ref(null)
const avg12 = ref(null)
const sessionAvg = ref(null)

onMounted(() => {
  startCounting('totalSolves', 35, 2000)
})

prepareChartData()
countSolves()
bestTime()
worstTime()
average()
bestAvg5()
bestAvg12()
bestSessionAvg()


function startCounting(elementId, endVal, duration) {
  const el = document.getElementById(elementId)
  let addition = ((endVal) / (duration / 10))
  let milestones = []

  let howManyMilestones = 0

  while (addition > 0.06) {
    addition = addition / 2
    howManyMilestones++
  }

  let milestoneMultiplier = 0.8

  for (let i = 1; i < howManyMilestones - 1; i++) {
    milestoneMultiplier = milestoneMultiplier + (0.2 / Math.pow(2, i))
    milestones.push(endVal * milestoneMultiplier)
  }

  addition = ((endVal) / (duration / 10))
  let interval = 7
  let currentVal = 0


  const count = setInterval(() => {
    currentVal = countUp(currentVal, addition)
    el.innerHTML = Math.floor(currentVal)

    if (el.innerHTML >= milestones[0]) {
      if (milestones.length == 1) {
        addition = 0.035
      } else if (milestones.length == 2) {
        addition = 0.8
      } else {
        addition = addition / 2
      }
      milestones.shift()
    }

    if (el.innerHTML >= endVal) {
      el.innerHTML = endVal
      clearInterval(count)
    }
  }, interval)
}


function countUp(currentVal, addition) {
  return currentVal + addition
}

function countSolves() {
  if (props.sessions.length) {
    let counter = 0
    props.sessions.forEach((session) => {
      counter = counter + session.times.length
    })
    totalSolves.value = counter
  }
}

function prepareChartData() {
  let sessionsAverage = []
  let sessionsDates = []
  let ratio;
  let sum
  let sessionShowInterval = Math.floor(props.sessions.length / 20)

  if (sessionShowInterval > 1) {
    // Count average of first session
    sum = 0
    props.sessions[0].times.forEach((solve) => {
      sum = solve.plus2 ? sum + solve.time + 2000 : sum + solve.time
    })
    sessionsAverage.push(displayTime(sum / props.sessions[0].times.length))
    sessionsDates.push(props.sessions[0].start_date.substring(5, 10))

    // Put 18 more sessions in the chart data
    let i = sessionShowInterval
    while (sessionsAverage.length < 19) {
      sum = 0
      props.sessions[i].times.forEach((solve) => {
        sum = solve.plus2 ? sum + solve.time + 2000 : sum + solve.time
      })
      sessionsAverage.unshift(displayTime(sum / props.sessions[i].times.length))
      sessionsDates.unshift(props.sessions[i].start_date.substring(5, 10))
      i = i + sessionShowInterval
    }

    // Count average of last session
    sum = 0
    props.sessions[props.sessions.length - 1].times.forEach((solve) => {
      sum = solve.plus2 ? sum + solve.time + 2000 : sum + solve.time
    })
    sessionsAverage.unshift(displayTime(sum / props.sessions[props.sessions.length - 1].times.length))
    sessionsDates.unshift(props.sessions[props.sessions.length - 1].start_date.substring(5, 10))
  } else {
    // If there are less than 20 sessions, display them all in the chart
    props.sessions.forEach((session) => {
      sum = 0
      session.times.forEach((solve) => {
        sum = solve.plus2 ? sum + solve.time + 2000 : sum + solve.time
      })
      sessionsAverage.unshift(displayTime(sum / session.times.length))
      sessionsDates.unshift(session.start_date.substring(5, 10))
    })
  }


  chartData.labels = sessionsDates
  chartData.datasets = [{
    label: 'Session average',
    data: sessionsAverage,
    borderColor: '#36A2EB',
  }]
  chartDataReady = true
}


function bestTime() {
  if (props.sessions.length) {
    let currentTime
    let best = props.sessions[0].times[0].plus2 ? props.sessions[0].times[0].time + 2000 : props.sessions[0].times[0].time
    props.sessions.forEach((session) => {
      session.times.forEach((solve) => {
        currentTime = solve.plus2 ? solve.time + 2000 : solve.time
        best = currentTime < best ? currentTime : best
      })
    })
    bestSolve.value = displayTime(best)
  }
}

function worstTime() {
  if (props.sessions.length) {
    let currentTime
    let worst = props.sessions[0].times[0].plus2 ? props.sessions[0].times[0].time + 2000 : props.sessions[0].times[0].time
    props.sessions.forEach((session) => {
      session.times.forEach((solve) => {
        currentTime = solve.plus2 ? solve.time + 2000 : solve.time
        worst = currentTime > worst ? currentTime : worst
      })
    })
    worstSolve.value = displayTime(worst)
  }
}

function average() {
  if (props.sessions.length) {
    let sum = 0
    props.sessions.forEach((session) => {
      session.times.forEach((solve) => {
        sum = sum + solve.time
        sum += solve.plus2 ? 2000 : 0
      })
    })
    avg.value = displayTime(sum / totalSolves.value)
  }
}

function bestAvg5() {
  if (props.sessions.length) {
    let currentAvg
    let best = 999999999
    props.sessions.forEach((session) => {
      if (session.times.length > 4) {
        for (let i = session.times.length; i >= 5; i--) {
          currentAvg = countAvg(session.times.slice(i - 5, i))
          best = currentAvg < best ? currentAvg : best
        }
      }
    })
    if (best == 999999999) {
      avg5.value = '-'
      return
    }
    avg5.value = displayTime(best)
  }
}

function bestAvg12() {
  if (props.sessions.length) {
    let currentAvg
    let best = 999999999
    props.sessions.forEach((session) => {
      if (session.times.length > 11) {
        for (let i = session.times.length; i >= 12; i--) {
          currentAvg = countAvg(session.times.slice(i - 12, i))
          best = currentAvg < best ? currentAvg : best
        }
      }
    })
    if (best == 999999999) {
      avg12.value = '-'
      return
    }
    avg12.value = displayTime(best)
  }
}

function bestSessionAvg() {
  if (props.sessions.length) {
    let best = 999999999
    let currentAvg
    props.sessions.forEach((session) => {
      let sum = 0
      session.times.forEach((solve) => {
        sum += solve.plus2 ? (solve.time + 2000) : solve.time
      })
      currentAvg = sum / session.times.length
      best = currentAvg < best ? currentAvg : best
    })
    sessionAvg.value = displayTime(best)
  }
}

function countAvg(solves) {
  if (solves.length > 2) {
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
    let avg = sum / (solves.length - 2)
    return avg
  }
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


