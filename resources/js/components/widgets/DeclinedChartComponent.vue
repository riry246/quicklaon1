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

                <div class="row row-cols-12 border-top border-block-start-dashed mx-5">
                    <h4 class="d-block fs-25 fw-bold text-center text-muted">Application Declined
                    </h4>
                    <p class="text-center text-muted">Check out our tips to improve loan section before re-applying loan</p>
                    <div class="d-grid mt-4 pt-2">
                        <button type="button" class="btn btn-secondary btn-wave btn-lg" data-bs-effect="effect-super-scaled"
                            data-bs-toggle="modal" href="#lead">Access More Loan Offers</button>
                    </div>

                    <div class="modal fade" id="lead">
                        <div class="modal-dialog modal-dialog-centered text-center" role="document">
                            <div class="modal-content modal-content-demo">
                                <div class="modal-header">
                                    <h6 class="modal-title text-center">Access More Loan Offers</h6>
                                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-start front-slider">
                                    <iframe :src="iframeSrc" width="100%" height="500" frameborder="0"></iframe>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
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
            token: this.val.leadmarket.lead_token,
            chartData: {
                datasets: [
                    {
                        backgroundColor: ['#e60105', '#e60105'],
                        data: [99, 1]
                    }
                ]
            },
            chartOptions: {
                responsive: true
            }
        }
    },
    computed: {
        iframeSrc() {
            return `/customer/leadmarket/${this.token}`;
        },
    },
}
</script>