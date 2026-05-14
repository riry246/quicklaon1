<template>
    <div class="card custom-card ">
        <div class=" d-flex flex-column p-0">
            <div class="card-body text-fixed-white gradient-bg">
                <div class="card-text fs-6 fw-semibold text-center mt-3">Earn 50 points by sharing your referral</div>
                <div class="text-center mt-5 mb-5">
                    <img src="/assets/images/icon/refer.png" class="img-fluid rounded-end w-50" alt="...">
                </div>
            </div>
            <div class="card-footer ">
                <h4 class="fs-4">Refer your friends and earn rewards.</h4>
                <p class="text-muted pb-2 pt-2">Share the love by inviting your friends to join QuickLoan. When they sign
                    up using your referral link, both you and your friend will enjoy fantastic benefits. It's a win-win!
                    Start referring now and make every friend count.</p>
                <a class="btn btn-secondary d-grid mt-3 mb-3" data-bs-effect="effect-super-scaled" data-bs-toggle="modal"
                    href="#refer">Invite Now</a>
            </div>
        </div>
    </div>
    <div class="modal fade" id="refer">
        <div class="modal-dialog modal-dialog-centered text-center" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title text-center">Refer Your Friend</h6>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-start front-slider">
                    <p class="mb-3 op-7 fw-normal text-center fs-14">Earn <span class="fw-semibold">50 Cashfaster
                            points</span> <br />for each successful referral.</p>
                    <div class="card custom-card">
                        <div class="card-body">
                            <ul class="nav nav-tabs tab-style-1 d-sm-flex d-block nav-justified" role="tablist">
                                <li class="nav-item me-sm-2 me-0">
                                    <a class="nav-link active" data-bs-toggle="tab" data-bs-target="#sms"
                                        aria-current="page" href="#sms">SMS</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" data-bs-target="#email"
                                        href="#email">Email</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" data-bs-target="#copy" href="#copy">Copy
                                        Link</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="alert alert-solid-success alert-dismissible fade show" v-if="success">
                                    {{ success }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                                            class="bi bi-x"></i></button>
                                </div>
                                <div class="tab-pane border-0 active p-0" id="sms" role="tabpanel"
                                    aria-labelledby="sms">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12"> <label for="input-tel"
                                            class="form-label">Mobile</label>
                                        <input type="tel" v-model="mobile" class="form-control" 
                                            placeholder="+1100-2031-1233" maxlength="10">
                                        <span class="error_msg" role="alert" v-if="error">{{ error }}</span>
                                    </div>
                                    <div class="d-grid mt-4 pt-2">
                                        <button type="button" class="btn btn-lg btn-secondary label-btn label-end"
                                            v-if="processing"><span class="spinner-border spinner-border-sm align-middle"
                                                role="status" aria-hidden="true"></span>
                                            Processing...</button>
                                        <button type="button" class="btn btn-secondary btn-wave btn-lg" v-else
                                            @click="sendReferral('sms')">Send SMS</button>
                                    </div>
                                </div>
                                <div class="tab-pane border-0 p-0" id="email" role="tabpanel"
                                    aria-labelledby="email">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <label for="input-tel" class="form-label">Email</label>
                                        <input type="email" class="form-control" 
                                            placeholder="example@cashfaster.com.au" v-model="email">
                                        <span class="error_msg" role="alert" v-if="error">{{ error }}</span>
                                    </div>
                                    <div class="d-grid mt-4 pt-2">
                                        <button type="button" class="btn btn-lg btn-secondary label-btn label-end"
                                            v-if="processing"><span class="spinner-border spinner-border-sm align-middle"
                                                role="status" aria-hidden="true"></span>
                                            Processing...</button>
                                        <button v-else type="button" class="btn btn-secondary btn-wave btn-lg"
                                            @click="sendReferral('email')">Send Email</button>
                                    </div>
                                </div>
                                <div class="tab-pane border-0 p-0" id="copy" role="tabpanel" aria-labelledby="copy">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <label for="input-tel" class="form-label">Referral Link</label>
                                        <input type="text" class="form-control" 
                                             :value="referralLink" readonly />
                                            <span class="error_msg" role="alert" v-if="error">{{ error }}</span>
                                        <div class="d-grid mt-4 pt-2">
                                            <button @click="copyToClipboard" type="button"
                                                class="btn btn-secondary btn-wave btn-lg">
                                                Copy Link
                                            </button>
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
</template>
<script>
export default {
    props: ['val'],
    data() {
        return {
            baseUrl: import.meta.env.VITE_APP_URL,
            mobile: '',
            email: '',
            processing: false,
            user_id: '',
            token: '',
            error: '',
            success: '',
            referralLink: 'cashfaster.com.au/referral?id=Ekks78',
            
        };
    },
    mounted() {
        this.token = sessionStorage.getItem('token');
        this.user_id = sessionStorage.getItem('user_id');
        this.referralLink = 'cashfaster.com.au/referral?id='+this.val.user.referral.code;
    },
    methods: {
        sendReferral(method) {
            this.processing = true;
            this.success = null;
            this.error = null;
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;

            axios.post(this.baseUrl + 'api/auth/referral', { user_id: this.user_id, mobile: this.mobile, email: this.email, method: method })
                .then(response => {
                    this.success = response.data.message;
                })
                .catch(error => {
                    if (error.response) {
                        // The request was made and the server responded with a status code
                        this.error = `Error: ${error.response.status} - ${error.response.data.message}`;
                    } else if (error.request) {
                        // The request was made but no response was received
                        this.error = 'No response received from the server.';
                    } else {
                        // Something happened in setting up the request that triggered an Error
                        this.error = 'Error setting up the request.';
                    }
                })
                .finally(() => {
                    this.processing = false;
                });
        },
        async copyToClipboard() {
            try {
                await navigator.clipboard.writeText(this.referralLink);
                this.success = "Success! 🎉 Referral link copied to clipboard. Share it with your friends and enjoy the benefits together! 🚀";
            } catch (err) {
                this.error = "Oops! It seems there was an issue copying the text to the clipboard. Please try again or manually copy the text";
            }
        },
    },
}
</script>