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

                <div class="row row-cols-12 mx-5">
                    <h4 class="d-block fs-22 fw-bold text-center ">Pending
                        Approval</h4>
                    <p class="text-center fs-15 fw-light mx-auto">Application received! We'll notify you about your status ASAP.</p>
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
    name: 'DoughnutChart',
    components: { Doughnut },
    data() {
        return {
            chartData: {
                datasets: [
                    {
                        backgroundColor: ['#845adf', '#2e71fa'],
                        data: [99, 1]
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