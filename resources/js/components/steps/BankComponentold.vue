<template>
    <form>
        <div class="row gy-4 button-set-block">
            <h6 class="text-muted" v-if="list">Select your bank for verification</h6>
            <div class="alert alert-solid-danger alert-dismissible fade show" v-if="errors.bank"> {{ errors.bank }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                        class="bi bi-x"></i></button>
            </div>

            <div class="col-xl-10 col-lg-10 col-md-10 col-sm-12 ">
                <div class="card custom-card noshadow border" v-if="list">
                    <div class="card-body bank-list">
                        <div class="row gy-4">
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 text-center" v-for="bank in bank_list"
                                :key="bank" @click="selectBank(bank)">
                                <a class="btn btn-outline-secondary btn-secondary-light full-width-button label-btn p-3 "
                                    :class="{ active: bank.id === formData.bank }">
                                    <img :src="getImagePath(bank.logo)" style="width: 100%;" />

                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card custom-card noshadow border" v-else>
                    <div class="card-body">

                        <div class="row gy-2 button-set-block">
                            <div style="width: 400px;">
                                <img :src="getImagePath(selectedBank.logo)" style="width: 50%;" />
                            </div>
                            <div v-if="selectaccountsection">
                                <label for="floatingInput" class=" text-muted mt-5">Choose Primary Account</label>
                                <div class="row gy-4 mt-3 reason-block button-set-block">
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 reason" v-for="account in account_list"
                                        :key="index" @click="selectAccount(account)">
                                        <a class="btn btn-outline-secondary btn-secondary-light full-width-button label-btn p-3 eclipse "
                                            :class="{ active: account.accountNo === formData.accountNo }">

                                            {{ account.accountName }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <h3 class=" text-muted mt-5 fs-6">Corporate Login</h3>
                                <br />
                                <div class="alert alert-solid-danger alert-dismissible fade show" v-if="error"> {{ error }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                                            class="bi bi-x"></i></button>
                                </div>

                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 form-floating mb-3"
                                    v-if="selectedBank.username">

                                    <input type="text" class="form-control" v-model="formData.loginId"
                                        :placeholder=selectedBank.username>
                                    <label for="floatingInput" class=" text-muted">{{ selectedBank.username
                                    }}</label>
                                    <span class="error_msg" role="alert" v-if="errors.loginId">{{
                                        errors.loginId }}</span>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 form-floating mb-3"
                                    v-if="selectedBank.password">
                                    <input type="password" class="form-control" id="floatingInput"
                                        v-model="formData.password" :placeholder=selectedBank.password>
                                    <label for="floatingInput" class=" text-muted">{{ selectedBank.password
                                    }}</label>
                                    <span class="error_msg" role="alert" v-if="errors.password">{{
                                        errors.password }}</span>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 form-floating mb-3"
                                    v-if="selectedBank.secret">

                                    <input type="password" class="form-control" id="floatingInput" v-model="formData.secret"
                                        :placeholder=selectedBank.secret>
                                    <label for="floatingInput" class=" text-muted">{{ selectedBank.secret }}</label>
                                </div>
                                <div class="row gy-4 mt-3 mx-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="privacy-policy"
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
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="button" class="btn btn-outline-secondary btn-full-width "
                                        @click="backButtontobank">
                                        Cancel
                                    </button>
                                    <button class="btn btn-secondary btn-loader" v-if="loadingbank"> <span
                                            class="me-2">Loading</span> <span class="loading"><i
                                                class="ri-loader-2-fill fs-16"></i></span> </button>
                                    <button type="button" class="btn btn-secondary btn-full-width " @click="verifyBank()"
                                        v-else>
                                        Verify
                                    </button>

                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
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
                agreed_direct_debit_from_monoova: '',
                app_id: sessionStorage.getItem('app_id'),
                mobile: sessionStorage.getItem('mobile'),
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
            this.errors.bank = null;
            //console.log(bank);
            this.formData.bank = bank.id;
            this.formData.institution = bank.basiq_code;
            this.selectedBank = bank;
            this.list = false;
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

    }
}

</script>