<template>
  <div class="basis-3/4 mt-10 h-full">
    <span class="text-4xl font-bold">
      Your Solving Sessions
    </span>
    <div class="m-auto w-[80%] h-[90%] mt-12 relative">
      <div class="flex font-bold text-sm px-1 mt-12 justify-between text-left pb-2">
        <span class="w-[26%]">Session hash</span>
        <span class="w-[13%]">Puzzle</span>
        <span class="w-[13%]">Solves</span>
        <span class="w-[13%]">Best Time</span>
        <span class="w-[13%]">Worst Time</span>
        <span class="w-[13%]">Average</span>
        <span class="w-[13%]">Details</span>
      </div>
      <div v-if="props.sessions.length > 10">
        <SessionsListItem v-for="item in Pagination.showItems" :session="sessions[item]" />
      </div>
      <div v-else>
        <SessionsListItem v-for="x in props.sessions.length" :session="sessions[x - 1]" />
      </div>

      <!-- Pagination -->
      <div v-if="Pagination.numberOfPages > 1">
        <div class="flex m-auto w-full absolute bottom-6 h-10 justify-between items-center">
          <div class="hover:text-sky-500 h-full flex items-center justify-center cursor-pointer p-3 text-2xl"
            @click="pageChange('previous')">
            «
          </div>
          <span>Showing {{ Pagination.firstShownItem }} - {{ Pagination.lastShownItem }}
            of {{
              props.sessions.length }}
          </span>
          <div class=" hover:text-sky-500 h-full p-3 text-2xl flex items-center justify-center cursor-pointer"
            @click="pageChange('next')">
            »
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import SessionsListItem from './SessionsListItem.vue';

const props = defineProps({
  sessions: Array
})

const Pagination = reactive({
  numberOfPages: Math.ceil(props.sessions.length / 10),
  showItems: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
  firstShownItem: '1',
  lastShownItem: '10',
  currentPage: 1,
  previousPage: 1,
  nextPage: 2,
})

function pageChange(page) {
  if (page == 'previous') {
    let current = Pagination.currentPage
    Pagination.currentPage = Pagination.previousPage
    Pagination.nextPage = current
    if (Pagination.currentPage == 1) {
      Pagination.previousPage = Pagination.currentPage
      Pagination.nextPage = 2
    } else if (Pagination.currentPage > 1) {
      Pagination.previousPage = Pagination.currentPage - 1
    }
  } else if (page == 'next') {
    let current = Pagination.currentPage
    Pagination.currentPage = Pagination.nextPage
    Pagination.previousPage = current
    if (Pagination.currentPage == Pagination.numberOfPages) {
      Pagination.previousPage = Pagination.numberOfPages - 1
      Pagination.nextPage = Pagination.currentPage
    } else if (Pagination.currentPage < Pagination.numberOfPages) {
      Pagination.nextPage = Pagination.currentPage + 1
    }
  }

  Pagination.showItems = []

  if (Pagination.currentPage != Pagination.numberOfPages) {
    for (let i = ((Pagination.currentPage - 1) * 10); i < Pagination.currentPage * 10; i++) {
      Pagination.showItems.push(i)
    }
  } else {
    for (let i = ((Pagination.currentPage - 1) * 10); i < props.sessions.length; i++) {
      Pagination.showItems.push(i)
    }
  }
  Pagination.firstShownItem = Pagination.showItems[0] + 1
  Pagination.lastShownItem = Pagination.showItems[Pagination.showItems.length - 1] + 1
}


</script>


