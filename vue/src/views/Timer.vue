<template>
  <GlobalEvents @keydown.space="handleSpaceDown" @keyup.space="handleSpaceUp" />
  <div class="min-h-full">
    <Disclosure as="nav" class="bg-gray-800" v-slot="{ open }">
      <div class="mx-auto max-w-full px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <img class="h-8 w-8" src="https://cdn-icons-png.flaticon.com/512/3430/3430812.png" alt="Speedcube Timer" />
            </div>
            <div class="hidden md:block">
              <div class="ml-10 flex items-baseline space-x-4">
                <a v-for="item in navigation" :key="item.name" :href="item.href"
                  :class="[item.current ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white', 'rounded-md px-3 py-2 text-sm font-medium']"
                  :aria-current="item.current ? 'page' : undefined">{{ item.name }}</a>
              </div>
            </div>
          </div>
          <div class="hidden md:block">
            <div class="ml-4 flex items-center md:ml-6">
              <button type="button"
                class="relative rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                <span class="absolute -inset-1.5" />
                <span class="sr-only">View notifications</span>
                <BellIcon class="h-6 w-6" aria-hidden="true" />
              </button>

              <!-- Profile dropdown -->
              <Menu as="div" class="relative ml-3">
                <div>
                  <MenuButton
                    class="relative flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                    <span class="absolute -inset-1.5" />
                    <span class="sr-only">Open user menu</span>
                    <img class="h-8 w-8 rounded-full" :src="user.imageUrl" alt="" />
                  </MenuButton>
                </div>
                <transition enter-active-class="transition ease-out duration-100"
                  enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100"
                  leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100"
                  leave-to-class="transform opacity-0 scale-95">
                  <MenuItems
                    class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                    <!-- <MenuItem v-for="item in userNavigation" :key="item.name" v-slot="{ active }">
                    <a :href="item.href"
                      :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">{{ item.name }}</a>
                    </MenuItem> -->
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
        <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold tracking-tight text-gray-900 text-center">Timer</h1>
      </div>
    </header>
    <main>
      <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <!-- Scramble -->
        <div @click="generateNewScramble"
          class="text-1xl text-center cursor-pointer mt-9 lg:text-3xl md:text-2xl font-semibold select-none">
          {{ scramble }}
        </div>

        <!-- Timer -->
        <div @mousedown="handleMouseDown" @mouseup="handleMouseUp" class="text-center mt-9 w-full py-20 cursor-pointer">
          <span class="text-5xl lg:text-9xl md:text-7xl font-sans select-none"
            :class="{ 'text-red-500': preparingTimer, 'text-green-500': canStartTimer }">
            <span id="hours" v-if="hours" class="px-4">{{ hours }}</span>
            <span id="fC" v-if="firstColon">{{ firstColon }}</span>
            <span id="minutes" v-if="minutes" class="px-4">{{ minutes }}</span>
            <span id="sC" v-if="secondColon">{{ secondColon }}</span>
            <span id="seconds" v-if="seconds" class="px-4">{{ seconds }}</span>
            <span id="basicTime" v-if="basicTime">{{ basicTime }}</span>

          </span>
        </div>

        <!-- Times -->
        <div class="container">
          <div v-if="times.length" v-for="x in times.length" :key="x">
            <div>
              <span class="font-bold text-indigo-600">{{ times.length - (x - 1) }}. {{ displaySavedTime(times[times.length
                - x].time) }}
              </span> -
              {{ times[times.length - x].scramble }}

            </div>
          </div>
        </div>
        {{ sessionHash }}
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

const store = useStore()
const router = useRouter()

const user = {
  name: 'Tom Cook',
  email: 'tom@example.com',
  imageUrl:
    'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
}
const navigation = [
  // { name: 'Dashboard', href: '#', current: true },
  // { name: 'Team', href: '#', current: false },
  // { name: 'Projects', href: '#', current: false },
  // { name: 'Calendar', href: '#', current: false },
  // { name: 'Reports', href: '#', current: false },
]
const userNavigation = [
  { name: 'Your Profile', href: '#' },
  { name: 'Settings', href: '#' },
  { name: 'Sign out', href: '#' },
]

let startTime = 0
let endTime = 0
let nowSolving = ref(false)
let timeInterval;

let basicTime = ref(null)

let seconds = ref('0.00')
let minutes = ref(null)
let hours = ref(null)
let firstColon = ref(null)
let secondColon = ref(null)


// Properties for scrambling algorithm
const moves = store.state.scrambler.moves
const movesExcludedBy = store.state.scrambler.movesExcludedBy
const excludeSustainedBy = store.state.scrambler.excludeSustainedBy

let scramble = ref('')


let preparationStart = 0
let preparingTimer = ref(false)
let canStartTimer = ref(false)

store.dispatch('getSessionId')

const times = store.state.session.times
const sessionHash = store.state.session.hash



generateNewScramble()

function logout() {
  store.dispatch('logout')
    .then(() => {
      router.push('/login')
    })
}

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
    preparationStart = 0
    // Clear all displayed variables
    basicTime.value = null
    hours.value = null
    minutes.value = null
    firstColon.value = null
    secondColon.value = null
    seconds.value = 0

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

    saveTime(endTime - startTime, scramble.value)

    generateNewScramble()
  }
}

function displayTime(miliseconds) {
  seconds.value = ((endTime - startTime) / 1000)
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

function generateNewScramble() {
  let lastMove = generateMove(moves)
  let oneBeforeLast;
  let tempScramble = lastMove
  let excluded = movesExcludedBy[lastMove.charAt(0)]

  let availableMoves = moves.filter(move => !excluded.includes(move))

  for (let i = 0; i < 19; i++) {
    oneBeforeLast = lastMove
    lastMove = generateMove(availableMoves)
    tempScramble = tempScramble + ' ' + lastMove

    // Update moves that shouldn't be generated after last 2 moves
    if (excluded.length == 6) {
      excluded = movesExcludedBy[oneBeforeLast.charAt(0)]
    }
    if (excludeSustainedBy[oneBeforeLast.charAt(0)] == lastMove.charAt(0)) {
      excluded = excluded.concat(movesExcludedBy[lastMove.charAt(0)])
    } else {
      excluded = movesExcludedBy[lastMove.charAt(0)]
    }

    availableMoves = moves.filter(move => !excluded.includes(move))
  }

  scramble.value = tempScramble
}

function generateMove(availableMoves) {
  return availableMoves[Math.floor(Math.random() * availableMoves.length)]
}

function resetTimer() {
  seconds.value = '0.00'
  minutes.value = null
  hours.value = null
  firstColon.value = null
  secondColon.value = null
}

// Store time in state, session and DB
function saveTime(time, scramble) {
  store.state.session.times.push({ time: time, scramble: scramble })
  store.dispatch("saveSolve", { time: time, scramble: scramble }).then(response => { console.log(response) })
}
</script>