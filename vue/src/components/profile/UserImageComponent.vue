
<template>
  <label v-if="!image" for="profileImage">
    <div
      class="bg-gray-400/50 rounded-full flex flex-col items-center justify-center group relative w-[130px] h-[130px] cursor-pointer">
      <div
        class="bg-black/30 rounded-full hidden group-hover:flex w-full h-full items-center justify-center text-center z-10">
        <span class="text-xl text-black font-semibold w-full">Add Image</span>
      </div>
      <UserIcon class="w-[50%] h-[50%] text-gray-500 absolute group-hover:text-gray-400/80" />
    </div>
    <input type="file" id="profileImage" class="hidden" @change="showChosenImage"
      accept="image/png, image/jpeg, image/jpg, image/gif" />
  </label>
  <label v-else for="profileImage">
    <div
      class="bg-gray-400/50 rounded-full flex flex-col items-center justify-center group relative w-[130px] h-[130px] cursor-pointer">
      <div
        class="bg-black/30 rounded-full hidden group-hover:flex w-full h-full items-center justify-center text-center z-10">
        <span class="text-xl text-black font-semibold w-full">Change Image</span>
      </div>
      <img :src="image" alt="profile_image" class="w-[130px] h-[130px] rounded-full absolute group-hover:opacity-25">
    </div>
    <input type="file" id="profileImage" class="hidden" @change="showChosenImage" />
  </label>
</template>

<script setup>
import { UserIcon } from '@heroicons/vue/24/outline'
import { ref } from 'vue'

const emit = defineEmits(['imageChange'])

let profileImage = ref(null)

let { image } = defineProps({
  image: String,
})

function showChosenImage(ev) {
  if (ev.target.files[0]) {
    const file = ev.target.files[0]

    const reader = new FileReader()
    reader.onload = () => {
      profileImage.value = reader.result
      emit('imageChange', reader.result)
    }

    reader.readAsDataURL(file)
  }
}

</script>

