<template>
  <div class=" bg-gray-200 shadow-md rounded-l-[20px] col-span-1 overflow-y-auto">
    <span class="px-3 pt-2 font-semibold text-xl text-indigo-800 inline-block">Times</span>
    <div v-if="times.length" v-for="x in times.length" :key="x">
      <TimeListItem :index="times.length - (x - 1)" :time="times[times.length - x].time"
        :scramble="times[times.length - x].scramble" class="pr-3 pl-1" />
    </div>
  </div>
</template>

<script setup>
import TimeListItem from './TimeListItem.vue';

const { times } = defineProps({
  times: Array
})

function plus2(timeIndex) {

}

function dnf(timeIndex) {

}

function deleteTime(timeIndex) {

}
</script>


