<template>
    <div class="card custom-card"
        v-if="val.loanApplication.status == 'pending' || val.loanApplication.status == 'processing'">
        <div class="card-body">
            <h6 class="card-title fw-semibold mb-3 border-bottom pb-3">QuickLoan Points<span
                    class="badge bg-info float-end fs-6">{{ val.score }}</span></h6>
            <div class="row ">
                <div
                    class="col-xxl-2 col-xl-2 medium-icon  col-lg-2 col-md-2 col-sm-12 col-12 d-flex align-items-center justify-content-center ecommerce-icon success px-0">
                    <i class="bx bxs-star text-warning"></i>
                </div>
                <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 justify-content-center p-0">
                    <h6>Love using Quickloans? </h6>
                    <p class="card-text fs-7 fw-semibold text-muted">Share your experience and leave us a review! </p>

                </div>
            </div>
        </div>
        <div class="card-footer">
            <a class="btn btn-secondary d-grid" data-bs-effect="effect-super-scaled" data-bs-toggle="modal"
                href="#modaldemo1">Leave us a
                review</a>
        </div>
        <div class="modal fade" id="modaldemo1">
            <div class="modal-dialog modal-dialog-centered text-center" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title text-center">Review
                        </h6>
                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-start front-slider">

                        <p class="mb-4 op-7 fw-normal text-center fs-14">
                            For a limited time, <span class="fw-semibold">earn 30 Quickloans points</span> <br />when you
                            share your thoughts.
                            Your feedback means a lot to us! <br />

                        </p>
                        <div class="row">
                            <div class="col-xl-8 mb-4 mx-auto">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="min-height-30">
                                            <img src="/assets/images/icon/google.png" class="img-fluid rounded-end w-50"
                                                alt="...">
                                        </div>

                                        <a href="#" class="text-primary ms-2 d-inline-block mt-3">Review
                                            now</a>
                                    </div>
                                    <div class="col-6">
                                        <div class="min-height-30">
                                            <img src="/assets/images/icon/trust.png" class="img-fluid rounded-end w-50"
                                                alt="...">
                                        </div>

                                        <a target="_blank" href="https://www.trustpilot.com/review/Quickloans.com.au" class="text-primary ms-2 d-inline-block mt-3">Review
                                            now</a>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="card custom-card" v-if="val.loanApplication.status == 'declined'">
        <div class="card-body">
            <h6 class="card-title fw-semibold mb-3 border-bottom pb-3">Tips to improve</h6>
            <p class="card-text fs-7 text-muted">
            <ol>
                <li><b>Reduce Debt Load:</b><br /> Consolidate loans and focus on paying off high-interest debts to minimize
                    financial obligations.</li>
                <li><b>Meet Financial Commitments:</b><br /> Ensure timely payments for bills and obligations to maintain a
                    positive financial standing.</li>
                <li><b>Maintain a Financial Buffer:</b> <br />Avoid withdrawing all funds at once, planning for emergencies
                    and unexpected expenses.
                </li>
                <li><b>Increase Income:</b><br /> Seek additional income sources or invest in professional development to
                    enhance earning potential.</li>
                <li><b>Improve Credit Score:</b><br /> Promptly pay off debts, use credit responsibly, and regularly check
                    and correct credit reports to boost your creditworthiness.</li>
            </ol>
            Enhance your Quickloans Credit Score by settling outstanding debts promptly and enhancing your overall financial
            standing.</p>
        </div>
        <div class="card-footer"> <a href="javascript:void(0);" class="btn btn-secondary d-grid">Learn More</a> </div>
    </div>
    <div class="card custom-card" v-if="val.loanApplication.status == 'active'">
        <div class="card-body">
            <h6 class="card-title fw-semibold mb-3 border-bottom pb-3">Payment Methods</h6>
            <ul class="list-unstyled mb-0 pt-2 crm-deals-status">
                <li class="primary">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>Account Name</div>
                        <div class="fs-12 fw-bold">{{ val.payment_method.bankAccountName }}</div>
                    </div>
                </li>
                <li class="info">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>BSB</div>
                        <div class="fs-12 fw-bold">{{ val.payment_method.bsb }}</div>
                    </div>
                </li>
                <li class="warning">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>Account Number</div>
                        <div class="fs-12 fw-bold">{{ val.payment_method.bankAccountNumber }}</div>
                    </div>
                </li>
                <li class="success">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>PayID</div>
                        <div class="fs-12 fw-bold">{{ val.payment_method.payid }}</div>
                    </div>
                </li>
            </ul>
            <hr />
            <p class="card-text fs-7 mt-3 text-muted">
                You have the option to make manual payments towards your loan by either transferring funds to the specified
                BSB & Account Number or navigating to your statement and selecting the particular loan installment you
                intend to settle.
            </p>
        </div>
    </div>
</template>
<script>

export default {
    props: ['val']
}
</script>