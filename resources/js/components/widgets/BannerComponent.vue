<template>
    <div v-if="val.loanApplication.status == 'pending'">
        <div class="card custom-card card-bg-primary">
            <div class="row g-0">
                <div class="col-md-12">
                    <div class="card-body">
                        <div class="row p-4">
                            <div
                                class="col-xxl-2 col-xl-2 large-icon col-lg-2 col-md-2 col-sm-12 col-12 d-flex align-items-center justify-content-center ecommerce-icon success px-0">

                                <i class="bx bxs-file"></i>
                            </div>
                            <div
                                class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mt-2 px-0 border-end border-end border-custom">
                                <h5 class="mb-2 fw-bold">Application Submitted Successfully!</h5>
                                <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark text-white fs-13 lh-1 vertical-bottom">
                                        Thank you for completing
                                        your application.
                                    </span>
                                </div>
                                <hr />
                                <div class=" mb-1 fs-12">
                                    <span class="text-dark text-white fs-13 lh-1 vertical-bottom fw-light">
                                        We'll notify you by SMS and email once the review is done. For urgent questions,
                                        contact our support team. Thanks!
                                    </span>
                                </div>
                            </div>
                            <div
                                class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 px-0 d-flex align-items-center justify-content-center ">
                                <div class="text-muted mb-1 fs-12 mx-3 mt-2">
                                    <span class="text-dark text-white fs-13 lh-1 vertical-bottom">
                                        Our dedicated team is now reviewing your information. Expect to hear back from us
                                        shortly.
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-else-if="val.loanApplication.status == 'declined'">
        <div class="card custom-card card-bg-danger">
            <div class="row g-0">
                <div class="col-md-12">
                    <div class="card-body">
                        <div class="row p-4">
                            <div
                                class="col-xxl-2 col-xl-2 large-icon col-lg-2 col-md-2 col-sm-12 col-12 d-flex align-items-center justify-content-center ecommerce-icon success px-0">

                                <i class="bx bxs-file"></i>
                            </div>
                            <div
                                class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mt-2 px-0 border-end border-end border-white">
                                <h5 class="mb-2 fw-bold">Application Rejected</h5>
                                <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark text-white fs-13 lh-1 vertical-bottom">
                                        We regret to inform you that your recent loan application has been rejected.
                                    </span>
                                </div>
                                <hr />
                                <div class=" mb-1 fs-12">
                                    <span class="text-dark text-white fs-13 lh-1 vertical-bottom fw-light">
                                        Please consider reapplying once your bills are settled or financial position
                                        improves. We look forward to assisting you in the future. Thank you.
                                    </span>
                                </div>
                            </div>
                            <div
                                class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 px-0 d-flex align-items-center justify-content-center ">
                                <div class="text-muted mb-1 fs-12 mx-3 mt-2">
                                    <span class="text-dark text-white fs-13 lh-1 vertical-bottom">
                                        Good News ! Apply again
                                    </span><br />
                                    <button class="btn btn-light mt-3 mx-3" data-bs-effect="effect-super-scaled"
                                        data-bs-toggle="modal" href="#declineApp">Reapply Now</button>
                                    <div class="modal fade" id="declineApp">
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
                                                        }}</span> <span class="slider_value_highlight">$2000</span>
                                                        </h5>
                                                        <div>
                                                            <input class="range slider-round mx-5" type="range" min="300"
                                                                step="100" max="2000" v-model="amount" @input="rangeSlide">
                                                            <p></p>
                                                        </div>
                                                    </div>
                                                    <div class="container mt-5">
                                                        <h5 class="mb-3 mt-3"><span id="slider-duration-value">{{ duration
                                                        }}</span> weeks
                                                            <span class="slider_value_highlight">12 weeks</span>
                                                        </h5>
                                                        <div>
                                                            <input class="range slider-round mx-5" type="range" min="4"
                                                                max="12" step="4" v-model="duration" @input="rangeSlide">
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
                                                    <button class="btn btn-secondary" @click="declineApp()"
                                                        data-bs-dismiss="modal">Update</button> <button
                                                        class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-else-if="val.loanApplication.status == 'incomplete'">
        <div class="card custom-card card-bg-warning">
            <div class="row g-0">
                <div class="col-md-8">
                    <div class="card-body rounded">
                        <h4 class="card-title mb-2 fw-semibold mt-4 mb-4">Finalize Your Application</h4>
                        <p class="card-text mb-0 fs-6 fw-semibold">The last step towards your financial goals. </p>
                        <p class="mb-0 card-text mt-3">Review your application for a smooth approval process and complete
                            any outstanding requirements.</p>
                        <button class="btn btn-light mt-3" @click="continueApplication">Complete Application</button>
                    </div>
                </div>
                <div class="col-md-4 text-end p-3 mt-3"> <img src="/assets/images/icon/decline.png"
                        class="img-fluid rounded-end w-50" alt="..."> </div>
            </div>
        </div>
    </div>
    <div v-else-if="val.loanApplication.status == 'completed'">
        <div class="card custom-card card-bg-success">
            <div class="row g-0">
                <div class="col-md-8">
                    <div class="card-body rounded">
                        <h4 class="card-title mb-2 fw-semibold mt-4 mb-4">Apply for new application</h4>
                        <p class="card-text mb-0 fs-6 fw-semibold">Starting a new application opens the door to fresh
                            opportunities and financial possibilities</p>
                        <p class="mb-0 card-text mt-3">Whether you're looking to embark on a new venture, make an
                            investment, or achieve personal goals, applying for a new application is the first step in your
                            journey. </p>
                        <button class="btn btn-light mt-3" data-bs-toggle="modal" href="#newapp">Apply Now</button>
                    </div>
                    <div class="modal fade" id="newapp">
                        <div class="modal-dialog modal-dialog-centered text-center" role="document">
                            <div class="modal-content modal-content-demo">
                                <div class="modal-header">
                                    <h6 class="modal-title text-center">How much do you need?</h6>
                                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-start front-slider">
                                    <div class="container justify-content-between">
                                        <h5 class="mb-4 mt-3">$<span id="slider-amount-value">{{ amount
                                        }}</span> <span class="slider_value_highlight">$2000</span></h5>
                                        <div>
                                            <input class="range slider-round mx-5" type="range" min="300" step="100"
                                                max="2000" v-model="amount" @input="rangeSlide">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="container mt-5">
                                        <h5 class="mb-3 mt-3"><span id="slider-duration-value">{{ duration
                                        }}</span> weeks
                                            <span class="slider_value_highlight">12 weeks</span>
                                        </h5>
                                        <div>
                                            <input class="range slider-round mx-5" type="range" min="4" max="12" step="4"
                                                v-model="duration" @input="rangeSlide">
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
                                    <button class="btn btn-secondary" @click="declineApp()"
                                        data-bs-dismiss="modal">Update</button> <button class="btn btn-danger"
                                        data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end p-3 mt-3"> <img src="/assets/images/icon/decline.png"
                        class="img-fluid rounded-end w-50" alt="..."> </div>
            </div>
        </div>
    </div>
    <div v-else>
        <div class="card custom-card card-bg-secondary"
            v-if="val.loanApplication.approved_amount && val.loanApplication.customer_confirmation == null">
            <div class="row p-4">
                <div
                    class="col-xxl-2 col-xl-2 large-icon col-lg-2 col-md-2 col-sm-12 col-12 d-flex align-items-center justify-content-center ecommerce-icon success px-0">

                    <i class="bx bx-money"></i>
                </div>
                <div
                    class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mt-2 px-0 border-end border-end border-white">
                    <h5 class="mb-2 fw-bold">Pre Approval (${{ val.loanApplication.approved_amount }})</h5>
                    <div class="text-muted mb-1 fs-12">
                        <span class="text-dark text-white fs-13 lh-1 vertical-bottom">
                            Great news! You have been pre-approved by Cashfaster.
                        </span>
                    </div>
                    <hr />
                    <div class=" mb-1 fs-12">
                        <span class="text-dark text-white fs-13 lh-1 vertical-bottom fw-light">
                            You have received pre-approval for a CashFaster loan with an amount of <b>${{
                                val.loanApplication.approved_amount }}</b>, from the applied loan amount of <b>${{
        val.loanApplication.amount }}</b>.
                        </span>
                    </div>
                </div>
                <div
                    class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 px-0 d-flex align-items-center justify-content-center ">
                    <div class="text-muted mb-1 fs-12 mx-3 mt-2">
                        <span class="text-dark text-white fs-13 lh-1 vertical-bottom">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end" v-if="loading">
                                <button type="submit" class="btn btn-lg btn-secondary label-btn label-end"><span
                                        class="spinner-border spinner-border-sm align-middle" role="status"
                                        aria-hidden="true"></span>
                                    Processing...</button>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end" v-else>
                                <button type="button" class="btn btn-danger btn-full-width " @click="preapproval(0)">
                                    Disagree
                                </button>
                                <button type="button" class="btn btn-success btn-full-width " @click="preapproval(1)">
                                    Agree
                                </button>

                            </div>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card custom-card card-bg-secondary" v-else-if="!val.user.email_verified_at">
            <div class="row p-4">
                <div
                    class="col-xxl-2 col-xl-2 large-icon col-lg-2 col-md-2 col-sm-12 col-12 d-flex align-items-center justify-content-center ecommerce-icon success px-0">

                    <i class="bx bx-envelope"></i>
                </div>
                <div
                    class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mt-2 px-0 border-end border-end border-white">
                    <h5 class="mb-2 fw-bold">Email Verification</h5>
                    <div class="text-muted mb-1 fs-12">
                        <span class="text-dark text-white fs-13 lh-1 vertical-bottom">
                            Verification link has been sent to the email address.
                        </span>
                    </div>
                    <hr />
                    <div class=" mb-1 fs-12">
                        <span class="text-dark text-white fs-13 lh-1 vertical-bottom fw-light">
                            We value your security and privacy. To ensure the safety of your account and the information you
                            share with us, please verify your email address.
                        </span>
                    </div>
                </div>
                <div
                    class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 px-0 d-flex align-items-center justify-content-center ">
                    <div class="text-muted mb-1 fs-12 mx-3 mt-2">
                        <span class="text-dark text-white fs-13 lh-1 vertical-bottom">
                            <button class="btn btn-light mt-3" @click="sendVerificationLink()">{{ email_btn }}</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card custom-card card-bg-secondary" v-else-if="!val.user.bank_verified">
            <div class="row p-4">
                <div
                    class="col-xxl-2 col-xl-2 large-icon col-lg-2 col-md-2 col-sm-12 col-12 d-flex align-items-center justify-content-center ecommerce-icon success px-0">

                    <i class="bx bxs-bank"></i>
                </div>
                <div
                    class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mt-2 px-0 border-end border-end border-white">
                    <h5 class="mb-2 fw-bold">Link your payment method</h5>
                    <div class="text-muted mb-1 fs-12">
                        <span class="text-dark text-white fs-13 lh-1 vertical-bottom">
                            Get 50 QuickLoan points by updating your payment methods
                        </span>
                    </div>
                    <hr />
                    <div class=" mb-1 fs-12">
                        <span class="text-dark text-white fs-13 lh-1 vertical-bottom fw-light">
                            Link your payment methods to get a chance to approve you loan faster
                        </span>
                    </div>
                </div>
                <div
                    class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 px-0 d-flex align-items-center justify-content-center ">
                    <div class="text-muted mb-1 fs-12 mx-3 mt-2">
                        <span class="text-dark text-white fs-13 lh-1 vertical-bottom">
                            <button class="btn btn-light mt-3" data-bs-toggle="modal" data-bs-target="#BankVerification">Add
                                Payment Methods</button>
                            <VerifyBankComponent :val="val"></VerifyBankComponent>
                        </span>
                    </div>
                </div>
            </div>

        </div>
        <div class="card custom-card card-bg-secondary" v-else-if="!val.user.id_verified">
            <div class="row p-4">
                <div
                    class="col-xxl-2 col-xl-2 large-icon col-lg-2 col-md-2 col-sm-12 col-12 d-flex align-items-center justify-content-center ecommerce-icon success px-0">

                    <i class="bx bxs-id-card"></i>
                </div>
                <div
                    class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mt-2 px-0 border-end border-end border-white">
                    <h5 class="mb-2 fw-bold">Verify Your Identity</h5>
                    <div class="text-muted mb-1 fs-12">
                        <span class="text-dark text-white fs-13 lh-1 vertical-bottom">
                            For Enhanced Security and Access
                        </span>
                    </div>
                    <hr />
                    <div class=" mb-1 fs-12">
                        <span class="text-dark text-white fs-13 lh-1 vertical-bottom fw-light">
                            To ensure the safety of your account and provide you with a better user experience, we require
                            you to verify your identity
                        </span>
                    </div>
                </div>
                <div
                    class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 px-0 d-flex align-items-center justify-content-center ">
                    <div class="text-muted mb-1 fs-12 mx-3 mt-2">
                        <span class="text-dark text-white fs-13 lh-1 vertical-bottom">
                            <button class="btn btn-light mt-3" data-bs-toggle="modal"
                                data-bs-target="#idVerification">Verify
                                Your Identity</button>
                            <IdVerificationComponent :val="val"></IdVerificationComponent>
                        </span>
                    </div>
                </div>
            </div>

        </div>
        <div class="card custom-card card-bg-secondary" v-else-if="!val.user.document_verified">
            <div class="row p-4">
                <div
                    class="col-xxl-2 col-xl-2 large-icon col-lg-2 col-md-2 col-sm-12 col-12 d-flex align-items-center justify-content-center ecommerce-icon success px-0">

                    <i class="bx bxs-file-plus"></i>
                </div>
                <div
                    class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mt-2 px-0 border-end border-end border-white">
                    <h5 class="mb-2 fw-bold">Upload Additional Documents</h5>
                    <div class="text-muted mb-1 fs-12">
                        <span class="text-dark text-white fs-13 lh-1 vertical-bottom">
                            To ensure the security of your account and expedite the processing of your loan application, we
                            kindly request you to provide additional documents.
                        </span>
                    </div>
                    <hr />
                    <div class=" mb-1 fs-12">
                        <span class="text-dark text-white fs-13 lh-1 vertical-bottom fw-light">
                            Thank you for your cooperation. If you have any questions or need assistance, please contact our
                            support team.
                        </span>
                    </div>
                </div>
                <div
                    class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 px-0 d-flex align-items-center justify-content-center ">
                    <div class="text-muted mb-1 fs-12 mx-3 mt-2">
                        <span class="text-dark text-white fs-13 lh-1 vertical-bottom">
                            <button class="btn btn-light mt-3" data-bs-toggle="modal"
                                data-bs-target="#DocumentVerification">Upload Document</button>
                            <DocumentComponent :val="val"></DocumentComponent>
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import IdVerificationComponent from './IdVerificationComponent.vue';
import VerifyBankComponent from './VerifyBankComponent.vue';
import DocumentComponent from './DocumentComponent.vue';
export default {
    props: ['val'],
    components: {
        IdVerificationComponent,
        VerifyBankComponent,
        DocumentComponent
    },
    data() {
        return {
            baseUrl: import.meta.env.VITE_APP_URL,
            email_btn: 'Send Verification Link',
            amount: 500,
            duration: 4,
            frequency: 'weekly',
            loading: false,
        };
    },
    methods: {
        continueApplication() {
            window.location.href = this.baseUrl + "application/apply";
        },
        selectedPayCycle(cycle) {
            this.frequency = cycle;
        },
        sendVerificationLink() {
            this.email_btn = 'Sending...';
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
            axios.post(this.baseUrl + 'api/auth/sendLink', { user_id: this.val.user.id })
                .then(response => {
                    console.log(this.response);
                    this.email_btn = 'Link Sent';

                })
                .catch(error => {
                    this.error = error.response.data.message;
                });
        },
        preapproval($i) {
            this.loading = true;
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;

            axios.post(this.baseUrl + 'api/auth/customer/preapproved', {
                app_id: this.val.loanApplication.id,
                customer_confirmation: $i
            })
                .then(response => {
                    // Handle success
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                })
                .catch(error => {
                    // Handle error
                    this.error = error.response.data.message;
                })
                .finally(() => {
                    // This block will be executed regardless of success or failure
                    this.loading = false; // Assuming you want to stop the loading indicator
                });
        },
        declineApp() {
            sessionStorage.setItem('amount', this.amount);
            sessionStorage.setItem('duration', this.duration);
            sessionStorage.setItem('frequency', this.frequency);
            this.continueApplication();
        }
    }
}
</script>