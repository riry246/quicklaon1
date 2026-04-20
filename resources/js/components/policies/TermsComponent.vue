<template>
    <div class="modal fade" id="terms">
        <div class="modal-dialog modal-dialog-centered text-center" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title text-center">{{ val.name }}
                    </h6>
                    <a aria-label="Close" class="btn-close" data-bs-dismiss="modal"></a>
                </div>
                <div class="modal-body text-start front-slider">
                    <div class="p-4 text-muted terms-conditions" id="terms-scroll"
                        style="overflow: scroll; max-height: 300px;" v-html="val.terms">

                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-danger" data-bs-dismiss="modal">Close</a>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['val'],

};
</script>