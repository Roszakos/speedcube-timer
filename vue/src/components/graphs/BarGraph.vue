<template>
  <Line id="my-chart-id" :options="chartOptions" :data="chartData" />
</template>

<script>
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
} from 'chart.js'
import { Line } from 'vue-chartjs'

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
)


export default {
  name: 'LineChart',
  components: { Line },
  data() {
    return {
      chartOptions: {
        responsive: true,
        aspectRatio: 4,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            display: true,
            title: {
              display: true,
              text: 'Average Time',
              color: '#191',
              font: {
                family: 'Times',
                size: 20,
                style: 'normal',
                lineHeight: 1.2
              },
              padding: { top: 30, left: 0, right: 0, bottom: 0 }
            }
          }
        }
      }
    }
  },
  props: {
    chartData: {
      type: Object,
      required: true
    }
  }
}

</script>