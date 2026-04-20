<div class="col-xl-12">
    <div class="card custom-card">
        <div class="card-body p-0">
            <div class="p-3 border-bottom border-block-end-dashed d-flex align-items-center justify-content-between">
                <div>
                    <ul class="nav nav-tabs mb-0 tab-style-6 justify-content-start" id="myTab" role="tablist">
                        <!-- Adjusted tab IDs and labels -->
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="address-tab" data-bs-toggle="tab"
                                data-bs-target="#address-tab-pane" type="button" role="tab"
                                aria-controls="address-tab-pane" aria-selected="true">
                                Address
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="enquiry-tab" data-bs-toggle="tab"
                                data-bs-target="#enquiry-tab-pane" type="button" role="tab"
                                aria-controls="enquiry-tab-pane" aria-selected="false">
                                Enquiry
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="attribute-tab" data-bs-toggle="tab"
                                data-bs-target="#attribute-tab-pane" type="button" role="tab"
                                aria-controls="attribute-tab-pane" aria-selected="false">
                                Attribute
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="consdflt-tab" data-bs-toggle="tab"
                                data-bs-target="#consdflt-tab-pane" type="button" role="tab"
                                aria-controls="consdflt-tab-pane" aria-selected="false">
                                CONSDFLT
                            </button>
                        </li>
                        <!-- Add more nav-items for additional tabs -->
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="consccp-tab" data-bs-toggle="tab"
                                data-bs-target="#consccp-tab-pane" type="button" role="tab"
                                aria-controls="consccp-tab-pane" aria-selected="false">
                                CONSCCP
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="conssci-tab" data-bs-toggle="tab"
                                data-bs-target="#conssci-tab-pane" type="button" role="tab"
                                aria-controls="conssci-tab-pane" aria-selected="false">
                                CONSSCI
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="crtpubrc-tab" data-bs-toggle="tab"
                                data-bs-target="#crtpubrc-tab-pane" type="button" role="tab"
                                aria-controls="crtpubrc-tab-pane" aria-selected="false">
                                CRTPUBRC
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="crtpubjg-tab" data-bs-toggle="tab"
                                data-bs-target="#crtpubjg-tab-pane" type="button" role="tab"
                                aria-controls="crtpubjg-tab-pane" aria-selected="false">
                                CRTPUBJG
                            </button>
                        </li>
                        <!-- Add more nav-items for additional tabs -->
                    </ul>
                </div>
            </div>

            <div class="p-3">
                <div class="tab-content" id="myTabContent">
                    <!-- Adjusted tab IDs -->
                    <div class="tab-pane fade p-0 border-0 active show" id="address-tab-pane" role="tabpanel"
                        aria-labelledby="address-tab">
                        @include('admin.creditScore.widgets.address')
                    </div>
                    <div class="tab-pane fade p-0 border-0" id="enquiry-tab-pane" role="tabpanel"
                        aria-labelledby="enquiry-tab">
                        @include('admin.creditScore.widgets.enquiry')
                    </div>
                    <div class="tab-pane fade p-0 border-0" id="attribute-tab-pane" role="tabpanel"
                        aria-labelledby="attribute-tab">
                        @include('admin.creditScore.widgets.attribute')
                    </div>
                    <div class="tab-pane fade p-0 border-0" id="consdflt-tab-pane" role="tabpanel"
                        aria-labelledby="consdflt-tab">
                        @include('admin.creditScore.widgets.consdflt')
                    </div>
                    <!-- Add more tab-panes for additional tabs -->
                    <div class="tab-pane fade p-0 border-0" id="consccp-tab-pane" role="tabpanel"
                        aria-labelledby="consccp-tab">
                        @include('admin.creditScore.widgets.consccp')
                    </div>
                    <div class="tab-pane fade p-0 border-0" id="conssci-tab-pane" role="tabpanel"
                        aria-labelledby="conssci-tab">
                        @include('admin.creditScore.widgets.conssci')
                    </div>
                    <div class="tab-pane fade p-0 border-0" id="crtpubrc-tab-pane" role="tabpanel"
                        aria-labelledby="crtpubrc-tab">
                         @include('admin.creditScore.widgets.crtpubrc')
                    </div>
                    <div class="tab-pane fade p-0 border-0" id="crtpubjg-tab-pane" role="tabpanel"
                        aria-labelledby="crtpubjg-tab">
                         @include('admin.creditScore.widgets.crtpubjg')
                    </div>
                    <!-- Add more tab-panes for additional tabs -->
                </div>
            </div>
        </div>
    </div>
</div>
