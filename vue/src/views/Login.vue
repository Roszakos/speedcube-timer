<template>
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    <img class="mx-auto h-10 w-auto" src="https://cdn-icons-png.flaticon.com/512/3430/3430812.png"
      alt="Speedcube Timer" />
    <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Sign in to your account</h2>
  </div>

  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
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
            <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-500">Forgot password?</a>
          </div>
        </div>
        <div class="mt-2">
          <input id="password" name="password" type="password" autocomplete="current-password" required
            v-model="data.password"
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
        </div>
      </div>

      <div>
        <button type="submit"
          class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign
          in</button>
      </div>
    </form>

    <p class="mt-10 text-center text-sm text-gray-500">
      Don't have an account yet?
      <router-link :to="{ name: 'Register' }" class="text-indigo-700 font-semibold">Sign up for free</router-link>
    </p>
  </div>
</template>


<script setup>
import { useStore } from "vuex"
import { ref } from "vue"
import { useRouter } from "vue-router";

const store = useStore()
const router = useRouter()

const data = ref({
  emailOrNick: '',
  password: ''
})

function login() {
  store.dispatch('login', data.value)
    .then(() => {
      router.push('/')
    })
}

</script>

