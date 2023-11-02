
<template>
  <div @click="generateNewScramble"
    class="text-center cursor-pointer mt-6 lg:text-4xl md:text-3xl text-2xl font-semibold select-none">
    {{ scramble }}
  </div>
</template>

<script setup>
import { ref } from 'vue';
import store from '../../store';

defineExpose({
  generateNewScramble
})

const emit = defineEmits(['generateScrambleView'])

let scramble = ref('')
const moves = store.state.scrambler.moves
const movesExcludedBy = store.state.scrambler.movesExcludedBy
const excludeSustainedBy = store.state.scrambler.excludeSustainedBy

generateNewScramble()

function generateNewScramble() {
  let lastMove = generateMove(moves)
  let oneBeforeLast;
  let tempScramble = lastMove
  let excluded = movesExcludedBy[lastMove.charAt(0)]

  let availableMoves = moves.filter(move => !excluded.includes(move))

  for (let i = 0; i < 19; i++) {
    oneBeforeLast = lastMove
    lastMove = generateMove(availableMoves)
    tempScramble = tempScramble + ' ' + lastMove

    // Update moves that shouldn't be generated after last 2 moves
    if (excluded.length == 6) {
      excluded = movesExcludedBy[oneBeforeLast.charAt(0)]
    }
    if (excludeSustainedBy[oneBeforeLast.charAt(0)] == lastMove.charAt(0)) {
      excluded = excluded.concat(movesExcludedBy[lastMove.charAt(0)])
    } else {
      excluded = movesExcludedBy[lastMove.charAt(0)]
    }

    availableMoves = moves.filter(move => !excluded.includes(move))
  }

  scramble.value = tempScramble
  store.state.currentSolve.scramble = tempScramble

  emit('generateScrambleView')
}

function generateMove(availableMoves) {
  return availableMoves[Math.floor(Math.random() * availableMoves.length)]
}

</script>


