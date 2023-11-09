
<template>
  <div class="basis-3/4 lg:mt-10 h-full">
    <span class="text-4xl font-bold">
      Overview
    </span>
    <div class="m-auto w-[80%] h-[90%] mt-12 relative">
      <AccountDeleteConfirmationModal v-if="showDeleteConfirmation" @closeModal="deleteAccountConfirmed" />
      <form @submit.prevent="deleteAccount" class="text-left">
        <label for="password">
          <span class="inline-block py-2 text-lg font-bold">Password</span>
          <input type="password" v-model="password" class="w-full rounded-md">
        </label>
        <div class="text-right mt-5">
          <button type="submit" class="bg-red-500 px-5 py-2  border-2 border-black rounded-md hover:bg-red-700">
            Delete Account
          </button>
        </div>
      </form>
      <div v-if="errors.length" class="border-red-600 border-4 bg-red-400 font-bold text-md py-2 mt-4 rounded-md">
        {{ errors[0] }}
      </div>
      <span class="text-red-500 font-bold text-md absolute bottom-9 left-0 right-0 text-center italic">
        Note: this action can't be undone
      </span>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import AccountDeleteConfirmationModal from '../modals/AccountDeleteConfirmationModal.vue';
import store from '../../store';

let password = ref(null)
let showDeleteConfirmation = ref(false)

const errors = ref([])

function deleteAccount() {
  showDeleteConfirmation.value = true
}

function deleteAccountConfirmed(decision) {
  errors.value = []
  if (decision == 'delete') {
    showDeleteConfirmation.value = false
    store.dispatch('deleteAccount', { password: password.value })
      .then(response => {
        if (response == 1) {
          router.push('/login')
        }
      })
      .catch(err => {
        Object.keys(err.response.data.errors).forEach((attribute) => {
          err.response.data.errors[attribute].forEach((error) => {
            errors.value.push(error)
          })
        })
      })
  } else if (decision == 'cancel') {
    showDeleteConfirmation.value = false
  }
}
</script>


