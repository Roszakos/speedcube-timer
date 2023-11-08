<template>
  <div @click="closeModal('cancel')" class="fixed left-0 top-0 bg-gray-500/50 w-full h-full z-[1]">
    <div @click.stop class="xl:w-[30%] lg:w-[50%] md:w-[80%] w-[90%] p-10 m-auto bg-white mt-32 text-center">
      <div class="text-left">
        <span class="text-2xl font-bold inline-block w-full border-b-2 pb-2">Confirm account delete</span>
        <span class="inline-block font-bold text-red-500 mt-3 text-lg w-full">Are you sure? </span>
        <span class="inline-block font-bold text-red-500 text-lg">If you delete your account there is no way to
          retrieve
          it</span>
      </div>

      <div class="m-auto font-bold mt-7">
        <button class="bg-gray-400 px-4 py-2 mr-4 ring-1 ring-black" @click="closeModal('cancel')">Cancel</button>
        <button class="bg-red-500 px-4 py-2 ml-4 ring-1 ring-black" @click="closeModal('delete')">Delete</button>
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

