<template>
  <div class="min-h-full">
    <SessionContinueModalVue v-if="showModal" @closeModal="closeModal" />
    <SolveDetailsModal ref="solveDetailsModal" />
    <Disclosure as="nav" class="bg-gray-800" v-slot="{ open }">
      <div class="mx-auto max-w-full px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <img class="h-8 w-8" src="https://cdn-icons-png.flaticon.com/512/3430/3430812.png" alt="Speedcube Timer" />
            </div>
            <div class="hidden md:block">
              <div class="ml-10 flex items-baseline space-x-4">
              </div>
            </div>
          </div>
          <div class="hidden md:block">
            <div class="ml-4 flex items-center md:ml-6">

              <!-- Profile dropdown -->
              <Menu as="div" class="relative ml-3">
                <div>
                  <MenuButton
                    class="relative flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                    <span class="absolute -inset-1.5" />
                    <span class="sr-only">Open user menu</span>
                    <img class="h-8 w-8 rounded-full" :src="user.image" alt="profile_image" />
                  </MenuButton>
                </div>
                <transition enter-active-class="transition ease-out duration-100"
                  enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100"
                  leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100"
                  leave-to-class="transform opacity-0 scale-95">
                  <MenuItems
                    class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                    <div class="m-auto text-left border-b-2 border-gray-400 w-[80%] mx-4 py-1">
                      <span class=" text-md font-semibold  ">
                        {{ user.nickname }}
                      </span>
                    </div>
                    <MenuItem v-slot="{ active }">
                    <router-link to="/profile" class="cursor-pointer"
                      :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">Profile</router-link>
                    </MenuItem>
                    <MenuItem v-slot="{ active }">
                    <a class="cursor-pointer"
                      :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">Sessions</a>
                    </MenuItem>
                    <MenuItem v-slot="{ active }">
                    <a class="cursor-pointer"
                      :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">Settings</a>
                    </MenuItem>
                    <MenuItem v-slot="{ active }">
                    <a @click="logout" class="cursor-pointer"
                      :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">Sign out</a>
                    </MenuItem>
                  </MenuItems>
                </transition>
              </Menu>
            </div>
          </div>
          <div class="-mr-2 flex md:hidden">
            <!-- Mobile menu button -->
            <DisclosureButton
              class="relative inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
              <span class="absolute -inset-0.5" />
              <span class="sr-only">Open main menu</span>
              <Bars3Icon v-if="!open" class="block h-6 w-6" aria-hidden="true" />
              <XMarkIcon v-else class="block h-6 w-6" aria-hidden="true" />
            </DisclosureButton>
          </div>
        </div>
      </div>

      <DisclosurePanel class="md:hidden">
        <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
          <DisclosureButton v-for="item in navigation" :key="item.name" as="a" :href="item.href"
            :class="[item.current ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white', 'block rounded-md px-3 py-2 text-base font-medium']"
            :aria-current="item.current ? 'page' : undefined">{{ item.name }}</DisclosureButton>
        </div>
        <div class="border-t border-gray-700 pb-3 pt-4">
          <div class="flex items-center px-5">
            <div class="flex-shrink-0">
              <img class="h-10 w-10 rounded-full" :src="user.imageUrl" alt="" />
            </div>
            <div class="ml-3">
              <div class="text-base font-medium leading-none text-white">{{ user.name }}</div>
              <div class="text-sm font-medium leading-none text-gray-400">{{ user.email }}</div>
            </div>
            <button type="button"
              class="relative ml-auto flex-shrink-0 rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
              <span class="absolute -inset-1.5" />
              <span class="sr-only">View notifications</span>
              <BellIcon class="h-6 w-6" aria-hidden="true" />
            </button>
          </div>
          <div class="mt-3 space-y-1 px-2">
            <DisclosureButton v-for="item in userNavigation" :key="item.name" as="a" :href="item.href"
              class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">{{
                item.name }}</DisclosureButton>
          </div>
        </div>
      </DisclosurePanel>
    </Disclosure>

    <header class="bg-white shadow">
      <div class="mx-auto max-w-full px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold tracking-tight text-gray-900 text-center">Timer</h1>
      </div>
    </header>
    <main class="">
      <div class="mx-auto max-w-screen-2xl py-6 sm:px-6 lg:px-8">
        <!-- Scramble -->
        <ScrambleComponent ref="scrambleComponent" @generateScrambleView="generateScrambleView" />

        <!-- Timer -->
        <TimerComponent @saveTime="saveTime" @generateNewScramble="generateNewScramble" ref="timerComponent" />

        <!-- Times, Statistics and Scramble-->
        <div class=" grid sm:grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4 max-w-screen-xl mx-auto ">
          <TimesComponent @updateStats="updateStats" @showSolveDetails="showSolveDetails"
            class="min-w-fit  bg-[#7f7f88]" />
          <StatisticsComponent ref="statisticsComponent" class="min-w-fit  bg-[#7f7f88]" />
          <ScramblePreviewComponent ref="scrambleViewComponent"
            class="max-sm:w-[38rem] hidden lg:block cursor-pointer  bg-[#7f7f88]" @click="generateNewScramble" />
        </div>
        <div class="text-right mt-5 max-w-screen-xl m-auto">
          <button @click="endSession" class="py-2 px-3 bg-red-500 font-semibold text-xl hover:bg-red-600">
            End Session
          </button>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { Bars3Icon, BellIcon, XMarkIcon } from '@heroicons/vue/24/outline'

import { useStore } from "vuex"
import { ref } from "vue"
import { useRouter } from "vue-router"
import StatisticsComponent from '../components/StatisticsComponent.vue'
import TimesComponent from '../components/TimesComponent.vue'
import ScramblePreviewComponent from '../components/scramble/ScramblePreviewComponent.vue'
import ScrambleComponent from '../components/scramble/ScrambleComponent.vue'
import TimerComponent from '../components/TimerComponent.vue'
import SessionContinueModalVue from '../components/modals/SessionContinueModal.vue'
import SolveDetailsModal from '../components/modals/SolveDetailsModal.vue'

const store = useStore()
const router = useRouter()

const user = store.state.user.data


// Refs for components
const scrambleComponent = ref(null)
const scrambleViewComponent = ref(null)
const statisticsComponent = ref(null)
const solveDetailsModal = ref(null)
const timerComponent = ref(null)

const showModal = ref(false)

store.dispatch('getSessionId')

let sessionHash = store.state.session.hash

changeBgColor()

store.dispatch('loadSolves', sessionHash)
  .then((response) => {
    if (response) {
      showModal.value = true
    }
    updateStats()
  })


function changeBgColor() {
  document.body.style.backgroundColor = '#9C9CA6'
}


function logout() {
  store.dispatch('logout')
    .then(() => {
      router.push('/login')
    })
}

function generateNewScramble() {
  scrambleComponent.value.generateNewScramble()
}

function generateScrambleView() {
  if (scrambleViewComponent.value) {
    scrambleViewComponent.value.generateCubeStructure()
  }
}

function updateStats() {
  statisticsComponent.value.calculateStats()
}

// Store time in state, session and database
function saveTime() {
  let time = store.state.currentSolve.time
  let scramble = store.state.currentSolve.scramble
  store.state.session.times.push({ hash: null, time: time, scramble: scramble, plus2: 0, dnf: 0 })
  updateStats()
  store.dispatch("saveSolve", { time: time, scramble: scramble })
    .then(response => {
      store.state.session.times[store.state.session.times.length - 1].hash = response.data
    })
}

function endSession() {
  store.dispatch('createNewSessionId')
    .then(() => {
      sessionHash = store.state.session.hash
      store.dispatch('loadSolves', sessionHash)
        .then(() => {
          updateStats()
          timerComponent.value.resetTimer()
        })
    })
}

function closeModal(decision) {
  if (decision == 'yes') {
    showModal.value = false
  }
  if (decision == 'no') {
    store.dispatch('createNewSessionId')
      .then(() => {
        sessionHash = store.state.session.hash
        store.dispatch('loadSolves', sessionHash)
          .then(() => {
            updateStats()
            showModal.value = false
          })
      })
  }
}

function showSolveDetails(index) {
  solveDetailsModal.value.showSolveDetails(index)
}


</script>

<style>
body {
  background-color: #9C9CA6;
}
</style>