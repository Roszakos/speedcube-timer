
<template>
  <div @click="closeModal('yes')" class="fixed left-0 top-0 bg-gray-500/50 w-full h-full z-[1]">
    <div @click.stop class="w-[30%] p-5 m-auto bg-white mt-24 text-left">
      <div class="mx-4">
        <span class="text-2xl font-bold inline-block w-full border-b-2 pb-2 text-left ">Continue Session</span>
        <span class="inline-block font-semibold text-xl mt-2">Do you want to continue your last solving session?</span>
      </div>
      <div class="m-auto font-bold mt-5 text-center">
        <button class="bg-green-500 px-7 py-2 mr-4 ring-1 ring-black" @click="closeModal('yes')">Yes</button>
        <button class="bg-red-500 px-7 py-2 ml-4 ring-1 ring-black" @click="closeModal('no')">No</button>
      </div>
    </div>
  </div>
</template>

<script setup>

const emit = defineEmits(['closeModal'])

function closeModal(decision) {
  emit('closeModal', decision)
}
</script>


