
<template>
  <!-- <div class="basis-1/5">1</div>
  <div class="h-full border-r-2 border-black"></div>
  <div class="basis-1/5">2</div>
  <div class="h-full border-r-2 border-black"></div>
  <div class="basis-1/5">3</div>
  <div class="h-full border-r-2 border-black"></div>
  <div class="basis-1/5">4</div>
  <div class="h-full border-r-2 border-black"></div>
  <div class="basis-1/5">5</div>
  <div class="h-full border-r-2 border-black"></div> -->
  <div v-for="page in pages" :class="pagination.currentPage == page ? 'bg-sky-500' : ''"
    class="basis-1/5 h-full flex items-center justify-center hover:bg-sky-500" @click="pageChange(page)">
    <div class="w-full">{{ page }}</div>
    <div class="h-full border-r-2 border-black"></div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  pagination: Object,
})
const pages = ref([])
if (props.pagination.currentPage == 1) {
  for (let i = 1; i <= props.pagination.numberOfPages; i++) {
    pages.value.push(i)
  }
}
console.log(pages.value)


const emit = defineEmits(['pageChange'])

function pageChange(page) {
  emit('pageChange', page)
}
</script>
