<template>
    <div class="card-body">
        <div class="col-xl-12 col-xl-6">
            <div class="card custom-card no-border-card">
                <div class="card-body p-0 overflow-hidden">
                    <div class="mt-3 pt-1 pb-5">
                        <h4 class="fs-4">Your Points
                        </h4>
                        <p class="fw-light mb-0 fs-14">More points, better chances for loan approval.
                            Keep earning!
                        </p>
                        <a href="#" class=" text-muted text-secondary fw-medium fs-14 mb-3">Click
                            here to learn more</a>
                            <p class="mt-3"><span class="slider_value_highlight">
                            {{ val.score }}
                        </span></p>
                        
                    </div>

                    <div class="progress progress-sm mb-2 mt-5 progress-animate bg-dark-transparent" role="progressbar" aria-valuenow="50"
                        style="position: initial;" aria-valuemin="0" aria-valuemax="100">
                        <div :class="{ 'progress-item-1': true, 'bg-info': score >= 25 } "></div>
                        <div :class="{ 'progress-item-2': true, 'bg-info': score >= 50 }"></div>
                        <div :class="{ 'progress-item-3 svg-image': true, 'bg-info': score >= 75 }">
                           
                        </div>
                        <div class="progress-bar bg-info" :style="{ width: score + '%' }"></div>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col text-muted">
                                0
                            </div>
                            <div class="col text-center text-muted">
                                250
                            </div>
                            <div class="col text-end text-muted">
                                500
                            </div>
                            <div class="col text-end text-muted">
                                750
                            </div>
                            <div class="col text-end text-muted">
                                1000
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
            score: 0

        };
    },
    mounted() {
        this.calculatePercentage();
    },
    methods: {
        calculatePercentage() {
            const highestScore = 1000;
            this.score = (this.val.score / highestScore) * 100;

        }
    }

}
</script>