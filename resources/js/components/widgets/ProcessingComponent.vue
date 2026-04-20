<template>
    <div class="card-body">
        <div class="col-xl-12 col-xl-6">
            <div class="card custom-card no-border-card">
                <div class="card-body p-0 overflow-hidde">
                    <div class="timeline-front">
                        <ul class="list-unstyled timeline-widget mb-0 my-3  ">
                            <li class="timeline-widget-list completed_step_dashboard">
                                <div class="d-flex align-items-top">
                                    <div class="me-2">

                                        <span class="avatar avatar-rounded text-defaulttext-default fs-20  avatar-rounded">
                                            <i class="bi bi-check-lg fs-6 align-middle"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-wrap flex-fill align-items-center justify-content-between ">
                                        <div>
                                            <p
                                                class="mb-1 text-truncate timeline-widget-content text-wrap fs-6 fw-semibold">
                                                Mobile Verification</p>

                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="timeline-widget-list completed_step_dashboard">
                                <div class="d-flex align-items-top">
                                    <div class="me-2 text-center">
                                        <span
                                            class="avatar avatar-rounded text-defaulttext-default fs-20  avatar-rounded"><i
                                                class="bi bi-check-lg fs-6 align-middle"></i></span>
                                    </div>
                                    <div class="d-flex flex-wrap flex-fill align-items-center justify-content-between">
                                        <div>
                                            <p
                                                class="mb-1 text-truncate timeline-widget-content text-wrap fs-6 fw-semibold">
                                                Payment Setup</p>

                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="timeline-widget-list"
                                :class="{ 'completed_step_dashboard': val.user.email_verified_at }">
                                <div class="d-flex align-items-top">
                                    <div class="me-2 text-center">
                                        <span class="avatar avatar-rounded text-defaulttext-default fs-20  avatar-rounded">
                                            <i class="bi bi-exclamation fs-6 align-middle"
                                                v-if="!val.user.email_verified_at"></i>
                                            <i class="bi bi-check-lg fs-6 align-middle" v-else></i></span>
                                    </div>
                                    <div class="d-flex flex-wrap flex-fill align-items-center justify-content-between">
                                        <div>
                                            <p
                                                class="mb-1 text-truncate timeline-widget-content text-wrap fs-6 fw-semibold">
                                                Email Verification</p>
                                            <p class="mb-0 fs-12 lh-1 text-muted" v-if="!val.user.email_verified_at">
                                                Pending
                                            </p>
                                        </div>
                                        <a href="javascript:void(0);" class="btn btn-outline-secondary my-2 "
                                            v-if="!val.user.email_verified_at" @click="sendVerificationLink()">{{ email_btn
                                            }}</a>
                                    </div>
                                </div>
                            </li>
                            <li class="timeline-widget-list"
                                :class="{ 'completed_step_dashboard': val.user.document_verified }">
                                <div class="d-flex align-items-top">
                                    <div class="me-2 text-center">
                                        <span class="avatar avatar-rounded text-defaulttext-default fs-20  avatar-rounded">
                                            <i class="bi bi-exclamation fs-6 align-middle"
                                                v-if="!val.user.document_verified"></i>
                                            <i class="bi bi-check-lg fs-6 align-middle" v-else></i></span>
                                    </div>
                                    <div class="d-flex flex-wrap flex-fill align-items-center justify-content-between">
                                        <div>
                                            <p
                                                class="mb-1 text-truncate timeline-widget-content text-wrap fs-6 fw-semibold">
                                                Documents</p>
                                            <p class="mb-0 fs-12 lh-1 text-muted" v-if="!val.user.document_verified">
                                                Pending
                                            </p>
                                        </div>
                                        <a href="javascript:void(0);" class="btn btn-outline-secondary my-2 "
                                            data-bs-toggle="modal" data-bs-target="#DocumentVerification"
                                            v-if="!val.user.document_verified">Upload</a>

                                        <DocumentComponent :val="val"></DocumentComponent>

                                    </div>
                                </div>
                            </li>
                            <li class="timeline-widget-list"
                                :class="{ 'completed_step_dashboard': val.user.bank_verified }">
                                <div class="d-flex align-items-top">
                                    <div class="me-2 text-center">
                                        <span
                                            class="avatar avatar-rounded text-defaulttext-default fs-20  avatar-rounded"><i
                                                class="bi bi-exclamation fs-6 align-middle"
                                                v-if="!val.user.bank_verified"></i>
                                            <i class="bi bi-check-lg fs-6 align-middle" v-else></i></span>
                                    </div>
                                    <div class="d-flex flex-wrap flex-fill align-items-center justify-content-between">
                                        <div>
                                            <p
                                                class="mb-1 text-truncate timeline-widget-content text-wrap fs-6 fw-semibold">
                                                Bank Verification</p>
                                            <p class="mb-0 fs-12 lh-1 text-muted" v-if="!val.user.bank_verified">
                                                Pending
                                            </p>
                                        </div>
                                        <a ref="verifyButton" href="javascript:void(0);" class="btn btn-outline-secondary my-2 "
                                            data-bs-toggle="modal" data-bs-target="#BankVerification"
                                            v-if="!val.user.bank_verified">Verify
                                            Now</a>
                                        <VerifyBankComponent :val="val"></VerifyBankComponent>
                                    </div>
                                </div>
                            </li>
                            <li class="timeline-widget-list" :class="{ 'completed_step_dashboard': val.user.id_verified }">
                                <div class="d-flex align-items-top">
                                    <div class="me-2 text-center">
                                        <span
                                            class="avatar avatar-rounded text-defaulttext-default fs-20  avatar-rounded"><i
                                                class="bi bi-exclamation fs-6 align-middle"
                                                v-if="!val.user.id_verified"></i>
                                            <i class="bi bi-check-lg fs-6 align-middle" v-else></i></span>
                                    </div>
                                    <div class="d-flex flex-wrap flex-fill align-items-center justify-content-between">
                                        <div>
                                            <p
                                                class="mb-1 text-truncate timeline-widget-content text-wrap fs-6 fw-semibold">
                                                ID Verification</p>
                                        <p class="mb-0 fs-12 lh-1 text-muted" v-if="!val.user.id_verified">
                                                Pending
                                            </p>
                                        </div>
                                        <a href="javascript:void(0);" class="btn btn-outline-secondary my-2 "
                                            data-bs-toggle="modal" data-bs-target="#idVerification" v-if="!val.user.id_verified">Verify
                                            Now</a>
                                            
                                    </div>
                                </div>
                                <IdVerificationComponent :val="val"></IdVerificationComponent>
                            </li>
                            <li class="timeline-widget-list" :class="{ 'completed_step_dashboard': val.user.contract_signed }">
                                <div class="d-flex align-items-top">
                                    <div class="me-2 text-center">
                                        <span
                                            class="avatar avatar-rounded text-defaulttext-default fs-20  avatar-rounded"><i
                                                class="bi bi-exclamation fs-6 align-middle"
                                                v-if="!val.user.contract_signed"></i>
                                            <i class="bi bi-check-lg fs-6 align-middle" v-else></i></span>
                                    </div>
                                    <div class="d-flex flex-wrap flex-fill align-items-center justify-content-between">
                                        <div>
                                            <p
                                                class="mb-1 text-truncate timeline-widget-content text-wrap fs-6 fw-semibold">
                                                Contract Signing</p>
                                        <p class="mb-0 fs-12 lh-1 mb-3 text-muted" v-if="!val.user.contract_signed">
                                                Pending
                                            </p>
                                        </div>
                                        <a href="javascript:void(0);" class="btn btn-outline-secondary my-2 "
                                            @click="contract" v-if="val.contract">Sign</a>
                                            
                                    </div>
                                </div>
                                <IdVerificationComponent :val="val"></IdVerificationComponent>
                            </li>
                        </ul>
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
            email_btn: 'Send link',
            contract_signing: false,
        };
    },
    mounted() {
        this.token = sessionStorage.getItem('token');
        this.user_id = sessionStorage.getItem('user_id');
        let queryString = window.location.search;
        let urlParams = new URLSearchParams(queryString);
        this.referrerCode = urlParams.get('referrerCode');
        this.bankStatus = urlParams.get('status');

        if (this.referrerCode && this.bankStatus == 'COMPLETE') {
            this.$refs.verifyButton.click();
        }
    },
    methods: {
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
        contract(){
            this.contract_signing = true;
            this.$emit('sendData', this.contract_signing);
        }
    },
}
</script>