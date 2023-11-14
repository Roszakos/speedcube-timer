
<template>
  <div class="font-bold text-3xl w-full text-center">
    Reset Your Password
  </div>
  <div v-if="!wrongUrl" class="text-md m-auto w-[90%] sm:w-[420px] mt-10">
    <form @submit.prevent="resetPassword" class="w-full">
      <label for="email" class="block font-semibold">
        Email
      </label>
      <input type="email" name="email" v-model="model.email" class="w-full rounded-md">

      <label for="password" class="block font-semibold pt-4">
        Password
      </label>
      <input type="password" name="password" v-model="model.password" class="w-full rounded-md">

      <label for="password_confirmation" class="block font-semibold pt-4">
        Confirm Password
      </label>
      <input type="password" name="password_confirmation" v-model="model.password_confirmation" class="w-full rounded-md">

      <input type="hidden" :value="model.token" name="token" />

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
  </div>
  <div v-else class="font-semibold text-2xl mt-10 w-full text-center">
    <div>Your password reset link is not correct.</div>
    <div class="text-lg font-normal">You will be redirected to the login page...</div>
  </div>

  <div v-if="passwordResetStatus == 'success'" class="w-full mt-5 text-xl text-center text-green-500 font-semibold">
    <div>New password has been successfully set.</div>
    <div class="text-black text-lg font-normal">You will be redirected to the login page...</div>
  </div>
  <div v-if="passwordResetStatus == 'fail'" class="w-full mt-5 text-xl text-center text-red-500 font-semibold">
    Invalid data. Your password could not be reset.
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex'

const store = useStore()
const route = useRoute()
const router = useRouter()

const model = ref({
  email: '',
  password: '',
  password_confirmation: '',
  token: ''
})

const loading = ref(false)
const passwordResetStatus = ref(null)
const wrongUrl = ref(false)

if (route.query.token) {
  model.value.token = route.query.token
} else {
  wrongUrl.value = true
}

changeBgColor()

function resetPassword() {
  loading.value = true
  store.dispatch('resetPassword', model.value)
    .then((response) => {
      loading.value = false
      if (response.status == 200) {
        if (response.data.success) {
          passwordResetStatus.value = 'success'
          setTimeout(() => {
            router.push({ name: 'Login' })
          }, 4000)
        } else {
          passwordResetStatus.value = 'fail'
        }
      } else {
        passwordResetStatus.value = 'fail'
      }
    })
}

function changeBgColor() {
  document.body.style.backgroundColor = 'rgb(224, 200, 141)'
}

</script>

<style lang="scss" scoped></style>

