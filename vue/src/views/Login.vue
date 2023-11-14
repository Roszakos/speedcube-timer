<template>
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    <img class="mx-auto h-10 w-auto" src="https://cdn-icons-png.flaticon.com/512/3430/3430812.png"
      alt="Speedcube Timer" />
    <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Sign in to your account</h2>
  </div>

  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
    <div class="bg-red-500 font-semibold px-4 py-4 rounded-md mb-3" v-if="errors.length">
      <div class="w-full flex flex-row-reverse pt-1 pr-1">
        <XCircleIcon class="w-6 h-6 cursor-pointer" @click="hideErrors" />
      </div>
      <div v-for="(error, index) in errors" :key="index">
        <span>&#x2022 {{ error }}</span>
      </div>
    </div>

    <form class="space-y-6" @submit.prevent="login">
      <div>
        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Nickname or Email address</label>
        <div class="mt-2">
          <input id="emailOrNick" name="emailOrNick" type="text" autocomplete="emailOrNick" required
            v-model="data.emailOrNick"
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
        </div>
      </div>

      <div>
        <div class="flex items-center justify-between">
          <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
          <div class="text-sm">
            <router-link :to="{ name: 'ForgotPassword' }" class="font-semibold text-indigo-600 hover:text-indigo-500">
              Forgot password?
            </router-link>
          </div>
        </div>
        <div class="mt-2">
          <input id="password" name="password" type="password" autocomplete="current-password" required
            v-model="data.password"
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
        </div>
      </div>

      <div>
        <button type="submit" :disabled="loading" :class="[loading ? 'cursor-not-allowed' : '']"
          class="flex w-full justify-center items-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">

          <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
          </svg>
          Sign in
        </button>
      </div>
    </form>

    <p class="mt-10 text-center text-sm text-gray-700">
      Don't have an account yet?
      <router-link :to="{ name: 'Register' }" class="text-indigo-700 font-semibold">Sign up for free</router-link>
    </p>
  </div>
  <Notification />
</template>


<script setup>
import { useStore } from "vuex"
import { ref } from "vue"
import { useRouter } from "vue-router";
import { XCircleIcon } from "@heroicons/vue/24/outline";
import Notification from "../components/Notification.vue";

const store = useStore()
const router = useRouter()

const errors = ref([])

const data = ref({
  emailOrNick: '',
  password: ''
})

const loading = ref(false)

if (store.state.flashData.accountDeleted) {
  store.commit('notify', {
    type: 'success',
    message: 'Account was succesfully deleted'
  })
  store.state.flashData.accountDeleted = false
}

if (store.state.flashData.loggedOut) {
  store.commit('notify', {
    type: '',
    message: 'You have been logged out'
  })
  store.state.flashData.loggedOut = false
}

changeBgColor()

function login() {
  loading.value = true
  errors.value = []
  store.dispatch('login', data.value)
    .then((response) => {
      loading.value = false
      if (response.status === 200) {
        router.push({
          name: 'Timer'
        })
      } else if (response.response.status === 422) {
        errors.value = ['Provided credentials are not correct.']
      }
    })
}

function hideErrors() {
  errors.value = []
}

function changeBgColor() {
  document.body.style.backgroundColor = 'rgb(224, 200, 141)'
}

</script>

