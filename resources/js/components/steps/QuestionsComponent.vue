<template>
    <form>
        <!--Error Message Start-->
        <div class="alert alert-solid-danger alert-dismissible fs-15 fade show mb-4" v-if="errors.question">
            {{ errors.question }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                    class="bi bi-x"></i></button>
        </div>
        <!--Error Message End-->

        <div class="row gy-4" v-for="ques in questions" :key="ques.id">
            <div class="col-xl-10 col-lg-10 col-md-10 col-sm-12 mb-3">
                <label for="input-date" class="form-label  fs-17">{{ ques.question }}</label>
                <p class="text-muted fs-14">{{ ques.description }}</p>

            </div>
            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 d-flex align-items-center justify-content-between">
                <div class="btn-group btn-group-lg my-1" role="group" aria-label="Large button group">
                    <button type="button" class="btn btn-outline-secondary "
                        :class="{ active: formData[ques.slug] === 'Yes' }"
                        @click="selectedAnswer(ques.slug, 'Yes')">Yes</button>
                    <button type="button" class="btn btn-outline-danger" :class="{ active: formData[ques.slug] === 'No' }"
                        @click="selectedAnswer(ques.slug, 'No')">No</button>

                </div>
            </div>
            <div v-if="ques.slug == 'question_do-you-have-any-other-outstanding-loans-or-financial-commitments'">
                <div class="row gy-4 " v-if="activateExtra">
                    <h6 class="text-muted">Please provide a more detail</h6>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 ">
                        <label for="input-label" class="form-label text-muted">Name of Institution </label>
                        <input type="text" class="form-control" id="input" v-model="formData.name_of_institution"
                            @click="checkEmpty">
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <label for="input-tel" class="form-label text-muted"> Amount Owed</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="input-tel" v-model="formData.amount_owned">
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
            baseUrl: import.meta.env.VITE_APP_URL,
            questions: [],
            errors: [],
            result: '',
            formData: {
                name_of_institution: ' ',
                amount_owned: '0',
            },
            activateExtra: false
        };
    },
    mounted() {
        this.getQuestions();
    },
    methods: {
        checkEmpty: function () {
            if (this.formData.name_of_institution == ' ') {
                this.formData.name_of_institution = null;
            }
        },
        validateForm() {
            this.errors = [];
            let anyFieldEmpty = false;

            for (const key in this.formData) {
                if (this.formData.hasOwnProperty(key)) {
                    const value = this.formData[key];
                    if (key !== 'name_of_institution' || key !== 'amount_owned') {
                        if (value === '' || value === null) {
                            anyFieldEmpty = true;
                            break; // Exit the loop early since we found an empty field
                        }
                    }

                }
            }

            // Check the anyFieldEmpty flag to see if any field is empty
            if (anyFieldEmpty) {
                this.errors.question = 'Please read carefully and select all the answer.';
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

        getQuestions() {
            axios.defaults.headers.common['Authorization'] = `Bearer ${sessionStorage.getItem('token')}`;
            axios.get(this.baseUrl + 'api/auth/loan-question')
                .then(response => {
                    this.result = response.data.data;
                    this.manageQuestions();
                })
                .catch(error => {
                    this.error = error.response.data.message;
                });
        },

        manageQuestions() {
            this.result.forEach((data, index) => {
                this.questions.push({
                    question: data.questions,
                    description: data.description,
                    slug: data.slug,
                    id: index + 1 // Adding a unique identifier (you can use any unique identifier you prefer)
                });
                this.formData[data.slug] = '';
            });

        },
        selectedAnswer(slug, answer) {
            this.formData[slug] = answer;
            if (slug == 'question_do-you-have-any-other-outstanding-loans-or-financial-commitments') {
                if (answer == 'Yes') {
                    this.activateExtra = true;
                    this.formData.name_of_institution = null;
                } else {
                    this.activateExtra = false;
                    this.formData.name_of_institution = ' ';
                }
            }
        }

    }
}
</script>