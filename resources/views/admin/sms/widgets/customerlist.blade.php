<div class="chat-info border">
    <div class="d-flex align-items-center justify-content-between w-100 p-3 border-bottom">
        <div>
            <h5 class="fw-semibold mb-0">Messages</h5>
        </div>
    </div>
    <div class="d-grid align-items-top p-3 border-bottom">
        <button class="btn btn-success d-flex align-items-center justify-content-center" data-bs-toggle="modal"
            data-bs-target="#mail-Compose">
            <i class="ri-add-circle-line fs-16 align-middle me-1"></i>Send New Message
        </button>
    </div>
    <div class="chat-search p-3 border-bottom">
        <div class="input-group">
            <input type="text" class="form-control bg-light border-0" placeholder="Search Chat"
                aria-describedby="button-addon2">
            <button aria-label="button" class="btn btn-light" type="button" id="button-addon2"><i
                    class="ri-search-line text-muted"></i></button>
        </div>

    </div>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active border-0 chat-users-tab" id="users-tab-pane" role="tabpanel"
            aria-labelledby="users-tab" tabindex="0">
            <ul class="list-unstyled mb-0 mt-2 chat-users-tab" id="chat-msg-scroll">
                <li class="pb-0">
                    <p class="text-muted fs-11 fw-semibold mb-2 op-7">Customer List</p>
                </li>
                @foreach ($groupedMessages as $k => $m)
                    <li class="checkforactive">
                        @if ($k)
                            <a href="javascript:void(0);" onclick="sms({{ $k }})">
                            @else
                                <a href="javascript:void(0);" onclick="sms(0)">
                        @endif
                        <div class="d-flex align-items-top">
                            <div class="me-1 lh-1">
                                <span class="avatar avatar-sm avatar-rounded bg-success-transparent fw-semibold me-2">
                                    {{ substr($loan_helper->getUserNameByMobile($k) ?? $k, 0, 1) }}
                                </span>

                            </div>
                            <div class="flex-fill">
                                <p class="mb-0 fw-semibold">
                                    {{ $loan_helper->getUserNameByMobile($k) ?? $k }}
                                </p>
                                <p class="fs-12 mb-0">
                                    <span class="chat-msg text-truncate">{{ $m->message }}</span>
                                    <span
                                        class="float-end text-muted fw-normal fs-11">{{ $loan_helper->formateDate($m->created_at) }}</span>
                                </p>
                            </div>
                        </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
