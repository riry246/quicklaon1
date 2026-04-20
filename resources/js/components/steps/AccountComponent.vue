<template>
    <form>
        <div class="row gy-4">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card noshadow">
                    <div class="card-body">
                        <div class="row gy-4">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 form-floating">
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Email Address"
                                    v-model="formData.email"
                                >
                                <label for="floatingInput" class="text-muted">Email Address</label>
                                <span class="error_msg" role="alert" v-if="errors.email">
                                    {{ errors.email }}
                                </span>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 form-floating">
                                <div class="input-group">
                                    <input
                                        :type="showPassword ? 'text' : 'password'"
                                        class="form-control form-control-lg"
                                        id="signin-password"
                                        placeholder="Password"
                                        required
                                        v-model="formData.password"
                                    >
                                    <button
                                        class="btn btn-light"
                                        type="button"
                                        @click="viewPassword"
                                        id="button-addon2"
                                    >
                                        <i :class="showPassword ? 'ri-eye-line' : 'ri-eye-off-line'"></i>
                                    </button>
                                </div>
                                <span class="error_msg" role="alert" v-if="errors.password">
                                    {{ errors.password }}
                                </span>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 form-floating">
                                <div class="input-group">
                                    <input
                                        :type="showPassword ? 'text' : 'password'"
                                        class="form-control form-control-lg"
                                        id="signin-password-1"
                                        placeholder="Re Password"
                                        required
                                        v-model="formData.repassword"
                                    >
                                    <button
                                        class="btn btn-light"
                                        type="button"
                                        @click="viewPassword"
                                        id="button-addon3"
                                    >
                                        <i :class="showPassword ? 'ri-eye-line' : 'ri-eye-off-line'"></i>
                                    </button>
                                </div>
                                <span class="error_msg" role="alert" v-if="errors.repassword">
                                    {{ errors.repassword }}
                                </span>
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
            formData: {
                email: '',
                password: '',
                repassword: '',
            },
            showPassword: false,
            errors: {}
        };
    },
    mounted() {
        this.formData.email = this.val.user.email;
    },
    methods: {
        validateForm() {
            this.errors = {};

            if (!this.formData.email) {
                this.errors.email = 'Please enter contact person email';
            } else if (!this.isValidEmail(this.formData.email)) {
                this.errors.email = 'Invalid email address';
            }

            if (!this.formData.password) {
                this.errors.password = 'Password is required.';
            } else {
                this.validatePasswordStrength();
            }

            if (!this.formData.repassword) {
                this.errors.repassword = 'Re-entering the password is required.';
            } else if (this.formData.password !== this.formData.repassword) {
                this.errors.repassword = 'Passwords do not match.';
            }

            return Object.keys(this.errors).length === 0;
        },

        validatePasswordStrength() {
            const password = this.formData.password;

            const minLength = 8;
            const hasUppercase = /[A-Z]/.test(password);
            const hasLowercase = /[a-z]/.test(password);
            const hasNumber = /\d/.test(password);
            const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);

            if (
                password.length < minLength ||
                !hasUppercase ||
                !hasLowercase ||
                !hasNumber ||
                !hasSpecialChar
            ) {
                this.errors.password =
                    'Password must be at least 8 characters long and include uppercase, lowercase, number, and special character.';
            }
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

        isValidEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        },

        viewPassword() {
            this.showPassword = !this.showPassword;
        }
    }
}
</script>