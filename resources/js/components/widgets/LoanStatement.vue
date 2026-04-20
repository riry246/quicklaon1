<template>
    <div class="row">
        <div class="col-xxl-12 col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between noborder pb-0 mb-0">
                    <div class="card-title ">
                        <h5>Loan Statement</h5>
                    </div>
                    <button class="btn btn-sm btn-success" style="text-transform: capitalize;"
                        v-if="val.loanstatement.length > 0">{{ val.upcoming.frequency }}</button>
                </div>
                <div class="card-body pt-2">
                    <div class="alert alert-solid-success alert-dismissible fade show" v-if="success">
                        {{ success }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                                class="bi bi-x"></i></button>
                    </div>
                    <div class="alert alert-solid-danger alert-dismissible fade show" v-if="error">
                        {{ error }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                                class="bi bi-x"></i></button>
                    </div>
                    <div class="deleted-table table-responsive">
                        <table class="table table-bordered text-nowrap mb-4">
                            <thead>
                                <tr>
                                    <th>Week</th>
                                    <th>Pay Date</th>
                                    <th>Opening Balance</th>
                                    <th>Weekly Payment</th>
                                    <th>Closing Balance</th>
                                    <th>Payment Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(ls, index) in statement" :key="ls.id" v-if="statement.length > 0">
                                    <td v-if="ls.frequency == 'weekly'">{{ index + 1 }} week</td>
                                    <td v-else>{{ generateOddNumber(index) }} week</td>
                                    <td>{{ formatedate(ls.settlement_date) }}</td>
                                    <td>$ {{ ls.opening_balance }}</td>
                                    <td>$ {{ ls.weekly_payment }}</td>
                                    <td>$ {{ ls.closing_balance }}</td>
                                    <td>{{ ls.payment_status }}</td>
                                    <td>
                                        <div class="btn-group" v-if="loading">
                                            <button type="button" class="btn btn-lg btn-secondary label-btn label-end">
                                                <span class="spinner-border spinner-border-sm align-middle"
                                                    role="status" aria-hidden="true"></span>
                                                Processing...
                                            </button>
                                        </div>
                                        <div class="btn-group" v-if="!loading">
                                            <button type="button" class="btn btn-secondary dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-expanded="false"
                                                v-if="ls.payment_status == 'Scheduled'">
                                                Action
                                            </button>
                                            <button type="button" class="btn btn-light" v-else>
                                                Disable
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:void(0);"
                                                        @click="pay(ls.id)">Process Payment</a></li>
                                                <li><a class="dropdown-item" href="javascript:void(0);"
                                                        @click="reschedule(ls.id)">Re-schedule Payment</a></li>
                                            </ul>

                                        </div>
                                    </td>

                                </tr>
                                <tr v-else>
                                    <td colspan="6" class="text-center">Oops! 🙁 We couldn't find your Loan Statement.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="deleted-table table-responsive" v-if="rescheduledstatement.length > 0">
                        <h6>Re-scheduled Payments</h6>
                        <table class="table table-search text-nowrap mt-4 mb-4 mb-4">
                            <thead>
                                <tr>
                                    <th>Week</th>
                                    <th>Pay Date</th>
                                    <th>Amount</th>
                                    <th>Interest & fee</th>
                                    <th>Reschedule Fee</th>
                                    <th>Principal Payment</th>
                                    <th>Payment Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(ls, index) in rescheduledstatement" :key="ls.id"
                                    v-if="rescheduledstatement.length > 0">
                                    <td v-if="ls.frequency == 'weekly'">{{ index + 1 }} week</td>
                                    <td v-else>{{ generateOddNumber(index) }} week</td>
                                    <td>{{ formatedate(ls.settlement_date) }}</td>
                                    <td>$ {{ ls.weekly_payment }}</td>
                                    <td>$ {{ ls.interest }}</td>
                                    <td>$ {{ ls.reschedule_fee }}</td>
                                    <td>$ {{ ls.principal_payment }}</td>
                                    <td>{{ ls.payment_status }}</td>
                                    <td>
                                        <div class="btn-group" v-if="loading">
                                            <button type="button" class="btn btn-lg btn-secondary label-btn label-end">
                                                <span class="spinner-border spinner-border-sm align-middle"
                                                    role="status" aria-hidden="true"></span>
                                                Processing...
                                            </button>
                                        </div>
                                        <div class="btn-group" v-if="!loading">
                                            <button type="button" class="btn btn-secondary dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-expanded="false"
                                                v-if="ls.payment_status == 'Re-scheduled'">
                                                Action
                                            </button>
                                            <button type="button" class="btn btn-light" v-else>
                                                Disable
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:void(0);"
                                                        @click="pay(ls.id)">Process Payment</a></li>

                                            </ul>
                                        </div>
                                    </td>

                                </tr>
                                <tr v-else>
                                    <td colspan="6" class="text-center">Oops! 🙁 We couldn't find your Loan Statement.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import moment from 'moment';

export default {
    props: ['val'],
    data() {
        return {
            baseUrl: import.meta.env.VITE_APP_URL,
            user_id: this.val.user.id,
            token: '',
            success: '',
            error: '',
            statement: this.val.loanstatement,
            rescheduledstatement: this.val.rescheduledstatement,
            loading: '',
        };
    },
    mounted() {
        this.token = sessionStorage.getItem('token');
    },
    methods: {
        generateOddNumber(index) {
            return 2 * index + 1;
        },
        formatedate(date) {
            return moment(date).format('DD-MM-YYYY');
        },
        pay(statementid) {
            const isConfirmed = window.confirm("Are you sure you want to process this payment?");

            if (isConfirmed) {
                this.loading = true;
                this.success = ''; // Reset success message
                this.error = '';   // Reset error message

                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
                axios.get(this.baseUrl + 'api/auth/statement/' + statementid + '/pay')
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
                        this.refresh();
                    });
            } else {
                // User canceled the payment
            }
        },
        reschedule(statementid) {
            const isConfirmed = window.confirm("Are you sure you want to reschedule this payment?\nAn extra $10 reschedule fee may apply.");

            if (isConfirmed) {
                this.loading = true;
                this.success = ''; // Reset success message
                this.error = '';   // Reset error message

                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
                axios.get(this.baseUrl + 'api/auth/statement/' + statementid + '/reschedule')
                    .then(response => {
                        // Show a success alert
                        this.success = response.data.message;
                        this.refresh(); // Assuming you have a refresh method to update the data/UI
                    })
                    .catch(error => {
                        // Show an error alert
                        this.error = error.response.data.message;
                    })
                    .finally(() => {
                        // Reset loading state whether the request succeeds or fails
                        this.loading = false;
                    });
            } else {
                // User canceled the action
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