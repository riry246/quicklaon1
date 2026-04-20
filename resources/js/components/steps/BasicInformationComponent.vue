<template>
    <form>
        <div class="row gy-4">
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 form-floating">

                <select class="form-control" data-trigger name="choices-single-default" id="choices-single-default"
                    v-model="formData.title">
                    <option value="Mr.">Mr.</option>
                    <option value="Mrs.">Mrs.</option>
                    <option value="Miss">Miss</option>
                    <option value="Dr.">Dr.</option>
                </select>
                <label for="floatingSelect" class="text-muted">Title</label>
                <span class="error_msg" role="alert" v-if="errors.title">{{ errors.title }}</span>

            </div>
        </div>
        <div class="row gy-4 mt-3">
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 form-floating">
                <input type="text" class="form-control" placeholder="First Name" name="first_name" required
                    v-model="formData.first_name">
                <label for="floatingInput" class="text-muted">First Name</label>
                <span class="error_msg" role="alert" v-if="errors.first_name">{{ errors.first_name }}</span>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 form-floating ">
                <input type="text" class="form-control" id="input-label" placeholder="Middle Name" name="middle_name"
                    v-model="formData.middle_name">
                <label for="floatingInput" class="text-muted">Middle Name</label>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 form-floating">
                <input type="text" class="form-control" id="input-placeholder" placeholder="Last Name" name="last_name"
                    v-model="formData.last_name" required>
                <label for="floatingInput" class="text-muted">Last Name</label>
                <span class="error_msg" role="alert" v-if="errors.last_name">{{ errors.last_name }}</span>
            </div>
        </div>
        <div class="row gy-4 mt-3">
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 form-floating">

                <input type="date" class="form-control" id="input-date" placeholder="Date of birth" required
                    v-model="formData.dob">
                <label for="floatingInput" class="text-muted">Birthday</label>
                <span class="error_msg" role="alert" v-if="errors.dob">{{ errors.dob }}</span>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 form-floating">
                <input type="tel" class="form-control" maxlength="10" id="input-tel" :readonly="mobilePresent"
                    placeholder="+61-2031-1233" required v-model="formData.mobile">
                <label for="floatingInput" class="text-muted">Mobile</label>
                <span class="error_msg" role="alert" v-if="errors.mobile">{{ errors.mobile }}</span>
            </div>

        </div>
        <div class="row gy-4 mt-3">
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 form-floating">
                <select class="form-control" data-trigger name="choices-single-default" v-model="formData.martial_status">
                    <option value="single">Single</option>
                    <option value="married">Married</option>
                    <option value="in a de facto relationship">In a de facto relationship
                    </option>
                    <option value="separated">Seprated</option>
                    <option value="divorced">Divorced</option>
                    <option value="widowed ">Widowed</option>
                    <option value="never married ">Never married</option>
                </select>
                <label for="floatingSelect" class="text-muted">Martial
                    Status</label>
                <span class="error_msg" role="alert" v-if="errors.martial_status">{{ errors.martial_status }}</span>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 form-floating">
                <select class="form-control" data-trigger name="choices-single-default" id="choices-single-default"
                    v-model="formData.dependent">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="10+">10+</option>

                </select>
                <label for="floatingSelect" class="text-muted">No. of
                    Dependent</label>
                <span class="error_msg" role="alert" v-if="errors.dependent">{{ errors.dependent }}</span>
            </div>
        </div>
        <div class="row gy-4 mt-3">
            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 form-floating">


                <vue-google-autocomplete id="location-input" ref="locationInput" class="form-control"
                    v-model="formData.address" placeholder="Start typing location" @placechanged="placeChanged"
                    :placeAutocompleteOptions="autocompleteOptions" country="AU"></vue-google-autocomplete>
                <label for="floatingInput" class="text-muted">Address</label>
                <span class="error_msg" role="alert" v-if="errors.address">{{ errors.address }}</span>
            </div>
            <!--   <div>
              <a class="text-primary " data-bs-effect="effect-super-scaled" data-bs-toggle="modal"
                    href="#modaldemo2">Enter manually</a>
            </div>-->

            <div class="modal fade" id="modaldemo2">
                <div class="modal-dialog modal-dialog-centered text-center" role="document">
                    <div class="modal-content modal-content-demo">
                        <div class="modal-header">
                            <h6 class="modal-title text-center">Enter Your Address
                            </h6>
                            <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-start front-slider">
                            <p class="mb-4 text-muted op-7 fw-normal text-center">
                                Please enter your valid address</p>

                            <div class="row">
                                <div class="col-xl-12 mb-4 ">
                                    <div class="row">
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                            <label for="input-label" class="text-muted">Street</label>
                                            <input type="text" class="form-control" id="input" placeholder="Street"
                                                name="street" />
                                        </div>
                                    </div>
                                    <div class="row mt-3">

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="input-date" class="text-muted">City/
                                                Suburb</label>
                                            <select class="form-control" data-trigger name="city"
                                                id="choices-single-default">
                                                <option value="">Select City/ Suburb
                                                </option>
                                                <option value="Choice 1">Choice 1
                                                </option>
                                                <option value="Choice 2">Choice 2
                                                </option>
                                                <option value="Choice 3">Choice 3
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="input-date" class="text-muted">State</label>
                                            <select class="form-control" data-trigger name="state"
                                                id="choices-single-default">
                                                <option value="">Select State
                                                </option>
                                                <option value="Choice 1">Choice 1
                                                </option>
                                                <option value="Choice 2">Choice 2
                                                </option>
                                                <option value="Choice 3">Choice 3
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="input-label" class="text-muted">Zipcode</label>
                                            <input type="text" class="form-control" id="input" placeholder="Zipcode"
                                                name="zipcode">
                                        </div>

                                    </div>

                                </div>

                            </div>


                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary">Update
                                Address</button>
                            <button class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row gy-4 mt-3">
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 form-floating">

                <select class="form-control" data-trigger name="choices-single-default" id="choices-single-default"
                    v-model="formData.residential_status">
                    <option value="Renting">Renting</option>
                    <option value="Own home with mortgage">Own home with mortgage</option>
                    <option value="Living with parents">Living with parents</option>
                    <option value="Own home outright">Own home outright</option>
                </select>
                <label for="floatingSelect" class="text-muted">Residential
                    Status</label>
                <span class="error_msg" role="alert" v-if="errors.residential_status">{{ errors.residential_status }}</span>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 form-floating">
                <select class="form-control" data-trigger name="when_did_you_move_in" id="choices-single-default"
                    v-model="formData.when_did_you_move_in">
                    <option value="less than a years">Less than a year</option>
                    <option value="1 year">1 years</option>
                    <option value="2 years">2 years</option>
                    <option value="3 years">3 years</option>
                    <option value="4 years">4 years</option>
                    <option value="5 years">5 years</option>
                    <option value="6 years">6 years</option>
                    <option value="7 years">7 years</option>
                    <option value="8 years">8 years</option>
                    <option value="9 years">9 years</option>
                    <option value="10 years">10 years</option>
                    <option value="10+ years">10+ years</option>
                </select>
                <label for="floatingSelect" class="text-muted">When did you move in?</label>
                <span class="error_msg" role="alert" v-if="errors.when_did_you_move_in">{{ errors.when_did_you_move_in
                }}</span>

            </div>


        </div>
        <div class="row gy-4 mt-3">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <label for="input-date" class="text-muted">Are you an Australian Citizen or Permanent
                    Resident?</label>
                <div class="btn-group btn-group-lg my-1" role="group" aria-label="Large button group">
                    <button type="button" class="btn btn-outline-secondary "
                        :class="{ active: formData.are_you_a_permanent_resident === 'Yes' }"
                        @click="selectedAnswer('Yes')">Yes</button>
                    <button type="button" class="btn btn-outline-danger"
                        :class="{ active: formData.are_you_a_permanent_resident === 'No' }"
                        @click="selectedAnswer('No')">No</button>

                </div>
                <span class="error_msg" role="alert" v-if="errors.are_you_a_permanent_resident">{{
                    errors.are_you_a_permanent_resident
                }}</span>
            </div>
        </div>
    </form>
</template>
<script>
import VueGoogleAutocomplete from 'vue-google-autocomplete';
export default {
    components: {
        VueGoogleAutocomplete,
    },
    props: ['val'],
    data() {
        return {
            formData: {
                title: '',
                first_name: this.val.user.first_name || '',
                middle_name: this.val.user.middle_name || '',
                last_name: this.val.user.last_name || '',
                dob: this.val.user.dob || '',
                mobile: this.val.user.mobile || '',
                martial_status: '',
                dependent: '',
                address: '',
                residential_status: '',
                when_did_you_move_in: '',
                are_you_a_permanent_resident: '',
                administrative_area_level_1: '',
                administrative_area_level_2: '',
                country: '',
                latitude: '',
                locality: '',
                longitude: '',
                postal_code: '',
                route: '',
                street_number: '',
            },
            mobilePresent: false,
            autocompleteOptions: {
                types: ['address'],
                componentRestrictions: { country: 'AU' }, // Restrict results to Australia
            },
            errors: []
        };
    },
    mounted() {
        this.isMobileEmpty();
    },
    methods: {
        isMobileEmpty() {
            if (this.formData.mobile == '') {
                this.mobilePresent = false;
            }

        },
        validateForm() {
            this.errors = [];
            if (!this.formData.title) {
                this.errors.title = 'Title is required.';
            }
            if (!this.formData.first_name) {
                this.errors.first_name = 'First Name is required.';
            }
            if (!this.formData.last_name) {
                this.errors.last_name = 'Last Name is required.';
            }
            if (!this.formData.dob) {
                this.errors.dob = 'Date of birth is required.';
            } else {
                const dob = new Date(this.formData.dob);
                const eighteenYearsAgo = new Date();
                eighteenYearsAgo.setFullYear(eighteenYearsAgo.getFullYear() - 18);

                if (dob > eighteenYearsAgo) {
                    this.errors.dob = 'You must be at least 18 years old.';
                }
            }
            if (!this.formData.mobile) {
                this.errors.mobile = 'Mobile is required.';
            }
            if (!this.formData.martial_status) {
                this.errors.martial_status = 'Martial Status is required.';
            }
            if (!this.formData.dependent) {
                this.errors.dependent = 'Dependent is required.';
            }
            if (!this.formData.address) {
                this.errors.address = 'Address is required.';
            }
            if (!this.formData.residential_status) {
                this.errors.residential_status = 'Residental Status is required.';
            }
            if (!this.formData.when_did_you_move_in) {
                this.errors.when_did_you_move_in = 'This field is required.';
            }
            if (!this.formData.are_you_a_permanent_resident) {
                this.errors.are_you_a_permanent_resident = 'This field is required.';
            }
            if (!this.formData.mobile) {
                this.errors.mobile = 'Please enter mobile number';
            } else if (!this.isValidMobileNumber(this.formData.mobile)) {
                this.errors.mobile = 'Invalid mobile number';
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
        selectedAnswer(answer) {
            this.formData.are_you_a_permanent_resident = answer;
        },
        sendDataToParent() {
            this.$emit('sendData', this.formData);
        },
        isValidMobileNumber(mobileNumber) {
            const regex = /^(?:\+?61|0)[2-478](?:[ -]?[0-9]){8}$/;
            return regex.test(mobileNumber);
        },
        placeChanged(place) {

            const propertiesToCopy = [
                'administrative_area_level_1',
                'administrative_area_level_2',
                'country',
                'latitude',
                'locality',
                'longitude',
                'postal_code',
                'route',
                'street_number'
            ];

            for (const property of propertiesToCopy) {
                this.formData[property] = place[property] || '';
            }

            this.formData.address = (
                (place.street_number ? place.street_number + ' ' : '') +
                (place.route ? place.route + ',' : '') +
                (place.locality ? place.locality + ' ' : '') +
                (place.administrative_area_level_1 ? place.administrative_area_level_1 + ', ' : '') +
                (place.postal_code ? place.postal_code + ', ' : '') +
                (place.country ? place.country : '')
            ).trim();

        },
    }

}
</script>
  