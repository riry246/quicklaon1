<template>
    <div class="col-xl-12 col-xl-6">

        <div class="card custom-card no-border-card">
            <div class="card-body p-0 overflow-hidden">
                <div class="lead-source-value">
                    <div class="leads-source-chart d-flex align-items-center justify-content-center">

                        <div>
                            <Doughnut id="my-chart-id" :options="chartOptions" :data="chartData"
                                class="chartjs-chart w-100 p-4" />
                        </div>
                    </div>
                </div>

                <div class="row row-cols-12 border-top border-block-start-dashed">
                    <h4 class="d-block fs-25 fw-bold text-center text-muted">Completed</h4>
                    <p class="text-center fw-bold mx-3">Starting a new application opens the door to fresh opportunities and financial possibilities</p>
                    
                </div>
            </div>
        </div>
    </div>
</template>
  
<script>
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js'
import { Doughnut } from 'vue-chartjs'
ChartJS.register(ArcElement, Tooltip, Legend)
export default {
    props: ['val'],
    name: 'DoughnutChart',
    components: { Doughnut },
    data() {
        return {
            chartData: {
                datasets: [
                    {
                        backgroundColor: ['#26bf94','#845adf'],
                        data: [100, 0]
                    }
                ]
            },
            chartOptions: {
                responsive: true
            }
        }
    }
}
</script>