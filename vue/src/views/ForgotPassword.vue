<template>
  <div class="font-bold text-3xl w-full text-center">
    Reset Your Password
  </div>
  <form @submit.prevent="sendResetLink" class="m-auto mt-14 text-2xl w-[40%]">
    <label for="email" class="block py-1 font-semibold">Your email</label>
    <input type="email" v-model="email" class="block w-full rounded-md py-4" />
    <button type="submit" :disabled="loading" :class="[loading ? 'cursor-not-allowed' : '']" class="flex justify-center items-center w-full text-center py-2 mt-4 rounded-md bg-indigo-600
        hover:bg-indigo-700 text-white font-semibold">
      <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
        fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor"
          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
        </path>
      </svg>
      Reset Password
    </button>
  </form>
  <div v-if="linkSent == 'success'" class="w-full mt-5 text-xl text-center text-green-600 font-semibold">
    <div>Password reset link has been sent to
      <span class="font-bold">{{ responseEmail }}</span>
    </div>
  </div>
  <div v-if="linkSent == 'fail'" class="w-full mt-5 text-xl text-center text-red-500 font-semibold">
    <div>Invalid form data. Couldn't send password reset link.</div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useStore } from 'vuex'

const store = useStore()

const loading = ref(false)
const linkSent = ref()
const email = ref('')
const responseEmail = ref('')

changeBgColor()

function sendResetLink() {
  loading.value = true
  store.dispatch('sendPasswordResetLink', email.value)
    .then((response) => {
      loading.value = false
      if (response.status === 200) {
        linkSent.value = 'success'
        responseEmail.value = response.data.email
      } else {
        linkSent.value = 'fail'
      }
    })
}

function changeBgColor() {
  document.body.style.backgroundColor = 'rgb(224, 200, 141)'
}
</script>


