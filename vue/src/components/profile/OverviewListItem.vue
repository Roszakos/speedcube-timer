
<template>
  <div
    class="bg-gradient-to-r from-sky-300/50 rounded-r-xl to-sky-200/30 h-[8%] text-xl text-left px-4 flex justify-between items-center font-bold">
    <span>{{ title }}</span>
    <span>{{ data }}</span>
  </div>
</template>

<script setup>

const props = defineProps({
  title: String,
  data: String | Number
})

</script>


