<template>
    <div class="card custom-card card1 bg-secondary mb-0 ">
        <div class="card-body text-white py-2 ">
            <div class="row">
                <div class="col-lg-2 no-wrap">
                    <p class="mb-2 fw-light fs-15">Application ID</p>
                    <h6 class="fw-medium fs-6 fs-6">
                        # {{ val.loanApplication.id }}
                    </h6>
                </div>
                <div class="col-lg-2 no-wrap">
                    <p class="mb-2 fw-light fs-15">Application Status</p>
                    <h6 class="fw-medium fs-6" style="text-transform: capitalize;">
                        {{ val.loanApplication.status }}
                    </h6>
                </div>
                <div class="col-lg-2 no-wrap">
                    <p class="mb-2 fw-light fs-15">Loan Amount</p>
                    <h6 class="fw-medium fs-6" v-if="val.loanApplication.approved_amount">
                        ${{ val.loanApplication.approved_amount }}
                    </h6>
                    <h6 class="fw-medium fs-6" v-else>
                        ${{ val.loanApplication.amount }}
                    </h6>
                </div>
                <div class="col-lg-2 no-wrap">
                    <p class="mb-2 fw-light fs-15">Loan Duration</p>
                    <h6 class="fw-medium fs-6">
                        {{ val.loanApplication.duration }} weeks
                    </h6>
                </div>
                <div class="col-lg-2 no-wrap">
                    <p class="mb-2 fw-light fs-15">Weekly Instalment</p>
                    <h6 class="fw-medium fs-6" v-if="val.upcoming">
                        $ {{ val.upcoming.weekly_payment ?? N / A }}
                    </h6>
                    <h6 class="fw-medium fs-6" v-else>
                        N/A
                    </h6>
                </div>
                <div class="col-lg-2 no-wrap">
                    <p class="mb-2 fw-light fs-15">Next Payment Date</p>
                    <h6 class="fw-medium fs-6" v-if="val.upcoming">
                        {{ formatedate(this.val.upcoming.payment_date) ?? N / A }}

                    </h6>
                    <h6 class="fw-medium fs-6" v-else>
                        N/A
                    </h6>
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

        };
    },
    methods: {
        formatedate(date) {
            if (date) {
                return moment(date).format('DD-MM-YYYY');
            } else {
                return false;
            }
        }
    }
}
</script>