<template>
    <div class="modal fade" id="idVerification" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">ID Verification</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-4">
                    <div class="col-xl-12">

                        <nav class="nav nav-style-1 nav-pills mb-3 nav-justified d-sm-flex d-block" role="tablist">
                            <a class="nav-link active" data-bs-toggle="tab" role="tab" aria-current="page"
                                href="#passport" aria-selected="true">Passport</a>
                            <!--<a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page" href="#centerlink"
                                aria-selected="true">Centrelink</a>
                            <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page" href="#citizenship"
                                aria-selected="true">Citizenship</a>-->
                            <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page"
                                href="#driverLicence" aria-selected="true">Driving Licence</a>
                            <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page" href="#medicare"
                                aria-selected="true">Medicare</a>
                        </nav>
                        <div class="tab-content">
                            <div class="alert alert-solid-danger alert-dismissible fs-15 fade show mb-4" v-if="error">
                                {{ error }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                                        class="bi bi-x"></i></button>
                            </div>

                            <div class="tab-pane text-muted active show" id="passport" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <p class="mb-2 text-muted">Birth Date:</p>
                                        <input type="date" class="form-control" v-model="passport.BirthDate">
                                        <span class="error_msg" role="alert" v-if="errors.BirthDate">{{ errors.BirthDate
                                            }}</span>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                        <p class="mb-2 text-muted">Given Name:</p>
                                        <input type="text" class="form-control" v-model="passport.GivenName">
                                        <span class="error_msg" role="alert" v-if="errors.GivenName">{{ errors.GivenName
                                            }}</span>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                        <p class="mb-2 text-muted">Family Name:</p>
                                        <input type="text" class="form-control" v-model="passport.FamilyName">
                                        <span class="error_msg" role="alert" v-if="errors.FamilyName">{{
                                errors.FamilyName
                            }}</span>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                        <p class="mb-2 text-muted">Passport Number:</p>
                                        <input type="text" class="form-control" v-model="passport.TravelDocumentNumber">
                                        <span class="error_msg" role="alert" v-if="errors.TravelDocumentNumber">{{
                                errors.TravelDocumentNumber }}</span>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                        <p class="mb-2 text-muted">Gender:</p>
                                        <select class="form-control" v-model="passport.Gender">
                                            <option value="m">Male</option>
                                            <option value="f">Female</option>
                                            <option value="x">Other</option>
                                            <option value="">Prefer not to say</option>
                                        </select>
                                        <span class="error_msg" role="alert" v-if="errors.Gender">{{ errors.Gender
                                            }}</span>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                        <p class="mb-2 text-muted">ExpiryDate:</p>
                                        <input type="date" class="form-control" v-model="passport.ExpiryDate">
                                        <span class="error_msg" role="alert" v-if="errors.ExpiryDate">{{
                                errors.ExpiryDate
                            }}</span>
                                    </div>
                                    <button class="btn btn-primary btn-loader mt-3" v-if="loading"> <span
                                            class="me-2">Loading</span> <span class="loading"><i
                                                class="ri-loader-2-fill fs-16"></i></span> </button>
                                    <button v-else class="btn btn-primary btn-wave mt-3"
                                        @click="verifyPassport()">Verify
                                        Passport</button>
                                </div>
                            </div>
                            <div class="tab-pane text-muted  " id="centerlink" role="tabpanel">
                                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                                    <p class="mb-2 text-muted">Birth Date:</p>
                                    <input type="date" class="form-control" v-model="centerlink.BirthDate">
                                    <span class="error_msg" role="alert" v-if="errors.BirthDate">{{ errors.BirthDate
                                        }}</span>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                    <p class="mb-2 text-muted">Name:</p>
                                    <input type="text" class="form-control" v-model="centerlink.Name">
                                    <span class="error_msg" role="alert" v-if="errors.Name">{{ errors.Name }}</span>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                    <p class="mb-2 text-muted">Card Type:</p>
                                    <select class="form-control" v-model="centerlink.CardType">
                                        <option value="PCC">PCC (Pension Concession),</option>
                                        <option value="HCC">HCC (Health Care)</option>
                                        <option value="SHC">SHC (Seniors Health)</option>
                                    </select>
                                    <span class="error_msg" role="alert" v-if="errors.CardType">{{ errors.CardType
                                        }}</span>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                    <p class="mb-2 text-muted">ExpiryDate:</p>
                                    <input type="date" class="form-control" v-model="centerlink.CardExpiry">
                                    <span class="error_msg" role="alert" v-if="errors.CardExpiry">{{ errors.CardExpiry
                                        }}</span>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                    <p class="mb-2 text-muted">CustomerReferenceNumber:</p>
                                    <input type="text" class="form-control"
                                        v-model="centerlink.CustomerReferenceNumber">
                                    <span class="error_msg" role="alert" v-if="errors.CustomerReferenceNumber">{{
                                errors.CustomerReferenceNumber }}</span>
                                </div>


                                <button class="btn btn-primary btn-wave mt-3" @click="verifyCenterlink()">Verify
                                    Centerlink</button>
                            </div>
                            <div class="tab-pane text-muted " id="citizenship" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <p class="mb-2 text-muted">Birth Date:</p>
                                        <input type="date" class="form-control" v-model="citizenship.BirthDate">
                                        <span class="error_msg" role="alert" v-if="errors.BirthDate">{{ errors.BirthDate
                                            }}</span>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                        <p class="mb-2 text-muted">Give Name:</p>
                                        <input type="text" class="form-control" v-model="citizenship.GivenName">
                                        <span class="error_msg" role="alert" v-if="errors.GivenName">{{ errors.GivenName
                                            }}</span>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                        <p class="mb-2 text-muted">Middle Name:</p>
                                        <input type="text" class="form-control" v-model="citizenship.MiddleName">
                                        <span class="error_msg" role="alert" v-if="errors.MiddleName">{{
                                errors.MiddleName
                            }}</span>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                        <p class="mb-2 text-muted">Family Name:</p>
                                        <input type="text" class="form-control" v-model="citizenship.FamilyName">
                                        <span class="error_msg" role="alert" v-if="errors.FamilyName">{{
                                errors.FamilyName
                            }}</span>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                        <p class="mb-2 text-muted">Stock Number:</p>
                                        <input type="text" class="form-control" v-model="citizenship.StockNumber">
                                        <span class="error_msg" role="alert" v-if="errors.StockNumber">{{
                                errors.StockNumber
                            }}</span>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                        <p class="mb-2 text-muted">Acquisition Date:</p>
                                        <input type="date" class="form-control" v-model="citizenship.AcquisitionDate">
                                        <span class="error_msg" role="alert" v-if="errors.AcquisitionDate">{{
                                errors.AcquisitionDate
                            }}</span>
                                    </div>

                                    <button class="btn btn-primary btn-loader mt-3" v-if="loading"> <span
                                            class="me-2">Loading</span> <span class="loading"><i
                                                class="ri-loader-2-fill fs-16"></i></span> </button>

                                    <button v-else class="btn btn-primary btn-wave mt-3"
                                        @click="verifyCitizenship()">Verify
                                        Citizenship</button>
                                </div>
                            </div>

                            <div class="tab-pane text-muted " id="driverLicence" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <p class="mb-2 text-muted">Birth Date: </p>
                                        <input type="date" class="form-control" v-model="driverLicence.BirthDate">
                                        <span class="error_msg" role="alert" v-if="errors.BirthDate">{{ errors.BirthDate
                                            }}</span>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                        <p class="mb-2 text-muted">Give Name:</p>
                                        <input type="text" class="form-control" v-model="driverLicence.GivenName">
                                        <span class="error_msg" role="alert" v-if="errors.GivenName">{{ errors.GivenName
                                            }}</span>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                        <p class="mb-2 text-muted">Middle Name:</p>
                                        <input type="text" class="form-control" v-model="driverLicence.MiddleName">
                                        <span class="error_msg" role="alert" v-if="errors.MiddleName">{{
                                errors.MiddleName
                            }}</span>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                        <p class="mb-2 text-muted">Family Name:</p>
                                        <input type="text" class="form-control" v-model="driverLicence.FamilyName">
                                        <span class="error_msg" role="alert" v-if="errors.FamilyName">{{
                                errors.FamilyName
                            }}</span>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                        <p class="mb-2 text-muted">Licence Number:</p>
                                        <input type="text" class="form-control" v-model="driverLicence.LicenceNumber">
                                        <span class="error_msg" role="alert" v-if="errors.LicenceNumber">{{
                                errors.LicenceNumber
                            }}</span>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                        <p class="mb-2 text-muted">State Of Issue:</p>
                                        <select class="form-control" v-model="driverLicence.StateOfIssue">
                                            <option value="NSW">NSW</option>
                                            <option value="ACT">ACT</option>
                                            <option value="SA">SA</option>
                                            <option value="WA">WA</option>
                                            <option value="TAS">TAS</option>
                                            <option value="NT">NT</option>
                                            <option value="QLD">QLD</option>
                                            <option value="VIC">VIC</option>

                                        </select>
                                        <span class="error_msg" role="alert" v-if="errors.StateOfIssue">{{
                                errors.StateOfIssue
                            }}</span>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                        <p class="mb-2 text-muted">Card Number:</p>
                                        <input type="text" class="form-control" v-model="driverLicence.CardNumber">
                                        <span class="error_msg" role="alert" v-if="errors.CardNumber">{{
                                errors.CardNumber
                            }}</span>
                                    </div>


                                    <button class="btn btn-primary btn-loader mt-3" v-if="loading"> <span
                                            class="me-2">Loading</span> <span class="loading"><i
                                                class="ri-loader-2-fill fs-16"></i></span> </button>
                                    <button v-else class="btn btn-primary btn-wave mt-3"
                                        @click="verifyDriverLicence()">Verify
                                        Driver Licence</button>
                                </div>
                            </div>
                            <div class="tab-pane text-muted" id="medicare" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 mt-2">
                                        <p class="mb-2 text-muted">Full Name:</p>
                                        <input type="text" class="form-control" v-model="medicare.FullName1">
                                        <span class="error_msg" role="alert" v-if="errors.FullName1">{{ errors.FullName1
                                            }}</span>

                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                        <input type="button" class="form-control btn-secondary mt-4" id="input-new"
                                            value="Add Full Name" @click="addFullName">
                                    </div>
                                    <div v-for="(name, index) in additionalFullNames" :key="index" class="row mt-2">
                                        <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
                                            <p class="mb-2 text-muted">Full Name {{ index + 2 }}:</p>
                                            <input type="text" class="form-control" v-model="additionalFullNames[index]"
                                                @input="updateMedicare(index)">
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                            <input type="button" class="form-control btn-danger mt-4" value="Remove"
                                                @click="removeFullName(index)">
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-2">
                                        <p class="mb-2 text-muted">Birth Date:</p>
                                        <input type="date" class="form-control" v-model="medicare.BirthDate">
                                        <span class="error_msg" role="alert" v-if="errors.BirthDate">{{ errors.BirthDate
                                            }}</span>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                        <p class="mb-2 text-muted">Card Number:</p>
                                        <input type="text" class="form-control" v-model="medicare.CardNumber">
                                        <span class="error_msg" role="alert" v-if="errors.CardNumber">{{
                                errors.CardNumber }}</span>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                        <p class="mb-2 text-muted">Card Type:</p>
                                        <select class="form-control" v-model="medicare.CardType">
                                            <option value="Y">Yellow</option>
                                            <option value="G">Green</option>
                                            <option value="B">Blue</option>
                                        </select>
                                        <span class="error_msg" role="alert" v-if="errors.CardType">{{ errors.CardType
                                            }}</span>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                        <p class="mb-2 text-muted">ExpiryDate:</p>
                                        <input type="date" class="form-control" v-model="medicare.CardExpiry">
                                        <span class="error_msg" role="alert" v-if="errors.CardExpiry">{{
                                            errors.CardExpiry
                                            }}</span>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-2">
                                        <p class="mb-2 text-muted">Individual Reference Number:</p>
                                        <input type="text" class="form-control"
                                            v-model="medicare.IndividualReferenceNumber">
                                        <span class="error_msg" role="alert" v-if="errors.IndividualReferenceNumber">{{
                                            errors.IndividualReferenceNumber
                                            }}</span>

                                    </div>
                                    <button class="btn btn-primary btn-loader mt-3" v-if="loading"> <span
                                            class="me-2">Loading</span> <span class="loading"><i
                                                class="ri-loader-2-fill fs-16"></i></span> </button>
                                    <button v-else class="btn btn-primary btn-wave mt-3"
                                        @click="verifyMedicare()">Verify
                                        Medicare</button>
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
            user_id: sessionStorage.getItem('user_id'),
            passport: {
                type: 'passport',
                BirthDate: '',
                GivenName: '',
                FamilyName: '',
                TravelDocumentNumber: '',
                Gender: '',
                ExpiryDate: '',
            },
            centerlink: {
                type: 'centerlink',
                BirthDate: '',
                Name: '',
                CardType: '',
                CardExpiry: '',
                CustomerReferenceNumber: '',
            },
            citizenship: {
                type: 'citizenship',
                BirthDate: '',
                GivenName: '',
                MiddleName: '',
                FamilyName: '',
                StockNumber: '',
                AcquisitionDate: '',
            },
            driverLicence: {
                type: 'driverLicence',
                BirthDate: '',
                GivenName: '',
                MiddleName: '',
                FamilyName: '',
                LicenceNumber: '',
                StateOfIssue: '',
                CardNumber: '',
            },
            medicare: {
                type: 'medicare',
                BirthDate: '',
                FullName1: '',
                additionalFullNames: [],
                CardExpiry: '',
                CardNumber: '',
                CardType: '',
                IndividualReferenceNumber: '',
            },
            errors: [],
            error: '',
            msg: '',
            loading: '',
            additionalFullNames: [],
        };
    },
    methods: {
        verifyPassport() {
            this.errors = [];
            if (!this.passport.BirthDate) {
                this.errors.BirthDate = 'This field is required';
            }
            if (!this.passport.GivenName) {
                this.errors.GivenName = 'This field is required';
            }
            if (!this.passport.FamilyName) {
                this.errors.FamilyName = 'This field is required';
            }
            if (!this.passport.TravelDocumentNumber) {
                this.errors.TravelDocumentNumber = 'This field is required';
            }
            if (!this.passport.ExpiryDate) {
                this.errors.ExpiryDate = 'This field is required';
            }

            const isValid = Object.keys(this.errors).length === 0;

            if (isValid) {
                this.submitForm(this.passport);
            } else {
                //this.submitForm(this.passport);
                console.log('Form is invalid. Please correct the errors.');
            }
        },
        verifyCenterlink() {
            this.errors = [];
            if (!this.centerlink.BirthDate) {
                this.errors.BirthDate = 'This field is required';
            }
            if (!this.centerlink.Name) {
                this.errors.Name = 'This field is required';
            }
            if (!this.centerlink.CardType) {
                this.errors.CardType = 'This field is required';
            }
            if (!this.centerlink.CardExpiry) {
                this.errors.CardExpiry = 'This field is required';
            }
            if (!this.centerlink.CustomerReferenceNumber) {
                this.errors.CustomerReferenceNumber = 'This field is required';
            }

            const isValid = Object.keys(this.errors).length === 0;

            if (isValid) {
                this.submitForm(this.centerlink);
            } else {
                console.log('Form is invalid. Please correct the errors.');
            }
        },
        verifyCitizenship() {
            this.errors = [];
            if (!this.citizenship.BirthDate) {
                this.errors.BirthDate = 'This field is required';
            }
            if (!this.citizenship.GivenName) {
                this.errors.GivenName = 'This field is required';
            }
            if (!this.citizenship.FamilyName) {
                this.errors.FamilyName = 'This field is required';
            }
            if (!this.citizenship.StockNumber) {
                this.errors.StockNumber = 'This field is required';
            }
            if (!this.citizenship.AcquisitionDate) {
                this.errors.AcquisitionDate = 'This field is required';
            }

            const isValid = Object.keys(this.errors).length === 0;

            if (isValid) {
                this.submitForm(this.centerlink);
            } else {
                console.log('Form is invalid. Please correct the errors.');
            }
        },
        verifyDriverLicence() {
            this.errors = [];
            if (!this.driverLicence.BirthDate) {
                this.errors.BirthDate = 'This field is required';
            }
            if (!this.driverLicence.GivenName) {
                this.errors.GivenName = 'This field is required';
            }
            if (!this.driverLicence.FamilyName) {
                this.errors.FamilyName = 'This field is required';
            }
            if (!this.driverLicence.LicenceNumber) {
                this.errors.LicenceNumber = 'This field is required';
            }
            if (!this.driverLicence.StateOfIssue) {
                this.errors.StateOfIssue = 'This field is required';
            }
            if (!this.driverLicence.CardNumber) {
                this.errors.CardNumber = 'This field is required';
            }

            const isValid = Object.keys(this.errors).length === 0;

            if (isValid) {
                this.submitForm(this.driverLicence);
            } else {
                console.log('Form is invalid. Please correct the errors.');
            }
        },
        verifyMedicare() {
            this.errors = [];
            if (!this.medicare.BirthDate) {
                this.errors.BirthDate = 'This field is required';
            }
            if (!this.medicare.FullName1) {
                this.errors.FullName1 = 'This field is required';
            }
            if (!this.medicare.CardExpiry) {
                this.errors.CardExpiry = 'This field is required';
            }
            if (!this.medicare.CardNumber) {
                this.errors.CardNumber = 'This field is required';
            }
            if (!this.medicare.CardType) {
                this.errors.CardType = 'This field is required';
            }
            if (!this.medicare.IndividualReferenceNumber) {
                this.errors.IndividualReferenceNumber = 'This field is required';
            }


            const isValid = Object.keys(this.errors).length === 0;

            if (isValid) {
                this.submitForm(this.medicare);
            } else {
                console.log('Form is invalid. Please correct the errors.');
            }
        },

        submitForm(value) {
            this.loading = true;
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
            axios.post(this.baseUrl + 'api/auth/idVerify', { user_id: this.user_id, value })
                .then(response => {
                    this.error = '';
                    this.msg = response.data.success;
                    console.log(response);
                    if (!this.msg) {
                        this.error = response.data.error_message;
                    } else {
                        location.reload();
                    }
                })
                .catch(error => {
                    this.error = error;
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        addFullName() {
            this.additionalFullNames.push(''); // Add a new empty input field
        },
        removeFullName(index) {
            this.additionalFullNames.splice(index, 1); // Remove input field at specified index
            this.medicare.additionalFullNames.splice(index, 1); // Remove input field at specified index
            this.$delete(this.medicare.additionalFullNames, index);
        },
        updateMedicare(index) {
            const propertyName = `FullName${index + 2}`;
            this.medicare.additionalFullNames[index] = this.additionalFullNames[index];
        },
    }
}
</script>