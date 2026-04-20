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
                    <h4 class="d-block fs-25 fw-bold text-center text-muted">{{ val.completedStatement }}/{{
                        val.loanstatement.length }}</h4>
                    <p class="text-center fw-bold">Completed Instalment</p>
                    <p class="text-center fw-bold">Pay your all instalments now</p>
                    <div class="text-center mt-2 pt-2">
                        <button type="button" class="btn btn-secondary btn-wave waves-effect waves-light"
                            data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#paynow">Pay $ {{
                                val.totalPayable }}</button>
                    </div>
                    <div class="modal fade" id="paynow">
                        <div class="modal-dialog modal-dialog-centered text-center" role="document">
                            <div class="modal-content modal-content-demo">
                                <div class="modal-header">
                                    <h6 class="modal-title text-center">Settle Loan
                                    </h6>
                                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-start front-slider">
                                    <div class="alert alert-solid-success alert-dismissible fade show" v-if="success">
                                        {{ success }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"><i class="bi bi-x"></i></button>
                                    </div>
                                    <div class="alert alert-solid-danger alert-dismissible fade show" v-if="error">
                                        {{ error }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"><i class="bi bi-x"></i></button>
                                    </div>
                                    <h2 class="mt-4 op-7 fw-normal text-center fs-1 fw-bold">$ {{ val.totalPayable }}</h2>

                                    <div class="d-grid mt-4 pt-2" v-if="loading">
                                        <button type="button" class="btn btn-lg btn-secondary label-btn label-end">
                                            <span class="spinner-border spinner-border-sm align-middle" role="status"
                                                aria-hidden="true"></span>
                                            Processing...
                                        </button>
                                    </div>
                                    <div class="d-grid mt-4 pt-2" v-else>
                                        <button type="button" class="btn btn-secondary btn-wave btn-lg" @click="pay()">Pay
                                            Now</button>
                                    </div>
                                    <p class="mt-4 op-7 fw-normal text-center  ">
                                        Settle your loan amount faster, to get chance of next loan approval.

                                    </p>

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
            baseUrl: import.meta.env.VITE_APP_URL,
            user_id: this.val.user.id,
            chartData: {
                datasets: [
                    {
                        backgroundColor: ['#845adf', '#2e71fa'],
                        data: [this.val.completedStatement, this.val.loanstatement.length - this.val.completedStatement]
                    }
                ]
            },
            chartOptions: {
                responsive: true
            },
            loading: false,
            success: '',
            error: '',
        }
    },
    methods: {
        pay(statementid) {
            const isConfirmed = window.confirm("Are you sure you want to process this payment?");

            if (isConfirmed) {
                this.loading = true;
                this.success = ''; // Reset success message
                this.error = '';   // Reset error message

                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
                axios.get(this.baseUrl + 'api/auth/settlement/' + this.val.loanApplication.id + '/pay')
                    .then(response => {
                        this.success = response.data.message;
                        // Add your logic for success here
                    })
                    .catch(error => {
                        this.error = error.response.data.message;
                        // Add your logic for error here
                    })
                    .finally(() => {
                        this.loading = false;
                        setTimeout(() => {
                          //  location.reload();
                        }, 2000);
                    });
            } else {
                // User canceled the payment
            }
        },
        refresh() {
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
            axios.post(this.baseUrl + 'api/auth/customer/getapplication', { user_id: this.user_id })
                .then(response => {
                    const data = response.data;
                    this.statement = data.loanstatement;
                    this.rescheduledstatement = data.rescheduledstatement;

                })
                .catch(error => {
                    this.error = error.response.data.message;
                });
        }
    }
}
</script>