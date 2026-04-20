<template>
    <div class="modal fade" id="DocumentVerification" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Document Verification</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-4">
                    <div class="col-xl-12">
                        <div class="alert alert-solid-danger alert-dismissible fs-15 fade show mb-4" v-if="error">
                            {{ error }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                                    class="bi bi-x"></i></button>
                        </div>
                        <div class="text-muted active show">
                            <form :action="url" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="user_id" :value="val.user.id" />
                                <input type="hidden" name="app_id" :value="val.loanApplication.id" />
                                <input type="hidden" name="mode" value="web" />
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-2" v-for="doc in val.document">
                                    <p class="mb-2 text-muted">Upload {{ doc.document_type }}:</p>
                                    <input type="file" :name="doc.document_type" class="form-control" />
                                </div>
                                <button class="btn btn-primary btn-wave mt-3" @click="upload()">Upload</button>
                            </form>
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
            user_id: this.val.user.id,
            errors: [],
            error: '',
            msg: '',
            formData: [],
            url: '',
        };
    },
    mounted() {
        this.url = this.baseUrl + 'api/auth/uploaddoc';
    },
    methods: {
        getDoc() {

        },
        upload(value) {
            console.log(this.selectedImages);
        }
    }
}
</script>