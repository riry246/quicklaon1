<template>
    <form>
        <div class="row gy-4 button-set-block" v-if="loadingbank">
            <h6 class="text-muted">Select your bank for verification</h6>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 ">
                <div class="card custom-card noshadow border">
                    <iframe :src="iframe" class="illion_iframe" v-if="iframe" />
                    <div v-else>
                        <div class="d-flex justify-content-center my-5">
                            <div class="spinner-border" role="status"> <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <p class="d-flex justify-content-center my-5 fs-16 text-muted">Checking Bank information. Please
                            wait...</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gy-4 button-set-block" v-else>
            <h6 class="text-muted">Select your primary account</h6>
            <div class="alert alert-solid-danger alert-dismissible fade show" v-if="errors.bank"> {{ errors.bank }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                        class="bi bi-x"></i></button>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 ">
                <div class="card custom-card noshadow border">
                    <div v-if="selectaccountsection">
                        <div class="row gy-4 my-3 mx-3 reason-block button-set-block">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 reason my-0"
                                v-for="account in account_list" :key="index" @click="selectAccount(account)">
                                <a class="btn btn-outline-secondary btn-secondary-light full-width-button label-btn p-3 eclipse "
                                    :class="{ active: account.accountNumber === formData.accountNo }">

                                    {{ account.accountName }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <div class="d-flex justify-content-center my-5">
                            <div class="spinner-border" role="status"> <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <p class="d-flex justify-content-center my-5 fs-16 text-muted">Checking Bank information. Please
                            wait it may take a minute or more.
                            wait...</p>
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
                app_id: sessionStorage.getItem('app_id'),
                mobile: sessionStorage.getItem('mobile'),
                accountNo: '',
            },
            iframe: null,
            loadingbank: true,
            account_list: [],
            errors: [],
            selectaccountsection: false,
        };
    },
    computed: {
        imageBaseUrl() {
            return '/assets/';
        },
    },
    mounted() {

        let queryString = window.location.search;
        let urlParams = new URLSearchParams(queryString);
        this.referrerCode = urlParams.get('referrerCode');
        this.bankStatus = urlParams.get('status');

        if (this.referrerCode && this.bankStatus == 'COMPLETE') {
            this.loadingbank = false;
            console.log(this.loadingbank);
            this.sendDataToParent() 
            //this.getAccount();
        } else {
            this.getBank();
        }

    },
    methods: {
        validateForm() {
            this.errors = [];
            if (!this.formData.accountNo) {
                this.errors.bank = 'Please select account';
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
        getBank() {
            axios.defaults.headers.common['Authorization'] = `Bearer ${sessionStorage.getItem('token')}`;
            axios.post(this.baseUrl + 'api/auth/iframe', { app_id: this.formData.app_id })
                .then(response => {
                    this.iframe = import.meta.env.VITE_ILLION_BASE_URL + response.data.url;
                    console.log(this.iframe);
                })
                .catch(error => {
                    this.error = error.response.data.message;
                });
        },
        getAccount() {

            axios.defaults.headers.common['Authorization'] = `Bearer ${sessionStorage.getItem('token')}`;
            axios.post(this.baseUrl + 'api/auth/getillionAccount', { user_id: sessionStorage.getItem('user_id'), app_id: this.formData.app_id })
                .then(response => {
                    this.account_list = response.data.accountList;

                    if (this.account_list.length > 0) {
                        this.selectaccountsection = true;
                    }
                    console.log(this.account_list);
                })
                .catch(error => {

                });
        },
        selectAccount(account) {
            this.formData.accountNo = account.accountNumber;

        },
    }
}
</script>