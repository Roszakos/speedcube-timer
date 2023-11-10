<template>
  <div class="min-h-full">
    <SessionContinueModalVue v-if="showModal" @closeModal="closeModal" />
    <SolveDetailsModal ref="solveDetailsModal" />
    <Disclosure as="nav" class="bg-slate-600" v-slot="{ open }">
      <div class="mx-auto max-w-full px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
          <div class="flex items-center">
            <div class="flex-shrink-0 flex">
              <img class="h-8 w-8" src="https://cdn-icons-png.flaticon.com/512/3430/3430812.png" alt="Speedcube Timer" />
              <span class="text-[#e3c376] ml-4 font-bold text-lg">SpeedcubeTimer.org</span>
            </div>
            <div class="hidden md:block">
              <div class="ml-10 flex items-baseline space-x-4">
              </div>
            </div>
          </div>
          <div class="">
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
                    <router-link to="/profile/overview" class="cursor-pointer"
                      :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">Profile</router-link>
                    </MenuItem>
                    <MenuItem v-slot="{ active }">
                    <router-link to="/profile/sessions" class="cursor-pointer"
                      :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">Sessions</router-link>
                    </MenuItem>
                    <MenuItem v-slot="{ active }">
                    <router-link to="/profile/settings" class="cursor-pointer border-b-2"
                      :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">Settings</router-link>
                    </MenuItem>
                    <MenuItem v-slot="{ active }">
                    <a @click="logout" class="cursor-pointer font-semibold"
                      :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">Sign out</a>
                    </MenuItem>
                  </MenuItems>
                </transition>
              </Menu>
            </div>
          </div>
        </div>
      </div>

      <DisclosurePanel class="md:hidden">
        <div class="border-t border-gray-700 pb-3 pt-4">
          <div class="flex items-center px-5">
            <div class="flex-shrink-0">
              <img class="h-10 w-10 rounded-full" :src="user.image" alt="profile_image" />
            </div>
            <div class="ml-3">
              <div class="text-base font-medium leading-none text-white">{{ user.name }}</div>
              <div class="text-sm font-medium leading-none text-gray-400">{{ user.email }}</div>
            </div>
          </div>
          <div class="mt-3 space-y-1 px-2">
            <DisclosureButton v-for="item in userNavigation" :key="item.name" as="a" :href="item.href"
              class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">{{
                item.name }}</DisclosureButton>
          </div>
        </div>
      </DisclosurePanel>
    </Disclosure>

    <main class="mt-14">
      <div class="mx-auto max-w-screen-2xl py-6 px-6 md:px-8 lg:px-10">
        <!-- Scramble -->
        <ScrambleComponent v-show="!isTimerStarted" ref="scrambleComponent"
          @generateScrambleView="generateScrambleView" />

        <!-- Timer -->
        <TimerComponent @saveTime="saveTime" @generateNewScramble="generateNewScramble" @timerStarted="changeTimerStatus"
          ref="timerComponent" />

        <!-- Times, Statistics and Scramble-->
        <div v-show="!isTimerStarted" class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4 max-w-screen-xl mx-auto min-h-fit max-md:max-w-[560px]
          ">
          <TimesComponent @updateStats="updateStats" @showSolveDetails="showSolveDetails"
            class="min-w-fit bg-[#7f7f88] shadow-[0_0_60px_15px_rgba(0,0,0,0.3)] animate-fade-in-down rounded-sm"
            ref="timesListComponent" />
          <StatisticsComponent ref="statisticsComponent"
            class="min-w-fit bg-[#7f7f88] shadow-[0_0_60px_15px_rgba(0,0,0,0.3)] animate-fade-in-down rounded-sm" />
          <ScramblePreviewComponent ref="scrambleViewComponent"
            class="lg:col-span-2 md:col-span-2 cursor-pointer rounded-sm shadow-[0_0_60px_15px_rgba(0,0,0,0.3)] bg-[#7f7f88] animate-fade-in-down min-[320px]:block"
            @click="generateNewScramble" />
        </div>
        <div v-show="!isTimerStarted" class="text-right mt-5 max-w-screen-xl m-auto max-md:max-w-[560px]">
          <button @click="endSession"
            class="lg:py-2 lg:px-3 py-1 px-2 bg-red-500 font-semibold text-lg lg:text-xl hover:bg-red-600 ring-1 ring-black">
            End Session
          </button>
        </div>
      </div>

    </main>
  </div>
  <Notification />
</template>

<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'

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
import Notification from '../components/Notification.vue'

const store = useStore()
const router = useRouter()

const user = store.state.user.data


// Refs for components
const scrambleComponent = ref(null)
const scrambleViewComponent = ref(null)
const statisticsComponent = ref(null)
const solveDetailsModal = ref(null)
const timerComponent = ref(null)
const timesListComponent = ref(null)

const showModal = ref(false)

changeBgColor()

store.dispatch('getSessionId')

let sessionHash = store.state.session.hash
let isTimerStarted = ref(false)

store.dispatch('loadSolves', sessionHash)
  .then((response) => {
    if (response === 401) {
      router.go('/login')
    } else if (response) {
      showModal.value = true
    }
    updateStats()
  })

function changeTimerStatus() {
  isTimerStarted.value = true
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
  isTimerStarted.value = false
  let time = store.state.currentSolve.time
  let scramble = store.state.currentSolve.scramble
  store.state.session.times.push({ hash: null, time: time, scramble: scramble, plus2: 0, dnf: 0 })
  timesListComponent.value.addRecentTime()
  updateStats()
  store.dispatch("saveSolve", { time: time, scramble: scramble })
    .then(response => {
      if (response.status === 200) {
        store.state.session.times[store.state.session.times.length - 1].hash = response.data
        store.commit('notify', {
          type: 'success',
          message: 'Time was saved in database'
        })
      }
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
          store.commit('notify', {
            type: 'success',
            message: 'Started new solving session'
          })
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
        store.commit('setSolves', [])
        updateStats()
        showModal.value = false
      })
  }
}

function showSolveDetails(index) {
  solveDetailsModal.value.showSolveDetails(index)
}

function changeBgColor() {
  document.body.style.backgroundColor = 'rgb(224, 200, 141)'
}


</script>
