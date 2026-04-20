<template>
    <div class="modal fade" id="BankVerification" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Bank Verification</h6>
                </div>
                <div class="modal-body pt-4">
                    <form>
                        <div>
                            <h5><i class="bi bi-arrow-left pointer-cursor" id="backButton" @click="backButton"></i> Bank
                                Verification</h5>
                        </div>

                        <p class="form-check-label text-muted fw-normal">Your data secure with us
                        </p>
                        <div class="alert alert-solid-success alert-dismissible fs-15 fade show mb-4" v-if="success">
                            {{ success }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                                    class="bi bi-x"></i></button>
                        </div>
                        <div class="row gy-4 mt-1">
                            <h6 class="text-muted" v-if="list">Select your bank for verification</h6>
                            <span class="error_msg" role="alert" v-if="errors.bank">{{ errors.bank }}</span>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="card custom-card border border-info" v-if="list">
                                    <div class="card-body bank-list">
                                        <div class="row gy-4">
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 text-center"
                                                v-for="bank in bank_list" :key="bank" @click="selectBank(bank)">
                                                <a class="btn btn-outline-secondary full-width-button label-btn p-3 "
                                                    :class="{ active: bank.id === formData.bank }">
                                                    <img :src="getImagePath(bank.logo)" style="width: 100%;" />

                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card custom-card border border-info" v-else>
                                    <div class="card-body">
                                        <h5 @click="list = true"><i class="bi bi-arrow-left pointer-cursor"
                                                id="backButtontobank" @click="backButtontobank"></i> </h5>
                                        <div class="row gy-4">
                                            <div class="bank-logo-center align-items-center mx-auto" style="width: 300px;">
                                                <img :src="getImagePath(selectedBank.logo)" style="width: 50%;" />

                                            </div>
                                            <div v-if="selectaccountsection">
                                                <label for="input-label" class="form-label text-muted mt-5">Choose Primary
                                                    Account</label>
                                                <div class="row gy-4 mt-3 reason-block button-set-block">
                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 reason"
                                                        v-for="account in account_list" :key="index"
                                                        @click="selectAccount(account)">
                                                        <a class="btn btn-outline-secondary full-width-button label-btn p-3 "
                                                            :class="{ active: account.accountNo === formData.accountNo }">

                                                            {{ account.name }}
                                                        </a>
                                                    </div>
                                                    <button type="button" class="btn btn-secondary btn-full-width "
                                                        @click="addBank()">
                                                        Add Payment
                                                    </button>
                                                </div>
                                            </div>
                                            <div v-else>

                                                <label for="input-label" class="form-label text-muted mt-5">Corporate
                                                    Login</label>
                                                <br />
                                                <span class="error_msg" role="alert" v-if="error">{{ error }}</span>
                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12"
                                                    v-if="selectedBank.username">
                                                    <label for="input-label" class="form-label text-muted">{{
                                                        selectedBank.username
                                                    }}</label>
                                                    <input type="text" class="form-control" id="input"
                                                        v-model="formData.loginId">
                                                </div>
                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12"
                                                    v-if="selectedBank.password">
                                                    <label for="input-label" class="form-label text-muted">{{
                                                        selectedBank.password
                                                    }}</label>
                                                    <input type="password" class="form-control" id="input-label"
                                                        v-model="formData.password">
                                                </div>
                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12"
                                                    v-if="selectedBank.secret">
                                                    <label for="input-label" class="form-label text-muted">{{
                                                        selectedBank.secret }}</label>
                                                    <input type="password" class="form-control" id="input-label"
                                                        v-model="formData.secret">
                                                </div>
                                                <div class="row gy-4 mt-3 mx-1">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                            id="privacy-policy"
                                                            v-model="formData.agreed_direct_debit_from_monoova">
                                                        <p class="form-check-label fs-15 text-muted" for="privacy-policy">
                                                            Authorisation <a target="_blank" class="fw-semibold"
                                                                href="https://www.monoova.com/ddrsa">Direct
                                                                Debit</a> from this account.
                                                        </p>
                                                        <span class="error_msg" role="alert"
                                                            v-if="errors.agreed_direct_debit_from_monoova">{{
                                                                errors.agreed_direct_debit_from_monoova }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-4">
                                                    <button type="button" class="btn btn-secondary btn-full-width "
                                                        v-if="loadingbank">
                                                        Loading..
                                                    </button>
                                                    <button type="button" class="btn btn-secondary btn-full-width "
                                                        @click="verifyBank()" v-else>
                                                        Verify your bank acccounnt
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
            formData: {
                bank: '',
                institution: '',
                loginId: '',
                password: '',
                secret: '',
                app_id: this.val.loanApplication.id,
                mobile: this.val.user.mobile,
                user_id: this.val.user.id,
                accountNo: '',
                account_list: ''
            },
            bank_list: [],
            account_list: [],
            errors: [],
            selectedBank: [],
            list: true,
            selectaccountsection: false,
            loadingbank: false,
            success: '',
        };
    },
    computed: {
        imageBaseUrl() {
            return '/assets/';
        },
    },
    mounted() {
        this.getBank();
    },
    methods: {
        validateForm() {
            this.errors = [];
            if (!this.formData.accountNo) {
                this.errors.bank = 'Please select account';
            }
            if (!this.formData.bank) {
                this.errors.bank = 'Please select bank';
            }


            return Object.keys(this.errors).length === 0;
        },
        submitForm() {
            const isValid = this.validateForm();

            if (isValid) {
                this.sendDataToParent();
            } else {
                console.log('Form is invalid. Please correct the errors.');
            }
        },

        sendDataToParent() {
            this.$emit('sendData', this.formData);
        },
        backButton() {
            this.$emit('backButton');
        },
        backButtontobank() {
            this.list = true;
        },

        getBank() {
            axios.defaults.headers.common['Authorization'] = `Bearer ${sessionStorage.getItem('token')}`;
            axios.get(this.baseUrl + 'api/auth/bank')
                .then(response => {
                    this.bank_list = response.data.data;
                })
                .catch(error => {
                    this.error = error.response.data.message;
                });
        },
        getImagePath(imageFilename) {
            // Concatenate the base URL and image filename to create the full image path
            return this.imageBaseUrl + imageFilename;
        },
        selectBank(bank) {
            //console.log(bank);
            this.formData.bank = bank.id;
            this.formData.institution = bank.basiq_code;
            this.selectedBank = bank;
            this.list = false;
        },
        validateForm1() {
            this.errors = [];
            if (!this.formData.password) {
                this.errors.password = 'This field is required';
            }
            if (!this.formData.loginId) {
                this.errors.loginId = 'This field is required';
            }
            if (!this.formData.bank) {
                this.errors.bank = 'Please select bank';
            }
            if (!this.formData.agreed_direct_debit_from_monoova) {
                this.errors.agreed_direct_debit_from_monoova = 'Please check the direct debit terms and conditions';
            }


            return Object.keys(this.errors).length === 0;
        },
        verifyBank() {
            this.error = null;
            const isValid = this.validateForm1();
            if (isValid) {
                this.loadingbank = true;
                axios.defaults.headers.common['Authorization'] = `Bearer ${sessionStorage.getItem('token')}`;
                axios.post(this.baseUrl + 'api/auth/bankverify', { mobile: this.formData.mobile, app_id: this.formData.app_id, institution: this.formData.institution, loginId: this.formData.loginId, password: this.formData.password })
                    .then(response => {
                        this.account_list = response.data.accounts;
                        this.formData.account_list = response.data.accounts;
                        if (this.account_list.length > 0) {
                            this.selectaccountsection = true;
                        } else {
                            this.error = 'Oops!! something went wrong please try again.'
                        }

                    })
                    .catch(error => {
                        this.error = 'Oops!! something went wrong please try again.'
                    })
                    .finally(() => {
                        this.loadingbank = false;
                    });
            }
        },
        selectAccount(account) {
            this.formData.accountNo = account.accountNo;
        },
        addBank() {
            axios.defaults.headers.common['Authorization'] = `Bearer ${sessionStorage.getItem('token')}`;
            axios.post(this.baseUrl + 'api/auth/customer/addPayment', { formData: this.formData })
                .then(response => {
                    this.success = "Bank Account added successfully";

                    setTimeout(() => {
                        location.reload();
                    }, 5000);

                })
                .catch(error => {
                    this.error = error.response.data.message;
                });
        }

    }
}

</script>