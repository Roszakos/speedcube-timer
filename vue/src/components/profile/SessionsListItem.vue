
<template>
  <div class="w-full border-t-2"></div>
  <div class="flex text-sm px-2 mt-1 py-2 justify-between text-left">
    <span class="w-[26%]">{{ session.hash }}</span>
    <span class="w-[14%]">{{ session.puzzle }}</span>
    <span class="w-[14%]">{{ session.times.length }}</span>
    <span class="w-[14%]">{{ findBest() }}</span>
    <span class="w-[14%]">{{ findWorst() }}</span>
    <span class="w-[14%]">{{ average() }}</span>
  </div>
</template>

<script setup>
const props = defineProps({
  session: Object
})

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
