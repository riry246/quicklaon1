    <div class="row">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Message</div>
            </div>
            <div class="card-body">
                <div class="btn-list">
                    <div class="d-grid gap-2 mb-4">
                        <button class="btn btn-primary btn-wave" data-bs-toggle="modal" data-bs-target="#send-message">Send
                            Message</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-lg fade" id="send-message" tabindex="-1" aria-labelledby="mail-ComposeLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="mail-ComposeLabel">Send Message</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <form action="{{ route('message.send.all') }}" method="post" enctype="multipart/form-data"
                        class="row g-3 mt-0">
                        @csrf
                        <div class="row">
                            <div class="col-xl-12 mb-2">
                                <label for="Subject" class="form-label">Method</label>
                                <select class="form-control" data-trigger name="type" id="choices-single-default">
                                    <option value="inapp">Inapp Notificaiton</option>
                                    <option value="sms">SMS Notificaiton</option>
                                    <option value="mail">Mail Notificaiton</option>
                                </select>
                            </div>
                            <div class="col-xl-12 mb-2">
                                <label for="Subject" class="form-label">Subject</label>
                                <input type="text" name="subject" class="form-control" id="Subject"
                                    placeholder="Subject">
                            </div>

                            <div class="col-xl-12">
                                <label class="col-form-label">Content :</label>
                                <textarea name="content" id="content" class="form-control" rows="15"></textarea>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send</button>
                </div>
                </form>
            </div>
        </div>
    </div>
