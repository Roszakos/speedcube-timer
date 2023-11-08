
<template>
  <div class="basis-3/4 lg:mt-10 h-full">
    <span class="text-4xl font-bold">
      Change Password
    </span>
    <div class="m-auto w-[80%] h-[90%] mt-12 relative">
      <form @submit.prevent="changePassword" class="text-left">
        <label for="current_password">
          <span class="inline-block py-2 text-lg font-bold">Current Password</span>
          <input type="password" v-model="data.currentPassword" class="w-full rounded-md">
        </label>
        <label for="newPassword">
          <span class="inline-block py-2 text-lg font-bold">New Password</span>
          <input type="password" v-model="data.newPassword" class="w-full rounded-md">
        </label>
        <label for="newPassword_confirmation">
          <span class="inline-block py-2 text-lg font-bold">Confirm Password</span>
          <input type="password" v-model="data.newPassword_confirmation" class="w-full rounded-md">
        </label>
        <div class="text-right w-full mt-6">
          <button type="submit" class="bg-gray-300 px-5 py-2 border-2 border-black rounded-md hover:bg-gray-400">
            Change Password
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import store from '../../store';

let data = ref({
  currentPassword: null,
  newPassword: null,
  newPassword_confirmation: null
})

function changePassword() {
  store.dispatch('changePassword', data.value)
}
</script>


