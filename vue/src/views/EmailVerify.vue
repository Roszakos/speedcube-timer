
<template>
  <div v-if="url" class="md:text-6xl text-3xl m-auto mt-40 text-center font-bold">
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
    <div v-if="success">
      <div>Your email has been successfully verified</div>
      <div class="md:text-4xl text-xl mt-4">You will be redirected to the main page...</div>
    </div>
  </div>
  <div v-if="wrongUrl" class="md:text-6xl text-3xl m-auto mt-40 text-center font-bold">
    <div>Verification link is not correct. </div>
    <div class="text-xl md:text-3xl justify-center font-semibold mt-10 gap-3 flex">
      <div class="animate-spin">
        <svg class="w-10 h-10" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" opacity=".25" />
          <path
            d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"
            class="spinner_z9k8" />
        </svg>
      </div>
      You will be redirected to the main page...
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useStore } from 'vuex';
import { useRoute } from 'vue-router'
import router from '../router';

const store = useStore()
const route = useRoute()

const loading = ref(false)
const url = ref(false)
const success = ref(false)
const wrongUrl = ref(false)

if (route.query.url) {
  url.value = true
  loading.value = true
  store.dispatch('verifyEmail', route.query.url)
    .then((response) => {
      loading.value = false
      if (response.status === 200) {
        success.value = true
        store.state.user.verifyEmail = false
        setTimeout(() => {
          router.push({ name: 'Timer' })
        }, 3000)
      } else if (response.response.status === 403) {
        wrongUrl.value = true
        setTimeout(() => {
          router.push({ name: 'Timer' })
        }, 3000)
      }
    })
} else {
  wrongUrl.value = true
}
</script>

<style lang="scss" scoped></style>

