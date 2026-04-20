<template>
    <div class="modal fade" id="idVerification" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">ID Verification</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-4">
                    <div class="col-xl-12" v-if="tab == 'all'">
                        <ul class="list-unstyled crm-top-deals mb-0">
                            <li class="border-bottom border-block-end-dashed pb-3" @click="changeTab('passport')">
                                <div class="d-flex align-items-top flex-wrap">
                                    <span class="avatar avatar-md avatar-rounded bg-primary mx-2"> <i
                                            class='bx bx-id-card'></i> </span>
                                    <div class="flex-fill">
                                        <p class="fw-semibold mb-0 text-dark">Passport</p><span
                                            class="text-muted fs-12">Verify your passport detail</span>
                                    </div>
                                    <button
                                        class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light">
                                        <i class="ti ti-arrow-right fs-16"></i> </button>
                                </div>
                            </li>
                            <li class="border-bottom border-block-end-dashed pb-3" @click="changeTab('driverLicence')">
                                <div class="d-flex align-items-top flex-wrap">
                                    <span class="avatar avatar-md avatar-rounded bg-primary mx-2"> <i
                                            class='bx bx-id-card'></i> </span>
                                    <div class="flex-fill">
                                        <p class="fw-semibold mb-0 text-dark">Driver Licence</p><span
                                            class="text-muted fs-12">Verify your driving licence detail</span>
                                    </div>
                                    <button
                                        class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light">
                                        <i class="ti ti-arrow-right fs-16"></i> </button>
                                </div>
                            </li>
                            <li class="border-bottom border-block-end-dashed pb-3" @click="changeTab('medicare')">
                                <div class="d-flex align-items-top flex-wrap">
                                    <span class="avatar avatar-md avatar-rounded bg-primary mx-2"> <i
                                            class='bx bx-id-card'></i> </span>
                                    <div class="flex-fill">
                                        <p class="fw-semibold mb-0 text-dark">Medicare Card</p><span
                                            class="text-muted fs-12">Verify your medicare card detail</span>
                                    </div>
                                    <button
                                        class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light">
                                        <i class="ti ti-arrow-right fs-16"></i> </button>
                                </div>
                            </li>
                        </ul>

                    </div>

                    <div class="col-xl-12" v-else-if="tab == 'passport'">
                        <div class="row">
                            <div
                                class="d-flex align-items-top flex-wrap border-bottom border-block-end-dashed pb-3 mb-2">
                                <button
                                    class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light me-2"
                                    @click="changeTab('all')">
                                    <i class="ti ti-arrow-left fs-16"></i> </button>
                                <div class="flex-fill">
                                    <p class="fw-semibold mb-0 text-dark">Passport</p><span
                                        class="text-muted fs-12">Verify your passport detail</span>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-2">
                                <div class="card custom-card border border-info">
                                    <div class="card-body py-5">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <h6 class="fs-15">Passport</h6>
                                                <img class="rounded float-start"
                                                    src="https://spruko.com/demo/ynex/dist/assets/images/faces/8.jpg"
                                                    alt="...">
                                            </div>
                                            <div style="width:100%">
                                                <div class="row">
                                                    <div class="col-xl-6">
                                                        <p class="mb-0 text-muted fs-10">Family Name:</p>
                                                        <p class="card-text text-dark mb-1 fs-14 fw-semibold">{{
                                                            passport.FamilyName ? passport.FamilyName : 'XXXXXX' }}</p>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <p class="mb-0 text-muted fs-10">Document No:</p>
                                                        <p class="card-text text-dark mb-1 fs-14 fw-semibold">{{
                                                            passport.TravelDocumentNumber ?
                                                            passport.TravelDocumentNumber : '*********' }}
                                                        </p>
                                                    </div>

                                                </div>
                                                <p class="mb-0 text-muted fs-10">Given Name:</p>
                                                <p class="card-text text-dark mb-1 fs-14 fw-semibold">{{
                                                    passport.GivenName ? passport.GivenName : 'XXXXXX' }}</p>
                                                <div class="row">
                                                    <div class="col-xl-6">
                                                        <p class="mb-0 text-muted fs-10">Date of Birth:</p>
                                                        <p class="card-text text-dark mb-1 fs-14 fw-semibold">
                                                            {{ passport.BirthDate ? formatDate(passport.BirthDate) :
                                                            'XX-XX-XXXX' }}
                                                        </p>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <p class="mb-0 text-muted fs-10">Gender:</p>
                                                        <p class="card-text text-dark mb-1 fs-14 fw-semibold">{{
                                                            passport.Gender ? passport.Gender : 'XX' }}</p>
                                                    </div>
                                                </div>
                                                <p class="mb-0 text-muted fs-10">Expiry Date:</p>
                                                <p class="card-text text-dark mb-1 fs-14 fw-semibold">{{
                                                    passport.ExpiryDate ? formatDate(passport.ExpiryDate) : 'XX XX XXXX'
                                                    }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-2">
                            <div class="alert alert-solid-danger alert-dismissible fs-15 fade show mb-4" v-if="error">
                                {{ error }}
                            </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mt-2">
                                <p class="mb-2 text-muted">Given Name:</p>
                                <input type="text" class="form-control" v-model="passport.GivenName">
                                <span class="error_msg" role="alert" v-if="errors.GivenName">{{ errors.GivenName
                                    }}</span>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mt-2">
                                <p class="mb-2 text-muted">Family Name:</p>
                                <input type="text" class="form-control" v-model="passport.FamilyName">
                                <span class="error_msg" role="alert" v-if="errors.FamilyName">{{
                                    errors.FamilyName
                                    }}</span>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-4 col-sm-12 mt-2">
                                <p class="mb-2 text-muted">Birth Date:</p>
                                <input type="date" class="form-control" v-model="passport.BirthDate">
                                <span class="error_msg" role="alert" v-if="errors.BirthDate">{{ errors.BirthDate
                                    }}</span>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-2">
                                <p class="mb-2 text-muted">Passport Number:</p>
                                <input type="text" class="form-control" v-model="passport.TravelDocumentNumber">
                                <span class="error_msg" role="alert" v-if="errors.TravelDocumentNumber">{{
                                    errors.TravelDocumentNumber }}</span>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mt-2">
                                <p class="mb-2 text-muted">Gender:</p>
                                <select class="form-control" v-model="passport.Gender">
                                    <option value="m">Male</option>
                                    <option value="f">Female</option>
                                    <option value="x">Other</option>
                                    <option value="">Prefer not to say</option>
                                </select>
                                <span class="error_msg" role="alert" v-if="errors.Gender">{{ errors.Gender
                                    }}</span>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mt-2">
                                <p class="mb-2 text-muted">ExpiryDate:</p>
                                <input type="date" class="form-control" v-model="passport.ExpiryDate">
                                <span class="error_msg" role="alert" v-if="errors.ExpiryDate">{{
                                    errors.ExpiryDate
                                    }}</span>
                            </div>
                            <div class="text-center my-4">
                                <button class="btn btn-primary btn-loader mt-3" v-if="loading"> <span
                                        class="me-2">Loading</span> <span class="loading"><i
                                            class="ri-loader-2-fill fs-16"></i></span> </button>
                                <button v-else class="btn btn-primary btn-wave mt-3" @click="verifyPassport()">Verify
                                    Passport</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12" v-else-if="tab == 'driverLicence'">
                        <div class="row">
                            <div
                                class="d-flex align-items-top flex-wrap border-bottom border-block-end-dashed pb-3 mb-2">
                                <button
                                    class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light me-2"
                                    @click="changeTab('all')">
                                    <i class="ti ti-arrow-left fs-16"></i> </button>
                                <div class="flex-fill">
                                    <p class="fw-semibold mb-0 text-dark">Driver Licence</p><span
                                        class="text-muted fs-12">Verify your driving licence detail</span>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-2 ">
                                <div class="card custom-card border border-info">
                                    <div class="card-body pt-2 pb-5">
                                        <div class="pb-2 text-center">
                                            <h6 class="fs-15 p-0 m-0">Driving Licence</h6>
                                            <span class="text-muted fs-12 m-0 p-0">{{ driverLicence.StateOfIssue ?
                                                driverLicence.StateOfIssue : 'State' }} Australia</span>
                                        </div>
                                        <div class="row pb-3 ">
                                            <div class="col-xl-6">
                                                <p class="card-text text-dark mb-1 fs-14 fw-semibold">
                                                    {{ driverLicence.GivenName ? driverLicence.GivenName : 'XXXXXX' }}
                                                    {{ driverLicence.MiddleName ? driverLicence.MiddleName : '' }}<br />
                                                    {{ driverLicence.FamilyName ? driverLicence.FamilyName : 'XXXXXX' }}
                                                </p>
                                            </div>
                                            <div class="col-xl-6 text-end">
                                                <p class="mb-0 text-muted fs-10">Licence No:</p>
                                                <p class="card-text text-dark mb-1 fs-14 fw-semibold">{{
                                                    driverLicence.LicenceNumber ? driverLicence.LicenceNumber :
                                                    '*********' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center">

                                            <div style="width:100%">
                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <p class="mb-0 text-muted fs-10">Address</p>
                                                        <p class="card-text text-dark mb-1 fs-14 fw-semibold">
                                                            Not Required
                                                        </p>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <p class="mb-0 text-muted fs-10">Licence Expiry</p>
                                                        <p class="card-text text-dark mb-1 fs-14 fw-semibold">
                                                            Not Required
                                                        </p>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <p class="mb-0 text-muted fs-10">Date of Birth:</p>
                                                        <p class="card-text text-dark mb-1 fs-14 fw-semibold">
                                                            {{ passport.BirthDate ? formatDate(driverLicence.BirthDate)
                                                            : 'XX-XX-XXXX' }}
                                                        </p>
                                                    </div>
                                                    <div class="col-xl-12">
                                                        <p class="mb-0 text-muted fs-10">Licence Type</p>
                                                        <p class="card-text text-dark mb-1 fs-14 fw-semibold">
                                                            Not Required
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ms-3">

                                                <img class="rounded float-start"
                                                    src="https://spruko.com/demo/ynex/dist/assets/images/faces/8.jpg"
                                                    alt="...">
                                                <div class="col-xl-12 text-center">
                                                    <p class="card-text text-dark mb-1 fs-14 fw-semibold">
                                                        {{ driverLicence.CardNumber ? driverLicence.CardNumber :
                                                        '*********' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-2">
                            <div class="alert alert-solid-danger alert-dismissible fs-15 fade show mb-4" v-if="error">
                                {{ error }}
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <p class="mb-2 text-muted">Birth Date: </p>
                                    <input type="date" class="form-control" v-model="driverLicence.BirthDate">
                                    <span class="error_msg" role="alert" v-if="errors.BirthDate">{{ errors.BirthDate
                                        }}</span>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                    <p class="mb-2 text-muted">Give Name:</p>
                                    <input type="text" class="form-control" v-model="driverLicence.GivenName">
                                    <span class="error_msg" role="alert" v-if="errors.GivenName">{{ errors.GivenName
                                        }}</span>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                    <p class="mb-2 text-muted">Middle Name:</p>
                                    <input type="text" class="form-control" v-model="driverLicence.MiddleName">
                                    <span class="error_msg" role="alert" v-if="errors.MiddleName">{{
                                        errors.MiddleName
                                        }}</span>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                    <p class="mb-2 text-muted">Family Name:</p>
                                    <input type="text" class="form-control" v-model="driverLicence.FamilyName">
                                    <span class="error_msg" role="alert" v-if="errors.FamilyName">{{
                                        errors.FamilyName
                                        }}</span>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                    <p class="mb-2 text-muted">Licence Number:</p>
                                    <input type="text" class="form-control" v-model="driverLicence.LicenceNumber">
                                    <span class="error_msg" role="alert" v-if="errors.LicenceNumber">{{
                                        errors.LicenceNumber
                                        }}</span>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                    <p class="mb-2 text-muted">State Of Issue:</p>
                                    <select class="form-control" v-model="driverLicence.StateOfIssue">
                                        <option value="NSW">NSW</option>
                                        <option value="ACT">ACT</option>
                                        <option value="SA">SA</option>
                                        <option value="WA">WA</option>
                                        <option value="TAS">TAS</option>
                                        <option value="NT">NT</option>
                                        <option value="QLD">QLD</option>
                                        <option value="VIC">VIC</option>

                                    </select>
                                    <span class="error_msg" role="alert" v-if="errors.StateOfIssue">{{
                                        errors.StateOfIssue
                                        }}</span>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                    <p class="mb-2 text-muted">Card Number:</p>
                                    <input type="text" class="form-control" v-model="driverLicence.CardNumber">
                                    <span class="error_msg" role="alert" v-if="errors.CardNumber">{{
                                        errors.CardNumber
                                        }}</span>
                                </div>

                                <div class="text-center my-4">
                                <button class="btn btn-primary btn-loader btn-wave mt-3" v-if="loading"> <span
                                        class="me-2">Loading</span> <span class="loading"><i
                                            class="ri-loader-2-fill fs-16"></i></span> </button>
                                <button v-else class="btn btn-primary btn-wave mt-3"
                                    @click="verifyDriverLicence()">Verify
                                    Driver Licence</button>
                                        </div>
                            </div>


                        </div>
                    </div>
                    <div class="col-xl-12" v-else-if="tab == 'medicare'">
                        <div class="row">
                            <div
                                class="d-flex align-items-top flex-wrap border-bottom border-block-end-dashed pb-3 mb-2">
                                <button
                                    class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light me-2"
                                    @click="changeTab('all')">
                                    <i class="ti ti-arrow-left fs-16"></i> </button>
                                <div class="flex-fill">
                                    <p class="fw-semibold mb-0 text-dark">Medicare Card</p><span
                                        class="text-muted fs-12">Verify your medicare card detail</span>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-2 ">
                                <div class="card custom-card border border-info" v-bind:class="{
                        'bg-success': medicare.CardType === 'G',
                        'bg-warning': medicare.CardType === 'Y',
                        'bg-secondary': medicare.CardType === 'B'
                    }">
                                    <div class="card-body pt-2 pb-5">
                                        <div class="pb-2 text-center">
                                            <h6 class="fs-20 p-0 m-0 text-end ">Medicare</h6>
                                        </div>
                                        <div class="pb-5 text-center">
                                            <h6 class="fs-30 p-0 m-0  ">
                                                {{ medicare.CardNumber ? medicare.CardNumber.replace(/(\d{4})(\d{5})(\d{1})/, '$1 $2 $3') : 'XXXX XXXXX X' }}
                                            </h6>
                                        </div>

                                        <div class="d-flex align-items-center">
                                            <div style="width:100%">
                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <p class="card-text text-dark mb-1 fs-14 fw-semibold">
                                                            {{ medicare.IndividualReferenceNumber ?
                                                            medicare.IndividualReferenceNumber : 'X' }}
                                                            {{ medicare.FullName1 ? medicare.FullName1 : 'XXXX XXXXXXX'
                                                            }}
                                                        </p>
                                                    </div>
                                                    <div class="col-xl-12 text-end">
                                                        <p class="card-text text-dark mb-1 fs-14 fw-semibold">
                                                            Valid To {{ medicare.CardExpiry ?
                                                            formatDate(medicare.CardExpiry) : 'XXXX XXXXXXX' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-2">
                            <div class="alert alert-solid-danger alert-dismissible fs-15 fade show mb-4" v-if="error">
                                {{ error }}
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 mt-2">
                                    <p class="mb-2 text-muted">Full Name:</p>
                                    <input type="text" class="form-control" v-model="medicare.FullName1">
                                    <span class="error_msg" role="alert" v-if="errors.FullName1">{{ errors.FullName1
                                        }}</span>

                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                    <input type="button" class="form-control btn-secondary mt-4" id="input-new"
                                        value="Add Full Name" @click="addFullName">
                                </div>
                                <div v-for="(name, index) in additionalFullNames" :key="index" class="row mt-2">
                                    <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
                                        <p class="mb-2 text-muted">Full Name {{ index + 2 }}:</p>
                                        <input type="text" class="form-control" v-model="additionalFullNames[index]"
                                            @input="updateMedicare(index)">
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                                        <input type="button" class="form-control btn-danger mt-4" value="Remove"
                                            @click="removeFullName(index)">
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-2">
                                    <p class="mb-2 text-muted">Birth Date:</p>
                                    <input type="date" class="form-control" v-model="medicare.BirthDate">
                                    <span class="error_msg" role="alert" v-if="errors.BirthDate">{{ errors.BirthDate
                                        }}</span>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-2">
                                    <p class="mb-2 text-muted">Card Number:</p>
                                    <input type="text" class="form-control" v-model="medicare.CardNumber">
                                    <span class="error_msg" role="alert" v-if="errors.CardNumber">{{
                                        errors.CardNumber }}</span>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mt-2">
                                    <p class="mb-2 text-muted">Card Type:</p>
                                    <select class="form-control" v-model="medicare.CardType">
                                        <option value="Y">Yellow</option>
                                        <option value="G">Green</option>
                                        <option value="B">Blue</option>
                                    </select>
                                    <span class="error_msg" role="alert" v-if="errors.CardType">{{ errors.CardType
                                        }}</span>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mt-2">
                                    <p class="mb-2 text-muted">ExpiryDate:</p>
                                    <input type="date" class="form-control" v-model="medicare.CardExpiry">
                                    <span class="error_msg" role="alert" v-if="errors.CardExpiry">{{
                                        errors.CardExpiry
                                        }}</span>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-2">
                                    <p class="mb-2 text-muted">Individual Reference Number:</p>
                                    <input type="text" class="form-control"
                                        v-model="medicare.IndividualReferenceNumber">
                                    <span class="error_msg" role="alert" v-if="errors.IndividualReferenceNumber">{{
                                        errors.IndividualReferenceNumber
                                        }}</span>

                                </div>
                                <div class="text-center my-4">
                                <button class="btn btn-primary btn-loader mt-3" v-if="loading"> <span
                                        class="me-2">Loading</span> <span class="loading"><i
                                            class="ri-loader-2-fill fs-16"></i></span> </button>
                                <button v-else class="btn btn-primary btn-wave mt-3" @click="verifyMedicare()">Verify
                                    Medicare</button>
                                </div>
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
            user_id: sessionStorage.getItem('user_id'),
            tab: 'all',
            passport: {
                type: 'passport',
                BirthDate: '',
                GivenName: '',
                FamilyName: '',
                TravelDocumentNumber: '',
                Gender: '',
                ExpiryDate: '',
            },
            centerlink: {
                type: 'centerlink',
                BirthDate: '',
                Name: '',
                CardType: '',
                CardExpiry: '',
                CustomerReferenceNumber: '',
            },
            citizenship: {
                type: 'citizenship',
                BirthDate: '',
                GivenName: '',
                MiddleName: '',
                FamilyName: '',
                StockNumber: '',
                AcquisitionDate: '',
            },
            driverLicence: {
                type: 'driverLicence',
                BirthDate: '',
                GivenName: '',
                MiddleName: '',
                FamilyName: '',
                LicenceNumber: '',
                StateOfIssue: '',
                CardNumber: '',
            },
            medicare: {
                type: 'medicare',
                BirthDate: '',
                FullName1: '',
                additionalFullNames: [],
                CardExpiry: '',
                CardNumber: '',
                CardType: '',
                IndividualReferenceNumber: '',
            },
            errors: [],
            error: '',
            msg: '',
            loading: '',
            additionalFullNames: [],
        };
    },
    methods: {
        verifyPassport() {
            this.errors = [];
            if (!this.passport.BirthDate) {
                this.errors.BirthDate = 'This field is required';
            }
            if (!this.passport.GivenName) {
                this.errors.GivenName = 'This field is required';
            }
            if (!this.passport.FamilyName) {
                this.errors.FamilyName = 'This field is required';
            }
            if (!this.passport.TravelDocumentNumber) {
                this.errors.TravelDocumentNumber = 'This field is required';
            }
            if (!this.passport.ExpiryDate) {
                this.errors.ExpiryDate = 'This field is required';
            }

            const isValid = Object.keys(this.errors).length === 0;

            if (isValid) {
                this.submitForm(this.passport);
            } else {
                //this.submitForm(this.passport);
                console.log('Form is invalid. Please correct the errors.');
            }
        },
        verifyCenterlink() {
            this.errors = [];
            if (!this.centerlink.BirthDate) {
                this.errors.BirthDate = 'This field is required';
            }
            if (!this.centerlink.Name) {
                this.errors.Name = 'This field is required';
            }
            if (!this.centerlink.CardType) {
                this.errors.CardType = 'This field is required';
            }
            if (!this.centerlink.CardExpiry) {
                this.errors.CardExpiry = 'This field is required';
            }
            if (!this.centerlink.CustomerReferenceNumber) {
                this.errors.CustomerReferenceNumber = 'This field is required';
            }

            const isValid = Object.keys(this.errors).length === 0;

            if (isValid) {
                this.submitForm(this.centerlink);
            } else {
                console.log('Form is invalid. Please correct the errors.');
            }
        },
        verifyCitizenship() {
            this.errors = [];
            if (!this.citizenship.BirthDate) {
                this.errors.BirthDate = 'This field is required';
            }
            if (!this.citizenship.GivenName) {
                this.errors.GivenName = 'This field is required';
            }
            if (!this.citizenship.FamilyName) {
                this.errors.FamilyName = 'This field is required';
            }
            if (!this.citizenship.StockNumber) {
                this.errors.StockNumber = 'This field is required';
            }
            if (!this.citizenship.AcquisitionDate) {
                this.errors.AcquisitionDate = 'This field is required';
            }

            const isValid = Object.keys(this.errors).length === 0;

            if (isValid) {
                this.submitForm(this.centerlink);
            } else {
                console.log('Form is invalid. Please correct the errors.');
            }
        },
        verifyDriverLicence() {
            this.errors = [];
            if (!this.driverLicence.BirthDate) {
                this.errors.BirthDate = 'This field is required';
            }
            if (!this.driverLicence.GivenName) {
                this.errors.GivenName = 'This field is required';
            }
            if (!this.driverLicence.FamilyName) {
                this.errors.FamilyName = 'This field is required';
            }
            if (!this.driverLicence.LicenceNumber) {
                this.errors.LicenceNumber = 'This field is required';
            }
            if (!this.driverLicence.StateOfIssue) {
                this.errors.StateOfIssue = 'This field is required';
            }
            if (!this.driverLicence.CardNumber) {
                this.errors.CardNumber = 'This field is required';
            }

            const isValid = Object.keys(this.errors).length === 0;

            if (isValid) {
                this.submitForm(this.driverLicence);
            } else {
                console.log('Form is invalid. Please correct the errors.');
            }
        },
        verifyMedicare() {
            this.errors = [];
            if (!this.medicare.BirthDate) {
                this.errors.BirthDate = 'This field is required';
            }
            if (!this.medicare.FullName1) {
                this.errors.FullName1 = 'This field is required';
            }
            if (!this.medicare.CardExpiry) {
                this.errors.CardExpiry = 'This field is required';
            }
            if (!this.medicare.CardNumber) {
                this.errors.CardNumber = 'This field is required';
            }
            if (!this.medicare.CardType) {
                this.errors.CardType = 'This field is required';
            }
            if (!this.medicare.IndividualReferenceNumber) {
                this.errors.IndividualReferenceNumber = 'This field is required';
            }


            const isValid = Object.keys(this.errors).length === 0;

            if (isValid) {
                this.submitForm(this.medicare);
            } else {
                console.log('Form is invalid. Please correct the errors.');
            }
        },

        submitForm(value) {
            this.loading = true;
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
            axios.post(this.baseUrl + 'api/auth/idVerify', { user_id: this.user_id, value })
                .then(response => {
                    this.error = '';
                    this.msg = response.data.success;
                    console.log(response);
                    if (!this.msg) {
                        this.error = response.data.error_message;
                    } else {
                        location.reload();
                    }
                })
                .catch(error => {
                    this.error = error;
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        addFullName() {
            this.additionalFullNames.push(''); // Add a new empty input field
        },
        removeFullName(index) {
            this.additionalFullNames.splice(index, 1); // Remove input field at specified index
            this.medicare.additionalFullNames.splice(index, 1); // Remove input field at specified index
            this.$delete(this.medicare.additionalFullNames, index);
        },
        updateMedicare(index) {
            const propertyName = `FullName${index + 2}`;
            this.medicare.additionalFullNames[index] = this.additionalFullNames[index];
        },
        changeTab(name) {
            this.tab = name;
            this.error = '';

        },
        formatDate(date) {
            const dateFormat = new Date(date);
            const options = { day: '2-digit', month: 'short', year: 'numeric' };
            return dateFormat.toLocaleDateString('en-AU', options);
        }
    }
}
</script>