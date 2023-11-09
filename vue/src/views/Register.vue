<template>
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    <img class="mx-auto h-10 w-auto" src="https://cdn-icons-png.flaticon.com/512/3430/3430812.png"
      alt="Speedcube Timer" />
    <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Register for free</h2>
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

    <form class="space-y-6" @submit.prevent="register()">
      <div>
        <div class="flex items-center justify-between">
          <label for="nickname" class="block text-sm font-medium leading-6 text-gray-900">Nickname</label>
        </div>
        <div class="mt-2">
          <input id="nickname" name="nickname" type="text" autocomplete="current-nickname" v-model="data.nickname"
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
        </div>
      </div>

      <div>
        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
        <div class="mt-2">
          <input id="email" name="email" type="email" autocomplete="email" v-model="data.email"
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
        </div>
      </div>

      <div>
        <div class="flex items-center justify-between">
          <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
        </div>
        <div class="mt-2">
          <input id="password" name="password" type="password" autocomplete="current-password" v-model="data.password"
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
        </div>
      </div>

      <div>
        <div class="flex items-center justify-between">
          <label for="password_confirmation" class="block text-sm font-medium leading-6 text-gray-900">Confirm
            Password</label>
        </div>
        <div class="mt-2">
          <input id="password_confirmation" name="password_confirmation" type="password"
            v-model="data.password_confirmation" autocomplete="current-password_confirmation"
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
        </div>
      </div>

      <div>
        <button type="submit" :disabled="loading" :class="[loading ? 'cursor-not-allowed' : '']"
          class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
          <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
          </svg>
          Sign up
        </button>
      </div>
    </form>

    <p class="mt-10 text-center text-sm text-gray-500">
      Already have an account?
      <router-link :to="{ name: 'Login' }" class="text-indigo-700 font-semibold">Sign in</router-link>
    </p>
  </div>
</template>


<script setup>
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import { ref } from 'vue'
import { XCircleIcon } from '@heroicons/vue/24/outline';

const store = useStore()
const router = useRouter()

const errors = ref([])

const loading = ref(false)

const data = ref({
  nickname: '',
  email: '',
  password: '',
  password_confirmation: ''
})

changeBgColor()

function register() {
  loading.value = true
  store.dispatch('register', data.value)
    .then(() => {
      loading.value = false
      router.push({
        name: 'EmailVerify'
      })
    })
    .catch(err => {
      errors.value = []
      Object.keys(err.response.data.errors).forEach((attribute) => {
        err.response.data.errors[attribute].forEach((error) => {
          errors.value.push(error)
        })
      })
      loading.value = false
    })
}

function hideErrors() {
  errors.value = []
}

function changeBgColor() {
  document.body.style.backgroundColor = 'rgb(224, 200, 141)'
}
</script>

