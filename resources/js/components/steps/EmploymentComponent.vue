<template>
    <form>
        <div class="row gy-4 button-set-block">
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                <button type="button" class="btn btn-outline-secondary btn-secondary-light full-width-button label-btn p-3"
                    :class="{ active: formData.employment_detail === 'full-time-employment' }"
                    @click="selectedEmploymentType('full-time-employment')">
                    Full-time Employment
                </button>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                <button type="button" class="btn btn-outline-secondary btn-secondary-light full-width-button label-btn p-3"
                    :class="{ active: formData.employment_detail === 'part-time-employment' }"
                    @click="selectedEmploymentType('part-time-employment')">
                    Part-time Employment
                </button>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                <button type="button" class="btn btn-outline-secondary btn-secondary-light full-width-button label-btn p-3"
                    :class="{ active: formData.employment_detail === 'casual-employment' }"
                    @click="selectedEmploymentType('cascasual-employmentual')">
                    Casual Employment
                </button>
            </div>
            <span class="error_msg" role="alert" v-if="errors.employment_detail">{{ errors.employment_detail }}</span>
        </div>
        <div class="row gy-4 mt-3">
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 form-floating">

                <input type="text" class="form-control" id="input" placeholder="Company Name"
                    v-model="formData.company_name">
                <label for="floatingInput" class=" text-muted">Company
                    Name</label>
                <span class="error_msg" role="alert" v-if="errors.company_name">{{ errors.company_name }}</span>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 form-floating">

                <input type="text" class="form-control" id="input" placeholder="Contact Person"
                    v-model="formData.contact_person">
                <label for="floatingInput" class=" text-muted">Contact
                    Person</label>
                <span class="error_msg" role="alert" v-if="errors.contact_person">{{ errors.contact_person }}</span>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 form-floating">

                <input type="text" class="form-control" id="input" placeholder="Contract Person Email"
                    v-model="formData.contact_person_email">
                <label for="floatingInput" class=" text-muted">Contact
                    Person Email</label>
                <span class="error_msg" role="alert" v-if="errors.contact_person_email">{{ errors.contact_person_email
                }}</span>
            </div>
        </div>
        <div class="row gy-4 mt-3">
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 form-floating">

                <input 
  type="tel"
  inputmode="numeric"
  pattern="[0-9]*"
  maxlength="10"
  placeholder="04XXXXXXXX"
  class="form-control"
  v-model="formData.contact_person_mobile_number"
  @input="onlyNumbers"
/>
                <label for="floatingInput" class=" text-muted">Contact
                    Person Mobile Number</label>
                <span class="error_msg" role="alert" v-if="errors.contact_person_mobile_number">{{
                    errors.contact_person_mobile_number }}</span>
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
                employment_detail: '',
                company_name: '',
                contact_person: '',
                contact_person_email: '',
                contact_person_mobile_number: '',
            },
            reasons: '',
            errors: []
        };
    },
    methods: {
        validateForm() {
            this.errors = [];
            if (!this.formData.employment_detail) {
                this.errors.employment_detail = 'Please select employment detail';
            }

            if (!this.formData.company_name) {
                this.errors.company_name = 'Please enter company name';
            }

            if (!this.formData.contact_person) {
                this.errors.contact_person = 'Please enter contact person';
            }

            if (!this.formData.contact_person_email) {
                this.errors.contact_person_email = 'Please enter contact person email';
            } else if (!this.isValidEmail(this.formData.contact_person_email)) {
                this.errors.contact_person_email = 'Invalid email address';
            }

            if (!this.formData.contact_person_mobile_number) {
                this.errors.contact_person_mobile_number = 'Please enter mobile number';
            } else if (!this.isValidMobileNumber(this.formData.contact_person_mobile_number)) {
                this.errors.contact_person_mobile_number = 'Invalid mobile number';
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

        selectedEmploymentType(type) {
            this.formData.employment_detail = type;
        },
        isValidEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        },
        isValidMobileNumber(mobileNumber) {
            const regex = /^(?:\+?61|0)[2-478](?:[ -]?[0-9]){8}$/;
            return regex.test(mobileNumber);
        },
        onlyNumbers() {
    this.formData.contact_person_mobile_number =
        this.formData.contact_person_mobile_number.replace(/\D/g, '');
}

    }
    
}
</script>