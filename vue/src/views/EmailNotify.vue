
<template>
  <div
    class="m-auto bg-white p-5 md:mt-20 w-full h-[40rem] md:w-[70%] md:h-[25rem] flex items-center flex-col md:justify-center">
    <div class="text-xl sm:text-3xl pt-20 py-7">
      Verification link has been sent to
    </div>
    <div class="font-bold text-2xl sm:text-4xl ">{{ email }}</div>
    <div class="mt-14 text-lg" href="#">If you lost your verification link, click
      <span @click="resendEmailVerificationLink" class="underline text-blue-800 cursor-pointer font-semibold">here</span>.
    </div>
    <div v-if="loading" class="flex items-center justify-center flex-col h-[80%] w-full">
      <div class="animate-spin">
        <svg class="w-10 h-10" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" opacity=".25" />
          <path
            d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"
            class="spinner_z9k8" />
        </svg>
      </div>
    </div>
    <div v-if="resend" class="mt-10 tex-lg sm:text-xl font-semibold text-green-600">
      Verification link has been resend to your email
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import store from '../store';

const email = store.state.user.data.email

let resend = ref(false)
let loading = ref(false)

changeBgColor()

function resendEmailVerificationLink() {
  loading.value = true
  resend.value = false
  store.dispatch('resendEmailVerificationLink')
    .then((response) => {
      loading.value = false
      if (response.status === 200) {

        resend.value = true
        console.log(response.data)
        // if (response.data.email == 'sent') {

        // }
      }
    })
}

function changeBgColor() {
  document.body.style.backgroundColor = 'rgb(224, 200, 141)'
}
</script>
