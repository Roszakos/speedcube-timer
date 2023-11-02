
<template>
  <GlobalEvents @keydown.space="handleSpaceDown" @keyup.space="handleSpaceUp" />
  <div @mousedown="handleMouseDown" @mouseup="handleMouseUp" class="text-center w-full py-10 pb-20 cursor-pointer"
    :class="[nowSolving ? 'absolute top-[35%] left-0 right-0 ' : '']">
    <span class="text-7xl lg:text-9xl md:text-8xl font-sans select-none tabular-nums "
      :class="{ 'text-red-500': preparingTimer, 'text-green-500': canStartTimer, 'lg:text-[10rem] md:text-[8rem] text-[6.5rem]': nowSolving }">
      <span id="hours" v-if="hours" class="px-2">{{ hours }}</span>
      <span id="fC" v-if="firstColon">{{ firstColon }}</span>
      <span id="minutes" v-if="minutes" class="px-2">{{ minutes }}</span>
      <span id="sC" v-if="secondColon">{{ secondColon }}</span>
      <span id="seconds" v-if="seconds" class="px-2">{{ seconds }}</span>

    </span>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import store from '../store';

defineExpose({
  resetTimer
})

const emit = defineEmits(['saveTime', 'generateNewScramble', 'timerStarted'])

let seconds = ref((0).toFixed(2))
let minutes = ref(null)
let hours = ref(null)
let firstColon = ref(null)
let secondColon = ref(null)

let startTime = 0
let endTime = 0
let nowSolving = ref(false)
let timeInterval;

let preparationStart = 0
let preparingTimer = ref(false)
let canStartTimer = ref(false)


function handleSpaceDown() {
  // Check if timer is running
  if (nowSolving.value) {
    stopSolve()
    return
  }

  // Check if space is pressed long enough
  if (preparationStart) {
    if (Date.now() - preparationStart > 300) {
      preparingTimer.value = false
      canStartTimer.value = true
    }
    return
  }
  resetTimer()
  preparationStart = Date.now()
  preparingTimer.value = true
}

function handleSpaceUp() {
  // Start timer if space was pressed long enough
  if (canStartTimer.value) {
    startSolve()
    canStartTimer.value = false
  }
  preparingTimer.value = false
  preparationStart = 0
}

function handleMouseDown() {
  // Check if timer is running
  if (nowSolving.value) {
    stopSolve()
    return
  }


  preparationStart = Date.now()
  preparingTimer.value = true
  resetTimer()

  setTimeout(function () {
    if (preparationStart) {
      canStartTimer.value = true
      preparingTimer.value = false
    }
  }, 300)
}

function handleMouseUp() {
  // Start timer if mouse was pressed long enough
  if (preparationStart) {
    if (Date.now() - preparationStart > 400) {
      preparingTimer.value = false
      canStartTimer.value = true
    }
    if (canStartTimer.value) {
      startSolve()
      canStartTimer.value = false
    }
    preparingTimer.value = false
    preparationStart = 0
  }
}

function startSolve() {
  if (!nowSolving.value) {
    emit('timerStarted')

    preparationStart = 0

    // Clear all displayed variables
    hours.value = null
    minutes.value = null
    firstColon.value = null
    secondColon.value = null
    seconds.value = (0).toFixed(2)

    // Update solving status
    nowSolving.value = true

    startTime = Date.now()

    // Update timer every .01s
    timeInterval = setInterval(() => {
      seconds.value = (parseFloat(seconds.value) + 0.01).toFixed(2)
      if (seconds.value > 59.99) {
        minutes.value = minutes.value + 1
        seconds.value = '0'
        secondColon.value = ':'
      }
      if (minutes.value > 59) {
        hours.value = hours.value + 1
        minutes.value = '0'
        firstColon.value = ':'
      }
    }, 10)
  }
}

function stopSolve() {
  if (nowSolving.value) {
    // Stop timer 
    clearInterval(timeInterval)

    // Update solving status
    nowSolving.value = false

    endTime = Date.now()

    // Transform miliseconds to hours, minutes and seconds 
    displayTime(endTime - startTime)
    store.state.currentSolve.time = endTime - startTime

    emit('saveTime')
    emit('generateNewScramble')
  }
}

function displayTime(miliseconds) {
  seconds.value = (miliseconds / 1000)
  minutes.value = (parseInt(seconds.value / 60) > 0) ? parseInt(seconds.value / 60) : null
  hours.value = (parseInt(minutes.value / 60) > 0) ? parseInt(minutes.value / 60) : null

  seconds.value = (seconds.value - minutes.value * 60).toFixed(2)

  if (minutes.value) {
    minutes.value = minutes.value - hours.value * 60
    secondColon.value = ':'
  }

  if (hours.value) {
    firstColon.value = ':'
  }
}

function resetTimer() {
  seconds.value = '0.00'
  minutes.value = null
  hours.value = null
  firstColon.value = null
  secondColon.value = null
}
</script>

