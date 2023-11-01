
<template>
  <div class="basis-3/4 mt-10 h-full">
    <span class="text-4xl font-bold">
      Overview
    </span>
    <div class="m-auto w-[80%] h-[90%] mt-12 relative">
      <OverviewListItem title="TOTAL SOLVES" :data="totalSolves" />
      <OverviewListItem title="BEST TIME" :data="bestSolve" class="mt-6" />
      <OverviewListItem title="WORST TIME" :data="worstSolve" class="mt-6" />
      <OverviewListItem title="AVERAGE TIME" :data="avg" class="mt-6" />
      <OverviewListItem title="BEST AVERAGE OF 5" :data="avg5" class="mt-6" />
      <OverviewListItem title="BEST AVERAGE OF 12" :data="avg12" class="mt-6" />
      <OverviewListItem title="BEST SESSION AVERAGE" :data="sessionAvg" class="mt-6" />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import OverviewListItem from './OverviewListItem.vue';

const props = defineProps({
  sessions: Array
})

const totalSolves = ref(null)
const bestSolve = ref(null)
const worstSolve = ref(null)
const avg = ref(null)
const avg5 = ref(null)
const avg12 = ref(null)
const sessionAvg = ref(null)

countSolves()
bestTime()
worstTime()
average()
bestAvg5()
bestAvg12()
bestSessionAvg()

function countSolves() {
  if (props.sessions.length) {
    let counter = 0
    props.sessions.forEach((session) => {
      counter = counter + session.times.length
    })
    totalSolves.value = counter
  }
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


