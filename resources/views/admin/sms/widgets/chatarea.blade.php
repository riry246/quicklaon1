@inject('loan_helper', 'App\Http\Helpers\LoanHelper')
<div class="d-flex align-items-center p-2 border-bottom">
    <div class="me-2 lh-1">
        <span class="avatar avatar-sm avatar-rounded bg-success-transparent fw-semibold me-2">
            {{ substr($loan_helper->getUserName($user) ?? 'Unknown', 0, 1) }}
        </span>
    </div>
    <div class="flex-fill">
        <p class="mb-0 fw-semibold fs-14">
            <a href="javascript:void(0);" class="chatnameperson responsive-userinfo-open">
                {{ $loan_helper->getUserName($user) ?? 'Unknown' }}</a>
        </p>
        <p class="text-muted mb-0 chatpersonstatus">Message History</p>
    </div>

</div>
<div class="chat-content" id="main-chat-area-sms">
    <ul class="list-unstyled">
        @foreach ($groupedMessages as $k => $m)
            <li class="chat-day-label ">
                <span class="text-success">{{ $loan_helper->formateDate($k) }}</span>
            </li>
            @foreach ($m as $c)
                <li class="chat-item-end">
                    <div class="chat-list-inner">
                        <div class="me-3">
                            <span class="chatting-user-info">
                                <span class="msg-sent-time"><span
                                        class="chat-read-mark align-middle d-inline-flex"></span>{{ $loan_helper->formatetime($c->created_at) }}</span>
                                {{ $loan_helper->getUserfirstName($c->created_by) ?? 'Cashfaster' }}
                            </span>
                            <div class="main-chat-msg mb-2">
                                <div>
                                    <p class="mb-0 fw-semibold mb-2">{{ $c->subject }}</p>
                                    <p class="mb-0 border-bottom border-top pt-2 mb-2 pb-2">{!! strip_tags($c->content) !!}</p>
                                    <span class="msg-sent-time fs-12 text-muted">
                                        <span class="chat-read-mark align-middle d-inline-flex ">
                                            @if ($c->type == 'mail')
                                                <i class="ri-mail-line"></i>
                                            @elseif($c->type == 'inapp')
                                                <i class="ri-smartphone-line"></i>
                                            @else
                                                <i class="ri-phone-line"></i>
                                            @endif
                                        </span>
                                        {{ ucfirst($c->type) }} notification

                                    </span>
                                </div>
                            </div>
                            @if ($c->status == 'failed')
                                <span class="chatting-user-info text-danger ">
                                    <span class="msg-sent-time text-danger">
                                        <span class="chat-read-mark align-middle d-inline-flex text-danger">
                                            <i class="ri-close-circle-fill text-danger"></i>
                                        </span>
                                        Sending failed
                                    </span>

                                </span>
                            @endif
                        </div>
                        <div class="chat-user-profile">
                            <span class="avatar avatar-md avatar-rounded bg-success-transparent fw-semibold me-2">
                                @if ($c->created_by)
                                    {{ substr($loan_helper->getUserName($c->created_by) ?? 'Cashfaster', 0, 1) }}
                                @else
                                    CF
                                @endif
                            </span>
                        </div>
                    </div>
                </li>
            @endforeach
        @endforeach
    </ul>
</div>
@if ($user > 0)
    <div class="chat-footer">
        <input class="form-control" name="subject" placeholder="Type your subject here..." type="text"
            id="subject">
        <input class="form-control mx-2" name="content" placeholder="Type your message here..." type="text"
            id="msgcontent">
        <select class="form-control" data-trigger name="type" id="type">
            <option value="inapp">Inapp Notificaiton</option>
            <option value="sms">SMS Notificaiton</option>
            <option value="mail">Mail Notificaiton</option>
        </select>
        <button type="submit" aria-label="anchor" class="btn btn-primary mx-2 btn-icon btn-send"
            href="javascript:void(0)" onclick="submit({{ $user }})">
            <i class="ri-send-plane-2-line"></i>
        </button>

    </div>
@endif
