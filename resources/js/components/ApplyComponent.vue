<template>
    <div class="d-grid gap-2 col-12 ">
        <a class="btn btn-lg btn-outline-secondary label-btn " id="applybtn" data-bs-effect="effect-super-scaled" data-bs-toggle="modal"
            href="#modaldemo8">
            Apply For Loan
        </a>

        <div class="modal fade" id="modaldemo8">
            <div class="modal-dialog modal-dialog-centered text-center" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title text-center">How much do you need?</h6>
                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-start front-slider">
                        <div class="container justify-content-between">
                            <h5 class="mb-4 mt-3">$<span id="slider-amount-value">{{ amount }}</span>
                                <span class="slider_value_highlight"><span>Max</span> ${{ maxAmount }}</span>
                            </h5>
                            <div>
                                <input class="range slider-round mx-5" type="range" min="300" step="100" :max=maxAmount
                                    v-model="amount" @input="rangeSlide">
                            </div>
                        </div>
                        <div class="container mt-5">
                            <h5 class="mb-3 mt-3"><span id="slider-duration-value">{{ duration
                            }}</span> weeks
                                <span class="slider_value_highlight"><span>Max</span>{{ maxDuration }} weeks</span>
                            </h5>
                            <div>
                                <input class="range slider-round mx-5" type="range" min="4" :max="maxDuration" step="4"
                                    v-model="duration" @input="rangeSlide">
                                <p></p>
                            </div>

                        </div>
                        <div class="container">
                            <div class="row px-5 gy-4 mb-3 button-set-block">
                                <label for="input-date" class="form-label text-muted">Repayment
                                    Frequency</label>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <button type="button" class="btn btn-outline-secondary full-width-button label-btn p-3"
                                        :class="{ active: frequency === 'weekly' }" @click="selectedPayCycle('weekly')">
                                        Weekly
                                    </button>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <button type="button" class="btn btn-outline-secondary full-width-button label-btn p-3"
                                        :class="{ active: frequency === 'fortnightly' }"
                                        @click="selectedPayCycle('fortnightly')">
                                        Fortnightly
                                    </button>
                                </div>

                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" @click="update()" data-bs-dismiss="modal">Apply</button> <button
                            class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { computed } from 'vue';

export default {
    props: ['jsonData'],
    data() {
        return {
            baseUrl: import.meta.env.VITE_APP_URL,
            amount: '500',
            duration: '8',
            frequency: 'weekly',
            maxAmount: '2000',
            maxDuration: '12',
        };
    },
    mounted() {

    },
    methods: {
        weeklyPay() {
            const weeklyInterestRate = 0.04 / 4;
            const totalintrest = weeklyInterestRate * this.duration;

            const establishmnetFee = this.amount * 0.2;
            const weeklyEstablishmentFeePaid = establishmnetFee / this.duration;

            const principal = Number(establishmnetFee) + Number(this.amount);
            const principal_1 = this.amount * totalintrest + principal;
            const weeklyPayment = principal_1 / this.duration;
            this.instalment = (weeklyPayment).toFixed(2);
        },
        rangeSlide(value) {
            this.weeklyPay();
        },
        selectedPayCycle(cycle) {
            this.frequency = cycle;
        },

        update() {
            window.location.href = this.baseUrl + "application?amount=" + this.amount + "&duration=" + this.duration + "&frequency=" + this.frequency;
        }
    }

}

</script>

