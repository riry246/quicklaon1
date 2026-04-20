<template>
    <div class="container-lg contract">
        <!-- Start::row-1 -->
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <div class="card custom-card overflow-hidden" ref="cardElement">
                    <div class="card-body p-0">
                        <div
                            class="p-3 terms-heading-cover d-flex align-items-center text-fixed-white bg-primary h5 fw-semibold mb-0">
                            <span class="avatar avatar-md me-3">
                                <img src="https://laravelui.spruko.com/ynex/build/assets/images/media/media-67.png" alt="">
                            </span> CashFasterAUCredit#{{ val.contract.contract_id }}
                            <a href="javascript:void(0);" @click="toggleFullscreen" class="ms-auto text-fixed-white">
                                <i class="ri-fullscreen-line"></i>
                            </a>
                        </div>
                        <div class="p-5 text-muted terms-conditions" id="terms-scroll">
                            <div v-html="val.contract.document"></div>
                        </div>
                    </div>
                    <div class="card-footer d-sm-flex d-block align-items-center justify-content-between shadow-lg">
                        <div>

                        </div>
                        <div class="btn-list mt-sm-0 mt-2">
                            <button class="btn btn-primary btn-wave" data-bs-effect="effect-super-scaled"
                                data-bs-toggle="modal" href="#signature">Signature</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--End::row-1 -->
        <div class="modal fade" id="signature">
            <div class="modal-dialog modal-dialog-centered text-center" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title text-center">Signature</h6>
                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-start front-slider">
                        <div class="card custom-card">
                            <div class="card-body">
                                <ul class="nav nav-tabs tab-style-1 d-sm-flex d-block nav-justified" role="tablist">
                                    <li class="nav-item me-sm-2 me-0">
                                        <a class="nav-link active" data-bs-toggle="tab" data-bs-target="#draw"
                                            aria-current="page" href="#draw" @click="changeType('draw')">Draw</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#type" href="#type"
                                            @click="changeType('text')">Type</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="alert alert-solid-success alert-dismissible fade show" v-if="success">
                                        {{ success }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"><i class="bi bi-x"></i></button>
                                    </div>
                                    <div class="tab-pane border-0 active p-0" id="draw" role="tabpanel"
                                        aria-labelledby="sms">
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 text-center"> <label
                                                for="input-tel" class="form-label">Draw</label>
                                            <vueSignature ref="signature" :sigOption="option" :w="'430px'" :h="'200px'"
                                                id="vue-signature">
                                            </vueSignature>
                                            <span class="error_msg" role="alert" v-if="error">{{ error }}</span>
                                            <br />
                                            <div class="btn-group " role="group" aria-label="Basic example">
                                                <button class="btn  btn-light btn-wave btn-outline-info" @click="save">
                                                    <i class="ri-save-fill"></i> Save
                                                </button>
                                                <button class="btn  btn-light btn-wave btn-outline-info" @click="clear">
                                                    <i class="ri-delete-bin-7-fill"></i> Delete
                                                </button>
                                                <button class="btn btn-light btn-wave btn-outline-info" @click="undo">
                                                    <i class="ri-arrow-go-back-line"></i> Undo
                                                </button>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="tab-pane border-0 p-0" id="type" role="tabpanel" aria-labelledby="email">
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                            <label for="input-tel" class="form-label">Type</label>
                                            <input type="email" class="form-control signature"
                                                v-model="formdata.signature_customer">
                                            <span class="error_msg" role="alert" v-if="error">{{ error }}</span>
                                        </div>

                                    </div>
                                    <p class="text-muted mt-3">
                                        I acknowledge that Cashfaster utilizes my name and email for the signature process,
                                        enhancing user experience. For details on information usage, refer to our Privacy
                                        Policy. By electronically signing, I affirm its validity equivalent to handwritten
                                        signatures, as permitted by applicable law.
                                    </p>
                                    <div class="d-grid mt-4 pt-2">
                                        <button type="button" class="btn btn-lg btn-secondary label-btn label-end"
                                            v-if="processing"><span class="spinner-border spinner-border-sm align-middle"
                                                role="status" aria-hidden="true"></span>
                                            Processing...</button>
                                        <button type="button" class="btn btn-secondary btn-wave btn-lg" v-else
                                            @click="submitSignature()">Accept & Sign</button>
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
import vueSignature from "vue-signature";

export default {
    props: ['val'],
    components: {
        vueSignature,
    },
    data() {
        return {
            option: {
                penColor: "rgb(0, 0, 0)",
                backgroundColor: "rgb(255,255,255)",
            },
            formdata: {
                type: 'draw',
                ref_code: '',
                signature_customer: null,
            },
            disabled: false,
            baseUrl: import.meta.env.VITE_APP_URL,
            error: '',
            success: '',
            processing: false,
            appid: '',
            token: '',
        };
    },
    mounted() {
        this.token = sessionStorage.getItem('token');
        this.viewed();
    },
    methods: {
        toggleFullscreen() {
            let card = this.$refs.cardElement;
            card.classList.toggle("card-fullscreen");
            card.classList.remove("card-collapsed");
        },
        changeType(type) {
            this.formdata.type = type;
            this.formdata.signature_customer = null;
            this.clear();
        },
        submitSignature() {
            this.processing = true;
            this.success = null;
            this.error = null;

            this.formdata.ref_code = this.val.contract.ref_code;

            if (this.formdata.signature_customer == null) {
                this.error = 'Please save the signature before submitting contract';
                this.processing = false;
            } else {
                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;

                axios.post(this.baseUrl + 'api/auth/contract', { data: this.formdata })
                    .then(response => {
                        this.success = response.data.message;
                    })
                    .catch(error => {
                        if (error.response) {
                            this.error = `Error: ${error.response.status} - ${error.response.data.message}`;
                        } else if (error.request) {
                            this.error = 'No response received from the server.';
                        } else {
                            this.error = 'Error setting up the request.';
                        }
                    })
                    .finally(() => {
                        this.processing = false;
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    });

            }

        },
        viewed() {
            this.formdata.ref_code = this.val.contract.ref_code;
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;

            axios.post(this.baseUrl + 'api/auth/contract/viewed', { data: this.formdata })
                .then(response => {

                })
                .catch(error => {
                    if (error.response) {
                        this.error = `Error: ${error.response.status} - ${error.response.data.message}`;
                    } else if (error.request) {
                        this.error = 'No response received from the server.';
                    } else {
                        this.error = 'Error setting up the request.';
                    }
                })
                .finally(() => {

                });
        },
        save() {
            var _this = this;
            var png = _this.$refs.signature.save();
            var jpeg = _this.$refs.signature.save("image/jpeg");
            var svg = _this.$refs.signature.save("image/svg+xml");
            this.formdata.signature_customer = svg;
        },
        clear() {
            var _this = this;
            _this.$refs.signature.clear();
        },
        undo() {
            var _this = this;
            _this.$refs.signature.undo();
        },
    }
};

</script>
