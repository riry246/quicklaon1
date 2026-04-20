<template>
    <div>
        <form>
            <span class="error_msg" role="alert" v-if="errors.reasonForLoan">{{ errors.reasonForLoan }}</span>
            <div class="row gy-4 reason-block button-set-block">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 reason" v-for="reason in reasons" :key="reason"
                    @click="selectReason(reason)">
                    <a class="btn btn-outline-secondary btn-secondary-light full-width-button label-btn p-3 "
                        :class="{ active: reason.name === formData.reasonForLoan }">
                        <img :src="getImagePath(reason.image)" v-if="reason.image"/>
                        <img src="/assets/images/reason/8.png" v-else/>
                        {{ reason.name }}
                    </a>
                </div>
                <input type="hidden" id="reason" v-model="formData.reasonForLoan" />
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 reason">
                    <a class="btn btn-outline-secondary btn-secondary-light full-width-button label-btn p-3"
                        data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#modaldemo2">
                        <img src="/assets/images/reason/8.png" />
                        Others
                    </a>
                    <div class="modal fade" id="modaldemo2">
                        <div class="modal-dialog modal-dialog-centered text-center" role="document">
                            <div class="modal-content modal-content-demo">
                                <div class="modal-header">
                                    <h6 class="modal-title text-center">Reason
                                    </h6>
                                    <a aria-label="Close" class="btn-close" data-bs-dismiss="modal"></a>
                                </div>
                                <div class="modal-body text-start front-slider">
                                    <p class="mb-4 text-muted op-7 fw-normal">
                                        Please enter your reason for loan</p>

                                    <div class="row">
                                        <div class="col-xl-12 mb-4 ">
                                            <div class="row">
                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                                    <label for="input-label" class="form-label text-muted">Reason</label>
                                                    <textarea class="form-control" id="input" placeholder="Reason"
                                                        v-model="other_reason"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a class="btn btn-secondary" @click="addReason()" data-bs-dismiss="modal">Add
                                        Reason</a>
                                    <a class="btn btn-danger" data-bs-dismiss="modal">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="reason" id="reason" value="" />

        </form>
    </div>
</template>
<script>

export default {
    props: ['val'],
    data() {
        return {
            baseUrl: import.meta.env.VITE_APP_URL,
            formData: {
                reasonForLoan: '',
            },
            other_reason: '',
            reasons: '',
            errors: []
        };
    },
    computed: {
        imageBaseUrl() {
            return '/storage/images/';
        },
    },
    mounted() {
        this.getReasons();


    },
    methods: {
        validateForm() {
            this.errors = [];
            if (!this.formData.reasonForLoan) {
                this.errors.reasonForLoan = 'Please select on loan detail';
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

        getReasons() {
            axios.defaults.headers.common['Authorization'] = `Bearer ${sessionStorage.getItem('token')}`;
            axios.get(this.baseUrl + 'api/auth/loan-reason')
                .then(response => {
                    this.reasons = response.data.data;
                })
                .catch(error => {
                    this.error = error.response.data.message;
                });
        },
        getImagePath(imageFilename) {
            return this.imageBaseUrl + imageFilename;
        },
        selectReason(reason) {
            this.formData.reasonForLoan = reason.name;
        },
        addReason() {
            this.reasons.push({ name: this.other_reason });
        }

    }
}
</script>