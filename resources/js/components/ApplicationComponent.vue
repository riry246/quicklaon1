<template>
    <div class="row mx-auto ">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 px-5 smp-0">
            <h2>{{ heading }}</h2>
            <p class="fs-16 op-7 fw-normal">{{ subheading }}</p>
        </div>
        <StepComponent :step="step"></StepComponent>

        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 p-5 smp-0">
            <!--Error Message Start-->
            <div class="alert alert-solid-danger alert-dismissible fs-15 fade show mb-4" v-if="error">
                {{ error }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                        class="bi bi-x"></i></button>
            </div>
            <div class="alert alert-solid-danger alert-dismissible fs-15 fade show mb-4" v-if="bankError">
                {{ bankError }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                        class="bi bi-x"></i></button>
            </div>
            <!--Error Message End-->
            <BasicInformationComponent :val="val" v-if="step == 1" @sendData="handleDataFromChild" ref="childComponent">
            </BasicInformationComponent>
            <ReasonComponent :val="val" v-if="step == 2" @backButton="previousStep" @sendData="handleDataFromChild"
                ref="childComponent"></ReasonComponent>
            <IncomeComponent :val="val" v-if="step == 3" @backButton="previousStep" @sendData="handleDataFromChild"
                ref="childComponent"></IncomeComponent>
            <EmploymentComponent :val="val" v-if="step == 4" @backButton="previousStep" @sendData="handleDataFromChild"
                ref="childComponent"></EmploymentComponent>
            <QuestionsComponent :val="val" v-if="step == 5" @backButton="previousStep" @sendData="handleDataFromChild"
                ref="childComponent"></QuestionsComponent>
            <TermsComponent :val="val" v-if="step == 6" @backButton="previousStep" @sendData="handleDataFromChild"
                ref="childComponent"></TermsComponent>
            <BankComponent :val="val" v-if="step == 7" @backButton="previousStep" @sendData="handleDataFromChild"
                ref="childComponent"></BankComponent>
            <ExpensesComponent :val="val" v-if="step == 8" @backButton="previousStep" @sendData="handleDataFromChild"
                ref="childComponent"></ExpensesComponent>
            <AccountComponent :val="val" v-if="step == 9" @backButton="previousStep" @sendData="handleDataFromChild"
                ref="childComponent"></AccountComponent>
            <SuccessComponent :val="val" :message="message" v-if="step == 10"></SuccessComponent>

            <!-- Submit Button -->
            <div class="row mt-5" v-if="step < 10">
                <div class="d-grid gap-2 col-6 ">
                    <button type="submit" class="btn btn-lg btn-secondary label-btn label-end" v-if="loading"><span
                            class="spinner-border spinner-border-sm align-middle" role="status"
                            aria-hidden="true"></span>
                        Processing...</button>
                    <button type="submit" class="btn btn-secondary label-btn label-end" v-else
                        @click="handleButtonClick">
                        Save and Continue
                        <i class="ri-arrow-right-s-line label-btn-icon ms-2"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>
<script>
import StepComponent from './StepComponent.vue';
import BasicInformationComponent from './steps/BasicInformationComponent.vue';
import ReasonComponent from './steps/ReasonComponent.vue';
import IncomeComponent from './steps/IncomeComponent.vue';
import EmploymentComponent from './steps/EmploymentComponent.vue';
import QuestionsComponent from './steps/QuestionsComponent.vue';
import TermsComponent from './steps/TermsComponent.vue';
import BankComponent from './steps/BankComponent.vue';
import ExpensesComponent from './steps/ExpensesComponent.vue';
import AccountComponent from './steps/AccountComponent.vue';
import SuccessComponent from './steps/SuccessComponent.vue';

export default {
    components: {
        StepComponent,
        BasicInformationComponent,
        ReasonComponent,
        IncomeComponent,
        EmploymentComponent,
        QuestionsComponent,
        TermsComponent,
        BankComponent,
        ExpensesComponent,
        AccountComponent,
        SuccessComponent
    },
    data() {
        return {
            baseUrl: import.meta.env.VITE_APP_URL,
            token: '',
            user_id: '',
            step: '',
            val: '',
            formData: '',
            app_id: '',
            error: '',
            bankError: '',
            loading: false,
            heading: '',
            subheading: '',
            amount: '',
            duration: '',
            frequency: '',
            leadID: '',
            message: '',
            referrerCode: '',
            bankStatus: '',
        };
    },
    mounted() {

        let queryString = window.location.search;
        let urlParams = new URLSearchParams(queryString);
        this.leadID = urlParams.get('lead');
        this.referrerCode = urlParams.get('referrerCode');
        this.bankStatus = urlParams.get('status');
        this.bankError = urlParams.get('errorText');

        if (this.leadID) {
            sessionStorage.setItem('user_id', urlParams.get('u'));
            sessionStorage.setItem('amount', '2000');
            sessionStorage.setItem('duration', '12');
            sessionStorage.setItem('frequency', 'weekly');
        }


        this.token = sessionStorage.getItem('token');
        this.user_id = sessionStorage.getItem('user_id');
        this.getApplicationInfo();
        this.amount = sessionStorage.getItem('amount');
        this.duration = sessionStorage.getItem('duration');
        this.frequency = sessionStorage.getItem('frequency');
    },
    methods: {
        appStep() {
            this.error = null;
            if (this.step == 1) {
                this.heading = 'Basic Information';
                this.subheading = 'Your personal information';
            } else if (this.step == 2) {
                this.heading = 'Loan Details';
                this.subheading = 'Select your reason for a loan with us today';
            } else if (this.step == 3) {
                this.heading = 'Income';
                this.subheading = 'Enter your income details';
            } else if (this.step == 4) {
                this.heading = 'Employment Details';
                this.subheading = 'Enter your employment details';
            }
            else if (this.step == 5) {
                this.heading = 'Terms and Conditions';
                this.subheading = 'Read and answer the below terms and conditions carefully';
            }
            else if (this.step == 6) {
                this.heading = 'Terms and Conditions';
                this.subheading = 'Provide a personal reference and agree to our terms and conditions';
            }
            else if (this.step == 7) {
                this.heading = 'Bank Verification';
                this.subheading = 'Your data secure with us';
            }
            else if (this.step == 8) {
                this.heading = 'Expenses';
                this.subheading = 'Enter your weekly expenses';
            }
            else if (this.step == 9) {
                this.heading = 'Create Your Account';
                this.subheading = 'Create your account credentials';
            }
            else if (this.step == 10) {
                if (this.message) {
                    this.heading = 'Application Declined';
                    this.subheading = this.message;
                } else {
                    if (this.val.application.status == 'declined') {
                        this.heading = 'Application Declined';
                        this.subheading = 'Your previous loan appplication was declined';
                    } else {
                        this.heading = 'Application Submitted';
                        this.subheading = 'Application is submitted for review.';
                    }
                }


            }
        },
        getApplicationInfo() {
            this.loading = true;
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
            axios.post(this.baseUrl + 'api/auth/getapplication', {
                user_id: this.user_id,
                amount: sessionStorage.getItem('amount'),
                duration: sessionStorage.getItem('duration'),
                frequency: sessionStorage.getItem('frequency'),
                referrerCode: this.referrerCode,
                bankStatus: this.bankStatus,
            })
                .then(response => {
                    this.val = response.data;
                    this.step = this.val.application.step;
                    if(this.referrerCode && this.bankStatus == 'COMPLETE'){
                            //this.step++;
                            
                    }   
                    this.app_id = this.val.application.id;
                    sessionStorage.setItem('app_id', this.app_id);
                    sessionStorage.setItem('mobile', this.val.user.mobile);
                    sessionStorage.setItem('amount', this.val.application.amount);
                    sessionStorage.setItem('duration', this.val.application.duration);
                    sessionStorage.setItem('frequency', this.val.application.frequency);
                    sessionStorage.setItem('maxAmount', 2000);
                    sessionStorage.setItem('maxDuration', 12);
                    this.appStep();
                    this.loading = false;
                })
                .catch(error => {
                    this.error = error.response.data.message;
                    this.loading = false;
                });
        },
        handleDataFromChild(data) {
            this.formData = data;
            this.submitApplication();
        },
        handleButtonClick() {
            const childComponent = this.$refs.childComponent;
            childComponent.submitForm();
        },
        previousStep() {
            console.log(this.step);
            if (this.step > 1) {
                // this.getApplicationInfo();
                this.step--;
                console.log(this.step);
            }
        },
        submitApplication() {
            this.loading = true;
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
            axios.post(this.baseUrl + 'api/auth/store', { step: this.step, user_id: this.user_id, app_id: this.app_id, amount: this.amount, duration: this.duration, formData: this.formData })
                .then(response => {
                    this.val = response.data;
                    this.step = this.val.next_step;
                    this.message = this.val.message;
                    if (this.message) {
                        this.getApplicationInfo();
                    }
                    this.appStep();
                    this.loading = false;
                })
                .catch(error => {
                    this.error = error.response.data.message;
                    this.loading = false;
                });
        },
    },

}
</script>