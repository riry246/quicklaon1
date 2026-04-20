<template>
    <div class="row mt-1">

        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 " v-if="val">
            <ul class="nav nav-tabs tab-style-5 d-sm-flex d-block  " role="tablist">
                <li class="nav-item ">
                    <a class="nav-link active p-3 fs-6 fw-light text-muted " data-bs-toggle="tab"
                        data-bs-target="#dashboard" aria-current="page" href="#dashboard"><i
                            class="bx bxs-dashboard side-menu__icon"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link p-3 fs-6 fw-light text-muted" data-bs-toggle="tab" data-bs-target="#payments"
                        href="#payments"><i class="bx bx-credit-card-alt side-menu__icon"></i> Manage Payments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link p-3 fs-6 fw-light text-muted" data-bs-toggle="tab" data-bs-target="#settings"
                        href="#settings"><i class="bx bx-cog side-menu__icon "></i> Settings</a>
                </li>
            </ul>
            <div class="card custom-card card1 ">
                <div class="card-body p-2">
                    <div class="tab-content" v-if="contact_signing">
                        <ContractSigningComponent :val="val" ref="childComponent"></ContractSigningComponent>
                    </div>
                    <div class="tab-content" v-else>


                        <div class="tab-pane active p-0 noborder " id="dashboard" role="tabpanel">
                            <LoanDetailComponent :val="val"></LoanDetailComponent>
                            <hr class="px-4 mx-4" />
                            <BannerComponent :val="val"></BannerComponent>
                            <div class="card custom-card no-border-card p-3"
                                v-if="val.loanApplication.status !== 'incomplete' && val.loanApplication.step >= 10 && jsonData.section == 'dashboard'">
                                <div class="row g-0">
                                    <div class="col-md-5 border-top border-bottom border-start ">
                                        <ChartComponent :val="val" v-if="val.loanApplication.status == 'pending'">
                                        </ChartComponent>
                                        <DeclinedChartComponent :val="val"
                                            v-if="val.loanApplication.status == 'declined'">
                                        </DeclinedChartComponent>
                                        <ProcessingComponent :val="val"
                                            v-if="val.loanApplication.status == 'processing'"
                                            @sendData="handleDataFromChild">
                                        </ProcessingComponent>
                                        <ApprovedComponent :val="val" v-if="val.loanApplication.status == 'active'">
                                        </ApprovedComponent>
                                        <CompletedComponent :val="val" v-if="val.loanApplication.status == 'completed'">
                                        </CompletedComponent>

                                    </div>
                                    <div class="col-md-7 border">
                                        <ScoreComponent :val="val"></ScoreComponent>
                                    </div>
                                </div>
                            </div>
                            <div class="card custom-card card-bg-warning" v-else>
                                <div class="row g-0">
                                    <div class="col-md-8">
                                        <div class="card-body rounded">
                                            <h4 class="card-title mb-2 fw-semibold mt-4 mb-4">Finalize Your Application
                                            </h4>
                                            <p class="card-text mb-0 fs-6 fw-semibold">The last step towards your
                                                financial
                                                goals. </p>
                                            <p class="mb-0 card-text mt-3">Review your application for a smooth approval
                                                process and complete
                                                any outstanding requirements.</p>
                                            <button class="btn btn-light mt-3" @click="continueApplication">Complete
                                                Application</button>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-end p-3 mt-3"> <img src="/assets/images/icon/decline.png"
                                            class="img-fluid rounded-end w-50" alt="..."> </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane p-0 noborder" id="payments" role="tabpanel">
                            <LoanDetailComponent :val="val"></LoanDetailComponent>
                            <hr class="px-4 mx-4" />
                            <div class="card custom-card">
                                <div class="row g-0">
                                    <div class="col-md-12">
                                        <LoanStatement :val="val"></LoanStatement>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane p-0 noborder" id="settings" role="tabpanel">
                            <div class="row g-0">
                                <div class="col-md-12">
                                    <UpdateProfile :val="val"></UpdateProfile>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12" v-if="val">
            <div class="col-xl-12">
                <ReviewComponent :val="val"></ReviewComponent>
            </div>
            <div class="col-xl-12">
                <ReferComponent :val="val"></ReferComponent>
            </div>
        </div>
    </div>
</template>
<script>
import LoanDetailComponent from './widgets/LoanDetailComponent.vue';
import BannerComponent from './widgets/BannerComponent.vue';
import ChartComponent from './widgets/ChartComponent.vue';
import DeclinedChartComponent from './widgets/DeclinedChartComponent.vue';
import ScoreComponent from './widgets/ScoreComponent.vue';
import ReviewComponent from './widgets/ReviewComponent.vue';
import ReferComponent from './widgets/ReferComponent.vue';
import ProcessingComponent from './widgets/ProcessingComponent.vue';
import ApprovedComponent from './widgets/ApprovedComponent.vue';
import CompletedComponent from './widgets/CompletedComponent.vue';
import LoanStatement from './widgets/LoanStatement.vue';
import UpdateProfile from './widgets/UpdateProfile.vue';
import ContractSigningComponent from './widgets/ContractSigningComponent.vue';


export default {
    props: ['jsonData'],
    components: {
        LoanDetailComponent,
        BannerComponent,
        ChartComponent,
        ScoreComponent,
        ReferComponent,
        ReviewComponent,
        DeclinedChartComponent,
        ProcessingComponent,
        ApprovedComponent,
        CompletedComponent,
        LoanStatement,
        UpdateProfile,
        ContractSigningComponent
    },
    data() {
        return {
            baseUrl: import.meta.env.VITE_APP_URL,
            token: '',
            user_id: '',
            val: '',
            contact_signing: false,
        };
    },
    mounted() {
        this.token = this.jsonData.token;
        this.user_id = this.jsonData.user.id;

        sessionStorage.setItem('token', this.token);
        sessionStorage.setItem('user_id', this.user_id);

        this.getApplicationInfo();

    },
    methods: {
        getApplicationInfo() {
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
            axios.post(this.baseUrl + 'api/auth/customer/getapplication', { user_id: this.user_id })
                .then(response => {
                    this.val = response.data;
                    sessionStorage.setItem('amount', this.val.loanApplication.amount);
                    sessionStorage.setItem('duration', this.val.loanApplication.duration);

                })
                .catch(error => {
                    this.error = error.response.data.message;
                });
        },
        handleDataFromChild(data) {
            this.contact_signing = data;
        },
        continueApplication() {
            window.location.href = this.baseUrl + "application/apply";
        },
    },

}
</script>