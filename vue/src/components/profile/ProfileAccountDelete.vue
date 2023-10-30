
<template>
  <div>
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
    <span class="text-red-500 font-bold text-md absolute bottom-6 left-0 right-0 text-center italic">Note: this action
      can't be
      undone</span>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import AccountDeleteConfirmationModal from '../modals/AccountDeleteConfirmationModal.vue';
import store from '../../store';

let password = ref(null)
let showDeleteConfirmation = ref(false)

function deleteAccount() {
  showDeleteConfirmation.value = true
}

function deleteAccountConfirmed(decision) {
  if (decision == 'delete') {
    showDeleteConfirmation.value = false
    store.dispatch('deleteAccount', { password: password.value })
      .then(response => {
        if (response == 1) {
          router.push('/login')
        }
      })
  } else if (decision == 'cancel') {
    showDeleteConfirmation.value = false
  }
}
</script>


