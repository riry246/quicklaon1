<template>
    <div class="expenses-list">
        <div v-for="(expense, index) in expenses" :key="index">
            <div class="row gy-4 mb-3 expenses-block">
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                    <label for="input-label" class="form-label text-muted">Title</label>
                    <input type="text" class="form-control" v-model="expense.title" placeholder="Ex. Rent, Mortgage, etc">
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                    <label for="input-label" class="form-label text-muted">Amount</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text">$</span>
                        <input type="number" class="form-control" v-model="expense.amount" placeholder="Amount">
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 pointer-cursor">
                    <span class="avatar avatar-md avatar-rounded text-bg-danger fs-18 mt-4 delete-expenses"
                        @click="deleteExpense(index)">
                        <i class="bi bi-trash3 fs-16"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 mt-3">
        <input type="button" class="form-control btn-secondary" id="input-new" value="Add New" @click="addExpense">
    </div>
</template>
<script>

export default {
    props: ['val'],
    data() {
        return {
            formData: {},
            expenses: [],
            errors: []
        };
    },
    mounted() {
        this.addExpense();
    },
    methods: {
        validateForm() {
            this.errors = [];

            this.expenses.forEach((data, index) => {
                console.log(data);
                if (data.title !== '' || data.title !== null) {
                    this.formData['expenses_' + data.title.toLowerCase()] = data.amount;
                }
            });


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
        addExpense() {
            this.expenses.push({ title: '', amount: '' });
        },
        deleteExpense(index) {
            this.expenses.splice(index, 1);
        }
    }
}
</script>