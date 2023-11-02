
<template>
  <div class="bg-gray-200 min-h-[20rem]">
    <span
      class="px-2 pt-2 font-semibold text-xl text-indigo-800 w-[90%] mx-[5%] inline-block border-b-2 border-gray-400">Scramble</span>
    <div v-if="!sessionLoading" class="w-full h-[90%] flex items-center justify-center">
      <div id="cube" class="grid grid-cols-4 grid-rows-3 text-center justify-center items-center w-2/3 mx-auto py-6">
        <div id="upperFace" class="grid col-span-1 col-start-2 grid-cols-3 grid-rows-3 aspect-square">
          <ScrambleFaceComponent :lTC="Cube.Corners.B1.y" :tE="Cube.Edges.B1.y" :rTC="Cube.Corners.B2.y"
            :lE="Cube.Edges.M1.y" :center="Centers.upperFace" :rE="Cube.Edges.M2.y" :lDC="Cube.Corners.F1.y"
            :dE="Cube.Edges.F1.y" :rDC="Cube.Corners.F2.y" />
        </div>
        <div id="leftFace" class="grid col-span-1 grid-cols-3 row-start-2 grid-rows-3 aspect-square">
          <ScrambleFaceComponent :lTC="Cube.Corners.B1.x" :tE="Cube.Edges.M1.x" :rTC="Cube.Corners.F1.x"
            :lE="Cube.Edges.B4.x" :center='Centers.leftFace' :rE="Cube.Edges.F4.x" :lDC="Cube.Corners.B3.x"
            :dE="Cube.Edges.M4.x" :rDC="Cube.Corners.F3.x" />
        </div>
        <div id="frontFace" class="grid col-span-1 grid-cols-3 row-start-2 grid-rows-3 aspect-square">
          <ScrambleFaceComponent :lTC="Cube.Corners.F1.z" :tE="Cube.Edges.F1.z" :rTC="Cube.Corners.F2.z"
            :lE="Cube.Edges.F4.z" :center='Centers.frontFace' :rE="Cube.Edges.F2.z" :lDC="Cube.Corners.F3.z"
            :dE="Cube.Edges.F3.z" :rDC="Cube.Corners.F4.z" />
        </div>
        <div id="rightFace" class="grid col-span-1 grid-cols-3 row-start-2 grid-rows-3 aspect-square">
          <ScrambleFaceComponent :lTC="Cube.Corners.F2.x" :tE="Cube.Edges.M2.x" :rTC="Cube.Corners.B2.x"
            :lE="Cube.Edges.F2.x" :center='Centers.rightFace' :rE="Cube.Edges.B2.x" :lDC="Cube.Corners.F4.x"
            :dE="Cube.Edges.M3.x" :rDC="Cube.Corners.B4.x" />
        </div>
        <div id="backFace" class="grid col-span-1 grid-cols-3 row-start-2 grid-rows-3 aspect-square">
          <ScrambleFaceComponent :lTC="Cube.Corners.B2.z" :tE="Cube.Edges.B1.z" :rTC="Cube.Corners.B1.z"
            :lE="Cube.Edges.B2.z" :center='Centers.backFace' :rE="Cube.Edges.B4.z" :lDC="Cube.Corners.B4.z"
            :dE="Cube.Edges.B3.z" :rDC="Cube.Corners.B3.z" />
        </div>
        <div id="downFace" class="grid col-span-1 col-start-2 grid-cols-3 grid-rows-3 aspect-square">
          <ScrambleFaceComponent :lTC="Cube.Corners.F3.y" :tE="Cube.Edges.F3.y" :rTC="Cube.Corners.F4.y"
            :lE="Cube.Edges.M4.y" :center='Centers.downFace' :rE="Cube.Edges.M3.y" :lDC="Cube.Corners.B3.y"
            :dE="Cube.Edges.B3.y" :rDC="Cube.Corners.B4.y" />
        </div>
      </div>
    </div>
    <div v-else class="flex items-center justify-center flex-col h-[80%] w-full">
      <div class="animate-spin">
        <svg class="w-10 h-10" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" opacity=".25" />
          <path
            d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"
            class="spinner_z9k8" />
        </svg>
      </div>
    </div>
  </div>
</template>

<script setup>
import store from '../../store';
import { ref, computed } from 'vue'
import ScrambleFaceComponent from './ScrambleFaceComponent.vue';

defineExpose({
  generateCubeStructure
})

const sessionLoading = computed(() => store.state.session.loading)

const Rules = {
  R: {
    Affects: {
      Corners: ['F2', 'F4', 'B4', 'B2'],
      Edges: ['F2', 'M3', 'B2', 'M2'],
      noChange: 'x'
    }
  },
  L: {
    Affects: {
      Corners: ['F1', 'B1', 'B3', 'F3'],
      Edges: ['F4', 'M1', 'B4', 'M4'],
      noChange: 'x'
    }
  },
  U: {
    Affects: {
      Corners: ['F1', 'F2', 'B2', 'B1'],
      Edges: ['M1', 'F1', 'M2', 'B1'],
      noChange: 'y'
    }
  },
  D: {
    Affects: {
      Corners: ['F3', 'B3', 'B4', 'F4'],
      Edges: ['F3', 'M4', 'B3', 'M3'],
      noChange: 'y'
    }
  },
  F: {
    Affects: {
      Corners: ['F1', 'F3', 'F4', 'F2'],
      Edges: ['F1', 'F4', 'F3', 'F2'],
      noChange: 'z'
    }
  },
  B: {
    Affects: {
      Corners: ['B1', 'B2', 'B4', 'B3'],
      Edges: ['B1', 'B2', 'B3', 'B4'],
      noChange: 'z'
    }
  },
}

let Cube = ref({
  Corners: {
    F1: {
      x: 'bg-orange-400',
      y: 'bg-white',
      z: 'bg-[#17b01f]'
    },
    F2: {
      x: 'bg-red-700',
      y: 'bg-white',
      z: 'bg-[#17b01f]'
    },
    F3: {
      x: 'bg-orange-400',
      y: 'bg-yellow-400',
      z: 'bg-[#17b01f]'
    },
    F4: {
      x: 'bg-red-700',
      y: 'bg-yellow-400',
      z: 'bg-[#17b01f]'
    },
    B1: {
      x: 'bg-orange-400',
      y: 'bg-white',
      z: 'bg-[#2b57cf]'
    },
    B2: {
      x: 'bg-red-700',
      y: 'bg-white',
      z: 'bg-[#2b57cf]'
    },
    B3: {
      x: 'bg-orange-400',
      y: 'bg-yellow-400',
      z: 'bg-[#2b57cf]'
    },
    B4: {
      x: 'bg-red-700',
      y: 'bg-yellow-400',
      z: 'bg-[#2b57cf]'
    }
  },
  Edges: {
    F1: {
      x: null,
      y: 'bg-white',
      z: 'bg-[#17b01f]'
    },
    F2: {
      x: 'bg-red-700',
      y: null,
      z: 'bg-[#17b01f]'
    },
    F3: {
      x: null,
      y: 'bg-yellow-400',
      z: 'bg-[#17b01f]'
    },
    F4: {
      x: 'bg-orange-400',
      y: null,
      z: 'bg-[#17b01f]'
    },
    M1: {
      x: 'bg-orange-400',
      y: 'bg-white',
      z: null
    },
    M2: {
      x: 'bg-red-700',
      y: 'bg-white',
      z: null
    },
    M3: {
      x: 'bg-red-700',
      y: 'bg-yellow-400',
      z: null
    },
    M4: {
      x: 'bg-orange-400',
      y: 'bg-yellow-400',
      z: null
    },
    B1: {
      x: null,
      y: 'bg-white',
      z: 'bg-[#2b57cf]'
    },
    B2: {
      x: 'bg-red-700',
      y: null,
      z: 'bg-[#2b57cf]'
    },
    B3: {
      x: null,
      y: 'bg-yellow-400',
      z: 'bg-[#2b57cf]'
    },
    B4: {
      x: 'bg-orange-400',
      y: null,
      z: 'bg-[#2b57cf]'
    }
  }
})

const Centers = {
  upperFace: 'bg-white',
  frontFace: 'bg-[#17b01f]',
  leftFace: 'bg-orange-400',
  rightFace: 'bg-red-700',
  backFace: 'bg-[#2b57cf]',
  downFace: 'bg-yellow-400'
}

generateCubeStructure()

function generateCubeStructure() {
  let scramble = store.state.currentSolve.scramble.split(' ')

  let tempCube = {
    Corners: {
      F1: {
        x: 'bg-orange-400',
        y: 'bg-white',
        z: 'bg-[#17b01f]'
      },
      F2: {
        x: 'bg-red-700',
        y: 'bg-white',
        z: 'bg-[#17b01f]'
      },
      F3: {
        x: 'bg-orange-400',
        y: 'bg-yellow-400',
        z: 'bg-[#17b01f]'
      },
      F4: {
        x: 'bg-red-700',
        y: 'bg-yellow-400',
        z: 'bg-[#17b01f]'
      },
      B1: {
        x: 'bg-orange-400',
        y: 'bg-white',
        z: 'bg-[#2b57cf]'
      },
      B2: {
        x: 'bg-red-700',
        y: 'bg-white',
        z: 'bg-[#2b57cf]'
      },
      B3: {
        x: 'bg-orange-400',
        y: 'bg-yellow-400',
        z: 'bg-[#2b57cf]'
      },
      B4: {
        x: 'bg-red-700',
        y: 'bg-yellow-400',
        z: 'bg-[#2b57cf]'
      }
    },
    Edges: {
      F1: {
        x: null,
        y: 'bg-white',
        z: 'bg-[#17b01f]'
      },
      F2: {
        x: 'bg-red-700',
        y: null,
        z: 'bg-[#17b01f]'
      },
      F3: {
        x: null,
        y: 'bg-yellow-400',
        z: 'bg-[#17b01f]'
      },
      F4: {
        x: 'bg-orange-400',
        y: null,
        z: 'bg-[#17b01f]'
      },
      M1: {
        x: 'bg-orange-400',
        y: 'bg-white',
        z: null
      },
      M2: {
        x: 'bg-red-700',
        y: 'bg-white',
        z: null
      },
      M3: {
        x: 'bg-red-700',
        y: 'bg-yellow-400',
        z: null
      },
      M4: {
        x: 'bg-orange-400',
        y: 'bg-yellow-400',
        z: null
      },
      B1: {
        x: null,
        y: 'bg-white',
        z: 'bg-[#2b57cf]'
      },
      B2: {
        x: 'bg-red-700',
        y: null,
        z: 'bg-[#2b57cf]'
      },
      B3: {
        x: null,
        y: 'bg-yellow-400',
        z: 'bg-[#2b57cf]'
      },
      B4: {
        x: 'bg-orange-400',
        y: null,
        z: 'bg-[#2b57cf]'
      }
    }
  }

  for (let x = 0; x < 20; x++) {
    if (scramble[x].length == 1) {
      tempCube = simulateMove(tempCube, scramble[x].charAt(0), 'normal')
    } else if (scramble[x].charAt(1) == '\'') {
      tempCube = simulateMove(tempCube, scramble[x].charAt(0), 'prim')
    } else if (scramble[x].charAt(1) == '2') {
      tempCube = simulateMove(tempCube, scramble[x].charAt(0), 'double')
    }
  }
  Cube.value = tempCube
}



function simulateMove(Cube, move, moveType) {
  let positions = ['x', 'y', 'z'].filter((item) => item != Rules[move].Affects.noChange)
  let firstCorner, firstEdge

  switch (moveType) {
    case 'normal':
      firstCorner = {
        x: Cube.Corners[Rules[move].Affects.Corners[0]].x,
        y: Cube.Corners[Rules[move].Affects.Corners[0]].y,
        z: Cube.Corners[Rules[move].Affects.Corners[0]].z
      }
      firstEdge = {
        x: Cube.Edges[Rules[move].Affects.Edges[0]].x,
        y: Cube.Edges[Rules[move].Affects.Edges[0]].y,
        z: Cube.Edges[Rules[move].Affects.Edges[0]].z
      }

      for (let i = 0; i < 3; i++) {
        // Moving Corners
        Cube.Corners[Rules[move].Affects.Corners[i]][Rules[move].Affects.noChange] = Cube.Corners[Rules[move].Affects.Corners[i + 1]][Rules[move].Affects.noChange]
        Cube.Corners[Rules[move].Affects.Corners[i]][positions[0]] = Cube.Corners[Rules[move].Affects.Corners[i + 1]][positions[1]]
        Cube.Corners[Rules[move].Affects.Corners[i]][positions[1]] = Cube.Corners[Rules[move].Affects.Corners[i + 1]][positions[0]]

        // Moving Edges
        Cube.Edges[Rules[move].Affects.Edges[i]][Rules[move].Affects.noChange] = Cube.Edges[Rules[move].Affects.Edges[i + 1]][Rules[move].Affects.noChange]
        Cube.Edges[Rules[move].Affects.Edges[i]][positions[0]] = Cube.Edges[Rules[move].Affects.Edges[i + 1]][positions[1]]
        Cube.Edges[Rules[move].Affects.Edges[i]][positions[1]] = Cube.Edges[Rules[move].Affects.Edges[i + 1]][positions[0]]
      }

      Cube.Corners[Rules[move].Affects.Corners[3]][Rules[move].Affects.noChange] = firstCorner[Rules[move].Affects.noChange]
      Cube.Corners[Rules[move].Affects.Corners[3]][positions[0]] = firstCorner[positions[1]]
      Cube.Corners[Rules[move].Affects.Corners[3]][positions[1]] = firstCorner[positions[0]]

      Cube.Edges[Rules[move].Affects.Edges[3]][Rules[move].Affects.noChange] = firstEdge[Rules[move].Affects.noChange]
      Cube.Edges[Rules[move].Affects.Edges[3]][positions[0]] = firstEdge[positions[1]]
      Cube.Edges[Rules[move].Affects.Edges[3]][positions[1]] = firstEdge[positions[0]]

      break;
    case 'prim':
      firstCorner = {
        x: Cube.Corners[Rules[move].Affects.Corners[3]].x,
        y: Cube.Corners[Rules[move].Affects.Corners[3]].y,
        z: Cube.Corners[Rules[move].Affects.Corners[3]].z
      }
      firstEdge = {
        x: Cube.Edges[Rules[move].Affects.Edges[3]].x,
        y: Cube.Edges[Rules[move].Affects.Edges[3]].y,
        z: Cube.Edges[Rules[move].Affects.Edges[3]].z
      }

      for (let i = 3; i > 0; i--) {
        // Moving Corners
        Cube.Corners[Rules[move].Affects.Corners[i]][Rules[move].Affects.noChange] = Cube.Corners[Rules[move].Affects.Corners[i - 1]][Rules[move].Affects.noChange]
        Cube.Corners[Rules[move].Affects.Corners[i]][positions[0]] = Cube.Corners[Rules[move].Affects.Corners[i - 1]][positions[1]]
        Cube.Corners[Rules[move].Affects.Corners[i]][positions[1]] = Cube.Corners[Rules[move].Affects.Corners[i - 1]][positions[0]]

        // Moving Edges
        Cube.Edges[Rules[move].Affects.Edges[i]][Rules[move].Affects.noChange] = Cube.Edges[Rules[move].Affects.Edges[i - 1]][Rules[move].Affects.noChange]
        Cube.Edges[Rules[move].Affects.Edges[i]][positions[0]] = Cube.Edges[Rules[move].Affects.Edges[i - 1]][positions[1]]
        Cube.Edges[Rules[move].Affects.Edges[i]][positions[1]] = Cube.Edges[Rules[move].Affects.Edges[i - 1]][positions[0]]
      }

      Cube.Corners[Rules[move].Affects.Corners[0]][Rules[move].Affects.noChange] = firstCorner[Rules[move].Affects.noChange]
      Cube.Corners[Rules[move].Affects.Corners[0]][positions[0]] = firstCorner[positions[1]]
      Cube.Corners[Rules[move].Affects.Corners[0]][positions[1]] = firstCorner[positions[0]]

      Cube.Edges[Rules[move].Affects.Edges[0]][Rules[move].Affects.noChange] = firstEdge[Rules[move].Affects.noChange]
      Cube.Edges[Rules[move].Affects.Edges[0]][positions[0]] = firstEdge[positions[1]]
      Cube.Edges[Rules[move].Affects.Edges[0]][positions[1]] = firstEdge[positions[0]]

      break;
    case 'double':

      positions = ['x', 'y', 'z']
      let temp

      for (let i = 0; i < 3; i++) {
        // Moving Corners
        temp = Cube.Corners[Rules[move].Affects.Corners[0]][positions[i]]

        Cube.Corners[Rules[move].Affects.Corners[0]][positions[i]] = Cube.Corners[Rules[move].Affects.Corners[2]][positions[i]]
        Cube.Corners[Rules[move].Affects.Corners[2]][positions[i]] = temp

        temp = Cube.Corners[Rules[move].Affects.Corners[1]][positions[i]]
        Cube.Corners[Rules[move].Affects.Corners[1]][positions[i]] = Cube.Corners[Rules[move].Affects.Corners[3]][positions[i]]
        Cube.Corners[Rules[move].Affects.Corners[3]][positions[i]] = temp

        // Moving Edges
        temp = Cube.Edges[Rules[move].Affects.Edges[0]][positions[i]]
        Cube.Edges[Rules[move].Affects.Edges[0]][positions[i]] = Cube.Edges[Rules[move].Affects.Edges[2]][positions[i]]
        Cube.Edges[Rules[move].Affects.Edges[2]][positions[i]] = temp

        temp = Cube.Edges[Rules[move].Affects.Edges[1]][positions[i]]
        Cube.Edges[Rules[move].Affects.Edges[1]][positions[i]] = Cube.Edges[Rules[move].Affects.Edges[3]][positions[i]]
        Cube.Edges[Rules[move].Affects.Edges[3]][positions[i]] = temp
      }

      break;
    default:
      break;
  }

  return Cube
}
</script>


