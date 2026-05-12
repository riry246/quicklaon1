<template>
    <div class="p-5 smp-0" v-if="signin">

        <h2 class="text-center mb-5"><img src="/assets/images/icon/card.png" class="img-fluid rounded-end " alt="..."
                style="width:100px;"><br /><br />Sign In or Register</h2>
        <!--Error Message Start-->
        <div class="alert alert-solid-danger alert-dismissible fs-15 fade show mb-5" v-if="error">
            {{ error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                    class="bi bi-x"></i></button>
        </div>
        <!--Error Message End-->
        <div class="row  mt-0">
            <div class="col-xl-12 ">
                <label class="form-label text-default">Mobile Number</label>
                <input 
  type="tel"
  class="form-control form-control-lg"
  v-model="mobile"
  placeholder="Mobile Number"
  maxlength="10"
  @input="checkMobileNumber"
/>


            </div>
            <p class="mb-4 mt-4 text-center subheading op-7 fw-normal ">Please enter valid and active mobile number as we
                will send a
                verification code to this number.</p>
            <div class="col-xl-12 d-grid mt-4">
                <button type="submit" class="btn btn-lg btn-secondary" v-if="loading"><span
                        class="spinner-border spinner-border-sm align-middle" role="status" aria-hidden="true"></span>
                    Processing...</button>
                <button type="submit" class="btn btn-lg btn-secondary" @click="sendOtp" v-else>Sign In</button>

            </div>
        </div>
    </div>
    <div class="p-5 smp-0" v-else>
        <h2 class="text-center ">
            Verify Your Mobile
            Number</h2>
        <p class="mb-5 text-center subheading op-7 fw-normal ">Enter the 4 digit code sent to the
            registered mobile number.
        </p>
        <!--Error Message Start-->
        <div class="alert alert-solid-danger alert-dismissible fs-15 fade show mb-5" v-if="error">
            {{ error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                    class="bi bi-x"></i></button>
        </div>
        <!--Error Message End-->
        <div class="row gy-3 px-5">
            <div class="col-xl-12 mb-2">
                <div class="row pb-4">

                    <div class="col-3" v-for="(input, index) in inputFields">
                        <input 
  type="tel"
  inputmode="numeric"
  pattern="[0-9]*"
  class="form-control text-center"
  maxlength="1"
  @keyup="handleKeyUp(index, $event.target.value)"
>
                    </div>


                </div>
            </div>
            <div class="form-check mt-3 p-0">
                <label class="form-check-label fs-13" for="defaultCheck1">
                    Did not recieve a code?
                    <span v-if="timer > 0" class="text-secondary  d-inline-block fw-bold">
                        Wait for : {{ minutes }}:{{ seconds }} If it doesn't arrive shortly, request a new one by clicking
                        'Resend'."
                    </span>
                    <span v-else>
                        <a href="javascript:void(0);" @click="sendOtp"
                            class="text-secondary ms-2 mx-2 d-inline-block fw-bold">Resend</a>
                        or
                        <a href="javascript:void(0)" class="text-secondary d-inline-block mx-2 fw-bold"
                            @click="signin = true">Change
                            mobile number</a>
                    </span>
                </label>
            </div>

            <button type="submit" class="btn btn-lg btn-secondary" v-if="loading"><span
                    class="spinner-border spinner-border-sm align-middle" role="status" aria-hidden="true"></span>
                Processing...</button>
            <button type="submit" class="btn btn-lg btn-secondary" @click="verifyOtp" v-else>Verify OTP</button>
        </div>
        <div>
            <p class="fs-12 text-danger mt-3 mb-0 text-center"><sup></sup>Don't share the verification
                code with
                anyone !<i class="ri-asterisk"></i></p>
        </div>
    </div>
</template>
<script>
export default {
    props: ['jsonData'],
    data() {
        return {
            mobile: '',
            error: null,
            signin: true,
            inputFields: ['', '', '', ''],
            otp: '',
            loading: false,
            timer: 60, // 60 seconds (1 minute)
            intervalId: null, // Store the interval ID
            otpcount: 0
        };
    },
    computed: {
        // Calculate minutes and seconds from the timer value
        minutes() {
            return Math.floor(this.timer / 60);
        },
        seconds() {
            return this.timer % 60;
        }
    },
    methods: {
        checkMobileNumber() {
            // Remove non-numeric characters
    this.mobile = this.mobile.replace(/\D/g, '');
            if (this.mobile.length === 10) {
                if (this.isValidMobileNumber()) {
                    //this.sendOtp();
                } else {
                    this.error = 'Please enter valid Australian mobile number';
                }
            }
        },
        isValidMobileNumber() {
            const regex = /^04\d{8}$/;
            return regex.test(this.mobile);
        },
        sendOtp() {
            this.otpcount++;
            this.loading = true;
            axios.post('api/sendotp', { mobile: this.mobile })
                .then(response => {
                    this.signin = false;
                    this.error = null;
                    this.loading = false;
                    this.startOneMinuteTimer();
                })
                .catch(error => {
                    this.error = error.response.data.message;
                    this.loading = false;
                });
        },
        startOneMinuteTimer() {
            // Set the timer to 60 seconds (1 minute)
            this.timer = 60;
            if (this.otpcount > 1) {
                this.timer = 120;
            }


            // Start the timer countdown
            this.intervalId = setInterval(() => {
                if (this.timer > 0) {
                    this.timer--; // Decrement the timer
                } else {
                    clearInterval(this.intervalId); // Clear the interval when the timer reaches 0
                }
            }, 1000); // Update the timer every second (1000 milliseconds)
        },
        verifyOtp() {
            this.loading = true;
            axios.post('api/verifyotp', { mobile: this.mobile, otp: this.otp })
                .then(response => {
                    sessionStorage.setItem('amount', this.jsonData.amount);
                    sessionStorage.setItem('duration', this.jsonData.duration);
                    sessionStorage.setItem('frequency', this.jsonData.frequency);
                    sessionStorage.setItem('token', response.data.token);
                    sessionStorage.setItem('user_id', response.data.user_id);
                    window.location.href = "application/apply";
                    this.loading = false;
                    //this.$router.push('/about');
                })
                .catch(error => {
                    this.error = error.response.data.message;
                    this.loading = false;
                });
        },
        handleKeyUp(index, value) {
           
            this.inputFields[index] = value;
            this.otp = this.inputFields.join('');
            const nextIndex = index + 1;
            if (nextIndex < this.inputFields.length) {
                this.$refs.inputs[nextIndex].focus();
            }
            if (this.otp.length === 4) {
                this.verifyOtp();
            }
        },

    },

}
</script>