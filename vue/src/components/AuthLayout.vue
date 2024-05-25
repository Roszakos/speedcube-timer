
<template>
  <div class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8">
    <router-view></router-view>
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <div class="mt-12 text-xl font-bold text-black text-center">
        OR
      </div>
      <div class="mt-6">
        <button @click="loginAsTestAccount" :disabled="loading" :class="[loading ? 'cursor-not-allowed' : '']"
            class="flex w-full justify-center items-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
              fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
              </path>
            </svg>
            Sign in with Test Account
          </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue"
import {useStore} from 'vuex'
import { useRouter} from 'vue-router'

const store = useStore();
const router = useRouter();

const loading = ref(false);

function loginAsTestAccount() {
  loading.value = true
  const credentials = {
    emailOrNick: 'test@example.com',
    password: 'TestPassword1'
  }
  console.log(credentials);
  store.dispatch('login', credentials)
    .then((response) => {
      loading.value = false
      if (response.status === 200) {
        router.push({
          name: 'Timer'
        })
      } else if (response.response.status === 422) {

      }
    })
}
</script>

