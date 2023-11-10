
<template>
  <div class="basis-3/4 lg:mt-10 h-full">
    <span class="text-4xl font-bold">
      Account Settings
    </span>
    <div class="m-auto w-[80%] h-[90%] mt-12 relative">
      <form @submit.prevent="updateUser" v-if="user" class="text-left">
        <UserImageComponent :image="userModel.image" @imageChange="setNewImage" />
        <label for="nickname">
          <span class="inline-block py-2 text-lg font-bold">Nickname</span>
          <input type="text" v-model="userModel.nickname" class="w-full rounded-md">
        </label>
        <label for="email">
          <span class="inline-block py-2 text-lg font-bold">Email</span>
          <input type="email" v-model="userModel.email" class="w-full rounded-md">
        </label>
        <label for="firstName">
          <span class="inline-block py-2 text-lg font-bold">First Name</span>
          <input type="text" v-model="userModel.first_name" class="w-full rounded-md">
        </label>
        <label for="lastName">
          <span class="inline-block py-2 text-lg font-bold">Last Name</span>
          <input type="text" v-model="userModel.last_name" class="w-full rounded-md">
        </label>
        <div class="text-right w-full mt-6">
          <button type="submit" class="bg-gray-300 px-5 py-2 border-2 border-black rounded-md hover:bg-gray-400">
            Update
          </button>
        </div>

      </form>
      <div v-if="errors.length" class="border-red-600 border-4 bg-red-400 font-bold text-md py-2 mt-4 rounded-md">
        {{ errors[0] }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import UserImageComponent from './UserImageComponent.vue';
import store from '../../store';

const props = defineProps({
  user: Object
})

const emit = defineEmits(['notify'])

const errors = ref([])

let userModel = ref({
  nickname: props.user.nickname,
  email: props.user.email,
  first_name: props.user.first_name ?? null,
  last_name: props.user.last_name ?? null,
  image: props.user.image ?? null
})

function setNewImage(image) {
  userModel.value.image = image
}

function updateUser() {
  errors.value = []
  store.dispatch('updateUser', userModel.value)
    .then(() => {
      emit('notify', 'Data was succesfully updated')
    })
    .catch(err => {
      Object.keys(err.response.data.errors).forEach((attribute) => {
        err.response.data.errors[attribute].forEach((error) => {
          errors.value.push(error)
        })
      })
    })
}
</script>



