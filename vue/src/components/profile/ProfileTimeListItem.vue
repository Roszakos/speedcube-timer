<template>
  <div class="flex justify-between">
    <div @click="showSolveDetails(index - 1)" class="cursor-pointer hover:bg-gray-300 basis-1/2 flex justify-between">
      <div class="font-bold text-indigo-600 text-right w-10">{{ index }}. </div>
      <div v-if="plus2" class="basis-1/3 text-right"> {{ displaySavedTime(time + 2000) }} </div>
      <div v-else class="basis-1/3 text-right"> {{ displaySavedTime(time) }} </div>
    </div>
    <div class="text-gray-500 ml-2 px-1" :class="{ 'font-bold !text-black': plus2 }"> +2 </div>
    <div class="text-gray-500 mx-2 px-1" :class="{ 'font-bold !text-black': dnf }">
      DNF </div>
  </div>
</template>

<script setup>
const props = defineProps({
  index: Number,
  time: Number,
  scramble: String,
  plus2: Number | Boolean,
  dnf: Number | Boolean
})
const emit = defineEmits(["plus2", "dnf", "deleteTime", "updateSolve", "showSolveDetails"])

function displaySavedTime(miliseconds) {
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

function showSolveDetails(index) {
  emit('showSolveDetails', index)
}
</script>

