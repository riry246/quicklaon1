<template>
    <form>
        <div class="row gy-4 income-type-block button-set-block">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 border-end  ">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3">
                <button type="button" class="btn btn-outline-secondary btn-secondary-light full-width-button label-btn p-3"
                    :class="{ active: formData.income_type === 'wages' }" @click="showBlock('wages')">
                    Wages Only
                </button>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3">
                <button type="button" class="btn btn-outline-secondary btn-secondary-light full-width-button label-btn p-3"
                    @click="showBlock('wages-centerlink')" :class="{ active: formData.income_type === 'wages-centerlink' }">
                    Wages & Centerlink
                </button>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3">
                <button type="button" class="btn btn-outline-secondary btn-secondary-light full-width-button label-btn p-3"
                    :class="{ active: formData.income_type === 'centerlink' }" @click="showBlock('centerlink')">
                    Centerlink only
                </button>
            </div>
            <span class="error_msg" role="alert" v-if="errors.income_type">{{ errors.income_type }}</span>
            </div>
        
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 px-4">
        <div class="row gy-4 mb-3 button-set-block" v-show="activeBlock === 'wages' || activeBlock === 'wages-centerlink'">
            <label for="input-date" class="form-label text-muted">Employment
                type</label>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <button type="button" class="btn btn-outline-secondary btn-secondary-light full-width-button label-btn p-3"
                    :class="{ active: formData.employment_type === 'fulltime' }"
                    @click="selectedEmploymentType('fulltime')">
                    Full Time
                </button>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <button type="button" class="btn btn-outline-secondary btn-secondary-light full-width-button label-btn p-3"
                    :class="{ active: formData.employment_type === 'part-time' }"
                    @click="selectedEmploymentType('part-time')">
                    Part Time
                </button>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <button type="button" class="btn btn-outline-secondary btn-secondary-light full-width-button label-btn p-3"
                    :class="{ active: formData.employment_type === 'contractor' }"
                    @click="selectedEmploymentType('contractor')">
                    Contractor
                </button>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <button type="button" class="btn btn-outline-secondary btn-secondary-light full-width-button label-btn p-3"
                    :class="{ active: formData.employment_type === 'casual' }" @click="selectedEmploymentType('casual')">
                    Casual
                </button>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <button type="button" class="btn btn-outline-secondary btn-secondary-light full-width-button label-btn p-3"
                    :class="{ active: formData.employment_type === 'none' }" @click="selectedEmploymentType('none')">
                    None
                </button>
            </div>
            <span class="error_msg" role="alert" v-if="errors.employment_type">{{ errors.employment_type }}</span>

        </div>
        <div class="row gy-4 mb-3 button-set-block"
            v-show="activeBlock === 'wages' || activeBlock === 'wages-centerlink' || activeBlock === 'centerlink'">
            <label for="input-date" class="form-label text-muted">Pay cycle</label>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <button type="button" class="btn btn-outline-secondary btn-secondary-light full-width-button label-btn p-3"
                    :class="{ active: formData.pay_cycle === 'weekly' }" @click="selectedPayCycle('weekly')">
                    Weekly
                </button>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <button type="button" class="btn btn-outline-secondary btn-secondary-light full-width-button label-btn p-3"
                    :class="{ active: formData.pay_cycle === 'fortnightly' }" @click="selectedPayCycle('fortnightly')">
                    Fortnightly
                </button>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <button type="button" class="btn btn-outline-secondary btn-secondary-light full-width-button label-btn p-3"
                    :class="{ active: formData.pay_cycle === 'monthly' }" @click="selectedPayCycle('monthly')">
                    Monthly
                </button>
            </div>

            <span class="error_msg" role="alert" v-if="errors.pay_cycle">{{ errors.pay_cycle }}</span>
        </div>
        <div class="row gy-4 mb-3 button-set-block"
            v-show="activeBlock === 'wages' || activeBlock === 'wages-centerlink' || activeBlock === 'centerlink'">
            <label for="input-date" class="form-label text-muted">Pay Day</label>

            <!-- Loop through days and generate buttons dynamically -->
            <div v-for="day in ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']" :key="day"
                class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <button type="button" class="btn btn-outline-secondary btn-secondary-light full-width-button label-btn p-3"
                    :class="{ active: formData.pay_day === day }" @click="selectedPayDay(day)">
                    {{ capitalizeFirstLetter(day) }}
                </button>
            </div>

            <span class="error_msg" role="alert" v-if="errors.pay_day">{{ errors.pay_day }}</span>
        </div>
        <div class="row gy-4 mb-3 button-set-block"
            v-show="activeBlock === 'wages' || activeBlock === 'wages-centerlink' || activeBlock === 'centerlink'">
            <label for="input-date" class="form-label text-muted" >Pay Time</label>

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12"> 
                <input type="time" class="form-control" id="input-time" v-model="formData.pay_time">
            </div>
            

            <span class="error_msg" role="alert" v-if="errors.pay_time">{{ errors.pay_time }}</span>
        </div>
        <div class="row gy-4 mt-3 income-type-block-fields">
            <div class="col-12"
                v-show="activeBlock === 'wages' || activeBlock === 'wages-centerlink'">
                <label for="input-label" class="form-label text-muted">Wages after
                    tax</label>
                <div class="input-group mb-3">
                    <span class="input-group-text">$</span>
                    <input type="number" class="form-control" v-model="formData.wages_after_tax"
                        placeholder="Wages after tax">
                </div>


                <span class="error_msg" role="alert" v-if="errors.wages_after_tax">{{ errors.wages_after_tax }}</span>
            </div>
            <div class="col-12"
                v-show="activeBlock === 'centerlink' || activeBlock === 'wages-centerlink'">

                <label for="input-label" class="form-label text-muted">Centerlink
                    after
                    tax</label>
                <div class="input-group mb-3">
                    <span class="input-group-text">$</span>
                    <input type="number" class="form-control" v-model="formData.centerlink_after_tax"
                        placeholder="Centerlink after tax">
                </div>

                <span class="error_msg" role="alert" v-if="errors.centerlink_after_tax">{{ errors.centerlink_after_tax
                }}</span>
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
            activeBlock: '',
            formData: {
                income_type: '',
                employment_type: '',
                pay_cycle: '',
                pay_time: '',
                wages_after_tax: '',
                centerlink_after_tax: '',
                selectedPayDay: '',
            },
            errors: []
        };
    },
    methods: {
        showBlock(blockId) {
            this.activeBlock = blockId;
            this.formData.income_type = blockId;
        },
        selectedEmploymentType(type) {
            this.formData.employment_type = type;
        },
        selectedPayCycle(cycle) {
            this.formData.pay_cycle = cycle;
        },
        selectedPayDay(day) {
            this.formData.pay_day = day;
        },

        validateForm() {
            this.errors = [];
            if (!this.formData.income_type) {
                this.errors.income_type = 'Please select on income type';
            }
            if (!this.formData.pay_cycle) {
                this.errors.pay_cycle = 'Please select on pay cycle';
            }
            if (!this.formData.pay_day) {
                this.errors.pay_day = 'Please select on pay day';
            }
            if (!this.formData.pay_time) {
                this.errors.pay_time = 'Please select pay time';
            }

            if (this.formData.income_type == 'wages' || this.formData.income_type == 'wages-centerlink') {
                if (!this.formData.employment_type) {
                    this.errors.employment_type = 'Please select on employment type';
                }
            }
            if (this.formData.income_type == 'wages') {
                if (!this.formData.wages_after_tax) {
                    this.errors.wages_after_tax = 'Wages after tax is required';
                }
            }
            if (this.formData.income_type == 'wages-centerlink') {
                if (!this.formData.centerlink_after_tax) {
                    this.errors.centerlink_after_tax = 'Centerlink after tax is required';
                }
                if (!this.formData.wages_after_tax) {
                    this.errors.wages_after_tax = 'Wages after tax is required';
                }
            }
            if (this.formData.income_type == 'centerlink') {
                if (!this.formData.centerlink_after_tax) {
                    this.errors.centerlink_after_tax = 'Centerlink after tax is required';
                }
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
        capitalizeFirstLetter(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        },
        sendDataToParent() {
            this.$emit('sendData', this.formData);
        },
        backButton() {
            this.$emit('backButton');
        },
    }
}
</script>