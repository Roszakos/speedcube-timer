
<template>
  <div id="leftTopCorner" class="w-full h-full border-[1px] cubesm:border-2 md:border-4 border-black" :class="lTC">
  </div>
  <div id="topEdge" class="w-full h-full border-[1px] cubesm:border-2 md:border-4 border-black" :class="tE"></div>
  <div id="rightTopCorner" class="w-full h-full border-[1px] cubesm:border-2 md:border-4 border-black" :class="rTC">
  </div>

  <div id="leftEdge" class="w-full h-full border-[1px] cubesm:border-2 md:border-4 border-black" :class="lE"></div>
  <div id="center" class=" w-full h-full border-[1px] cubesm:border-2 md:border-4 border-black" :class="center">
  </div>
  <div id="rightEdge" class="w-full h-full border-[1px] cubesm:border-2 md:border-4 border-black" :class="rE"></div>

  <div id="leftDownCorner" class="w-full h-full border-[1px] cubesm:border-2 md:border-4 border-black" :class="lDC">
  </div>
  <div id="downEdge" class="w-full h-full border-[1px] cubesm:border-2 md:border-4 border-black" :class="dE"></div>
  <div id="rightDownCorner" class="w-full h-full border-[1px] cubesm:border-2 md:border-4 border-black" :class="rDC">
  </div>
</template>

<script setup>
const { } = defineProps({
  lTC: String,
  tE: String,
  rTC: String,
  lE: String,
  center: String,
  rE: String,
  lDC: String,
  dE: String,
  rDC: String,
})
</script>
