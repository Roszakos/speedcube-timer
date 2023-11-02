<template>
  <div v-if="showModal" @click="closeModal" class="fixed left-0 top-0 bg-gray-500/70 w-full h-full z-[1] shadow-lg">
    <div @click.stop
      class="w-[90%] h-[30%] md:w-[80%] lg:w-[60%] p-5 m-auto bg-white mt-[15rem] flex-col flex items-center justify-center rounded-md">
      <div v-html="content" class="m-auto font-bold mt-5 text-center">

      </div>
      <button @click="closeModal" class="bg-sky-600 px-3 py-2 font-semibold mt-7 rounded-lg text-white ">Close
        Details</button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import store from '../../store';

const content = ref(null)
const showModal = ref(false)

defineExpose({
  showSolveDetails
})

function showSolveDetails(index) {
  displayContent(index)
  showModal.value = true
}
function displayContent(index) {
  let newContent = '<div class=" font-bold text-5xl my-3">' + displayTime(store.state.session.times[index].time) + '</div>'
  newContent += '<div class="font-semibold text-xl">' + store.state.session.times[index].scramble + '</div>'
  content.value = newContent
}

function closeModal() {
  showModal.value = false
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


