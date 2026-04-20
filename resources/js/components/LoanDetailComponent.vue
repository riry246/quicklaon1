<template>
    <div class="landing-banner" id="home">
        <section class="section pb-0">
            <div class="container main-banner-container">
                <div class="row ">
                    <div class="col-xxl-7 col-xl-7 col-lg-8 d-flex align-items-center justify-content-between">
                        <div class="">
                            <h5 class="landing-banner-heading mb-3"><span class="fw-bold">{{ jsonData.heading }}</span></h5>
                            <p class="fs-16 mb-5 op-8 fw-normal text-fixed-white">{{ jsonData.sub_heading }}</p>
                        </div>
                    </div>
                    <div class="col-xxl-5 col-xl-5 col-lg-4">
                        <div class="card custom-card card-bg-primary noshadow " v-if="jsonData.loancal">
                            <div class="card-body rounded p-3 px-4 ">
                                <div class="modal fade" id="modaldemo8">
                                    <div class="modal-dialog modal-dialog-centered text-center" role="document">
                                        <div class="modal-content modal-content-demo">
                                            <div class="modal-header">
                                                <h6 class="modal-title text-center">How much do you need?</h6>
                                                <button aria-label="Close" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-start front-slider">
                                                <div class="container justify-content-between">
                                                    <h5 class="mb-4 mt-3">$<span id="slider-amount-value">{{ amount
                                                    }}</span> <span class="slider_value_highlight"><span>Max</span>${{ maxAmount
}}</span></h5>
                                                    <div>

                                                        <input class="range slider-round mx-5" type="range" min="300"
                                                            step="100" :max=maxAmount v-model="amount" @input="rangeSlide">
                                                        <p></p>
                                                    </div>
                                                </div>
                                                <div class="container mt-5">
                                                    <h5 class="mb-3 mt-3"><span id="slider-duration-value">{{ duration
                                                    }}</span> weeks
                                                        <span class="slider_value_highlight"><span>Max</span>{{ maxDuration }} weeks</span>
                                                    </h5>
                                                    <div>
                                                        <input class="range slider-round mx-5" type="range" min="4"
                                                            :max="maxDuration" step="4" v-model="duration"
                                                            @input="rangeSlide">
                                                        <p></p>
                                                    </div>

                                                </div>
                                                <div class="container">
                                                    <div class="row px-5 gy-4 mb-3 button-set-block">
                                                        <label for="input-date" class="form-label text-muted">Repayment
                                                            Frequency</label>
                                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                                            <button type="button"
                                                                class="btn btn-outline-secondary full-width-button label-btn p-3"
                                                                :class="{ active: frequency === 'weekly' }"
                                                                @click="selectedPayCycle('weekly')">
                                                                Weekly
                                                            </button>
                                                        </div>
                                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                                            <button type="button"
                                                                class="btn btn-outline-secondary full-width-button label-btn p-3"
                                                                :class="{ active: frequency === 'fortnightly' }"
                                                                @click="selectedPayCycle('fortnightly')">
                                                                Fortnightly
                                                            </button>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" @click="update()"
                                                    data-bs-dismiss="modal">Update</button> <button class="btn btn-danger"
                                                    data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 no-wrap pb-0 mb-0">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <p class="fs-16 p-0 mb-1 ">Applying for</p>
                                                <h6 class="fw-bold fs-5 ">
                                                    ${{ amount }}
                                                </h6>
                                            </div>
                                            <div class="fs-12 text-muted">
                                                <p class="fs-14 fw-semibold mb-2 lh-1"><a href="javascript:void(0);"  ><i
                                                            class="ri-edit-line float-end text-fixed-white " id="applybtn2"
                                                            data-bs-effect="effect-super-scaled" data-bs-toggle="modal"
                                                            href="#modaldemo8"></i></a> </p>
                                            </div>
                                        </div>
                                        <hr class="border-white border-1 opacity-75 p-0 mb-3 mt-2">
                                    </div>
                                    <div class="col-lg-5 no-wrap border-end border-white border-2 ">
                                        <p class="fs-16 p-0 mb-1 ">Loan Duration</p>
                                        <h6 class="fw-bold fs-5 ">
                                            {{ duration }} Weeks
                                        </h6>
                                    </div>
                                    <div class="col-lg-5 mx-auto no-wrap">
                                        <p class="fs-16 p-0 mb-1 ">Repayments ({{ capitalizeFirstLetter(frequency) }})</p>
                                        <h6 class="fw-bold fs-5 ">
                                            ${{ instalment }} x {{ duration }} weeks
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import { computed } from 'vue';

export default {
    props: ['jsonData'],
    data() {
        return {
            baseUrl: import.meta.env.VITE_APP_URL,
            amount: '',
            duration: '',
            frequency: 'weekly',
            instalment: '',
            token: '',
            user_id: '',
            maxAmount: '',
            maxDuration: '',
        };
    },
    mounted() {

        this.token = sessionStorage.getItem('token');
        
        setTimeout(() => {
            this.app_id = sessionStorage.getItem('app_id');
            this.amount = sessionStorage.getItem('amount');
            this.duration = sessionStorage.getItem('duration');
            this.frequency = sessionStorage.getItem('frequency');
            this.maxAmount = sessionStorage.getItem('maxAmount');
            this.maxDuration = sessionStorage.getItem('maxDuration');
            this.weeklyPay();
        }, 3000);
    },
    methods: {
        capitalizeFirstLetter(str) {
            if (this.frequency) {
                return str.charAt(0).toUpperCase() + str.slice(1);
            }
            return 'Weekly';

        },
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
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
            axios.post(this.baseUrl + 'api/auth/updateloan', { app_id: this.app_id, amount: this.amount, duration: this.duration, frequency: this.frequency })
                .then(response => {

                })
                .catch(error => {
                    this.error = error.response.data.message;
                    //window.location.href = "/";
                });
        }
    }

}

</script>
