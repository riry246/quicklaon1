<template>
    <div class="card custom-card">
        <div class="card-header justify-content-between noborder pb-0 mb-0">
            <div class="card-title">
                <h5> Profile Information</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-solid-success alert-dismissible fade show" v-if="success">
                {{ success }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                        class="bi bi-x"></i></button>
            </div>
            <div class="alert alert-solid-danger alert-dismissible fade show" v-if="error">
                {{ error }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                        class="bi bi-x"></i></button>
            </div>
            <h6 class="mt-2 fw-medium">Basic Information</h6>
            <div class="row gy-4 mt-1">
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

                    <input type="date" class="form-control" id="floatingInput" placeholder="Date of birth" required
                        v-model="formData.dob">
                    <label for="floatingInput" class="text-muted">Birthday</label>
                    <span class="error_msg" role="alert" v-if="errors.dob">{{ errors.dob }}</span>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 form-floating">
                    <input type="tel" class="form-control" id="floatingInput" readonly placeholder="+61-2031-1233" required
                        v-model="formData.mobile">
                    <label for="floatingInput" class="text-muted">Mobile</label>
                    <span class="error_msg" role="alert" v-if="errors.mobile">{{ errors.mobile }}</span>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 form-floating">
                    <input type="tel" class="form-control" id="floatingInput" readonly placeholder="Email Address" required
                        v-model="formData.email">
                    <label for="floatingInput" class="text-muted">Email Address</label>
                    <span class="error_msg" role="alert" v-if="errors.mobile">{{ errors.email }}</span>
                </div>


            </div>
            <hr class="mt-5" />
            <h6 class="mt-4 fw-medium">Change Password</h6>
            <div class="row gy-4 mt-1">
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 form-floating">

                    <input type="password" class="form-control" placeholder="Old Password" required
                        v-model="formData.oldPassword">
                    <label for="floatingInput" class=" text-muted">Old Password</label>
                    <span class="error_msg" role="alert" v-if="errors.oldPassword">{{ errors.oldPassword }}</span>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 form-floating">

                    <input type="password" class="form-control" placeholder="New Password" required
                        v-model="formData.newPassword">
                    <label for="floatingInput" class=" text-muted">New Password</label>
                    <span class="error_msg" role="alert" v-if="errors.mobile">{{ errors.newPassword }}</span>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 form-floating">

                    <input type="password" class="form-control" placeholder="Re-Password" required
                        v-model="formData.rePassword">
                    <label for="floatingInput" class="\ text-muted">Re-Password</label>
                    <span class="error_msg" role="alert" v-if="errors.rePassword">{{ errors.rePassword }}</span>
                </div>
            </div>

            <div class="row mt-5">
                <div class="d-grid gap-2 col-6 ">
                    <button type="submit" class="btn btn-lg btn-secondary label-btn label-end" v-if="loading"><span
                            class="spinner-border spinner-border-sm align-middle" role="status" aria-hidden="true"></span>
                        Processing...</button>
                    <button type="submit" class="btn btn-secondary label-btn label-end" v-else @click="submitForm()">
                        Update Information
                        <i class="ri-arrow-right-s-line label-btn-icon ms-2"></i>
                    </button>
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
                first_name: this.val.user.first_name || '',
                middle_name: this.val.user.middle_name || '',
                last_name: this.val.user.last_name || '',
                dob: this.val.user.dob || '',
                mobile: this.val.user.mobile || '',
                email: this.val.user.email || '',
                user_id: this.val.user.id,
                newPassword: '',
                oldPassword: '',
                rePassword: '',
            },
            loading: false,
            success: '',
            error: '',
            errors: []
        };
    },
    methods: {
        validateForm() {
            this.errors = [];
            if (!this.formData.first_name) {
                this.errors.first_name = 'First Name is required.';
            }
            if (!this.formData.last_name) {
                this.errors.last_name = 'Last Name is required.';
            }
            if (!this.formData.dob) {
                this.errors.dob = 'Date of birth is required.';
            }
            if (!this.formData.mobile) {
                this.errors.mobile = 'Mobile is required.';
            }
            if (this.formData.newPassword) {
                if (!this.formData.rePassword) {
                    this.errors.rePassword = 'This field is required.';
                }
                if (!this.formData.oldPassword) {
                    this.errors.oldPassword = 'This field is required.';
                }

                if (this.formData.rePassword !== this.formData.newPassword) {
                    this.errors.rePassword = 'Password miss matched.';
                }
            }


            return Object.keys(this.errors).length === 0;
        },
        submitForm() {
            this.success = ''; // Reset success message
            this.error = '';
            const isValid = this.validateForm();

            if (isValid) {
                this.loading = true;
                // Reset error message

                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
                axios.post(this.baseUrl + 'api/auth/update-profile', this.formData)
                    .then(response => {
                        this.success = response.data.message;
                    })
                    .catch(error => {
                        this.error = error.response.data.message;
                    })
                    .finally(() => {
                        this.loading = false;
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    });
            } else {
                console.log('Form is invalid. Please correct the errors.');
            }
        }

    }

}
</script>