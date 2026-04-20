<template>
    <form>
        <!--Error Message Start-->
        <div class="alert alert-solid-danger alert-dismissible fs-15 fade show mb-4" v-if="errors.question">
            {{ errors.question }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                    class="bi bi-x"></i></button>
        </div>
        <!--Error Message End-->
        <div class="row gy-4 ">
            <h6 class="text-muted">Please provide a personal reference</h6>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 form-floating">

                <input type="text" class="form-control" id="input" placeholder="Person Name"
                    v-model="formData.ref_person_name">
                <label for="floatingInput" class="text-muted">Person
                    Name</label>
                <span class="error_msg" role="alert" v-if="errors.ref_person_name">{{
                    errors.ref_person_name }}</span>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 form-floating">

                <input type="tel" maxlength="10" class="form-control" id="floatingInput" placeholder="04XXXXXXXX"
                    v-model="formData.ref_contract_number"  @input="onlyNumbers">
                <label for="floatingInput" class="text-muted">Contact
                    Number</label>
                <span class="error_msg" role="alert" v-if="errors.ref_contract_number">{{
                    errors.ref_contract_number }}</span>
            </div>
        </div>
        <div class="row gy-4 mt-3">
            <div class="col-sm-12">
                <div class="card custom-card overflow-hidden">
                    <div class="card-body p-0">
                        <div
                            class="p-3 terms-heading-cover d-flex align-items-center text-fixed-white bg-primary h5 fw-semibold mb-0">
                            CashFaster - {{ terms.name }}
                            <a href="javascript:void(0);" data-bs-toggle="card-fullscreen" class="ms-auto text-fixed-white">
                                <i class="ri-fullscreen-line"></i>
                            </a>
                        </div>
                        <div class="p-4 text-muted terms-conditions" id="terms-scroll" style="overflow: scroll;"
                            v-html="terms.terms">

                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row gy-4 mt-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="privacy-policy"
                    v-model="formData.agreed_terms">
                <label class="form-check-label text-muted" for="privacy-policy">
                    I agree to the Terms and Conditions listed above
                </label>
            </div>
            <div class="form-check d-block">
                <input class="form-check-input" type="checkbox" value="" id="terms_conditions"
                    v-model="formData.agreed_privacy_policy">
                <label class="form-check-label text-muted" for="terms_conditions">
                    I agree and consent to the
                    <a class="fw-semibold text-muted" data-bs-toggle="modal" href="#terms"
                        @click="findPolicyBySlug('privacy-policy')"><u>Privacy Policy, </u></a>
                    <a class="fw-semibold text-muted" data-bs-toggle="modal" href="#terms"
                        @click="findPolicyBySlug('refund-policy')"><u>Refund Policy, </u></a>
                    <a class="fw-semibold text-muted" data-bs-toggle="modal" href="#terms"
                        @click="findPolicyBySlug('terms-and-conditions')"><u>Terms and Conditions </u></a>
                </label>
            </div>
            <div class="form-check d-block">
                <input class="form-check-input" type="checkbox" value="" id="terms_conditions"
                    v-model="formData.agreed_credit_guide">
                <label class="form-check-label text-muted" for="terms_conditions">
                    I have read and understand the
                    <a class="fw-semibold text-muted" data-bs-toggle="modal" href="#terms"
                        @click="findPolicyBySlug('credit-guide')"><u>Credit Guide </u></a>
                </label>
            </div>
        </div>
        <Terms :val="policy"></Terms>
    </form>
</template>
<script>
import Terms from '../policies/TermsComponent.vue';

export default {
    components: {
        Terms
    },
    props: ['val'],
    data() {
        return {
            baseUrl: import.meta.env.VITE_APP_URL,
            terms: [],
            policy: [],
            errors: [],
            formData: {
                ref_person_name: '',
                ref_contract_number: '',
                agreed_terms: '',
                agreed_privacy_policy: '',
                agreed_credit_guide: '',
            }
        };
    },
    mounted() {
        this.getPolicy();
    },
    methods: {
        validateForm() {
            this.errors = [];
            let anyFieldEmpty = false;

            if (!this.formData.ref_person_name) {
                this.errors.ref_person_name = 'Please enter person name';
            }

            // Check if ref_contract_number is empty or not a valid Australian mobile number
            if (!this.formData.ref_contract_number) {
                this.errors.ref_contract_number = 'Please enter contract number';
            } else if (!this.isValidMobileNumber(this.formData.ref_contract_number)) {
                this.errors.ref_contract_number = 'Invalid Australian mobile number';
            }

            for (const key in this.formData) {
                if (this.formData.hasOwnProperty(key)) {
                    const value = this.formData[key];
                    if (value === '' || value === null) {
                        anyFieldEmpty = true;
                        break; // Exit the loop early since we found an empty field
                    }
                }
            }

            // Check the anyFieldEmpty flag to see if any field is empty
            if (anyFieldEmpty) {
                this.errors.question = 'Please read carefully and check  all the termsa and contitions.';
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
        getPolicy() {
            axios.defaults.headers.common['Authorization'] = `Bearer ${sessionStorage.getItem('token')}`;
            axios.get(this.baseUrl + 'api/auth/loan-term')
                .then(response => {
                    this.terms = response.data.data;
                })
                .catch(error => {
                    this.error = error.response.data.message;
                });
        },
        findPolicyBySlug(slug) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${sessionStorage.getItem('token')}`;
            axios.get(this.baseUrl + 'api/auth/loan-term/' + slug)
                .then(response => {
                    this.policy = response.data.data;
                })
                .catch(error => {
                    this.error = error.response.data.message;
                });
        },
        isValidMobileNumber(mobileNumber) {
            const regex = /^(?:\+?61|0)[2-478](?:[ -]?[0-9]){8}$/;
            return regex.test(mobileNumber);
        },
       onlyNumbers() {
    this.formData.ref_contract_number =
        this.formData.ref_contract_number.replace(/\D/g, '').slice(0, 10);
}
    }
}
</script>