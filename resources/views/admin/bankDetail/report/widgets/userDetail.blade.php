<div class="card custom-card">
    <div class="card-body text-dark">
        <div class="d-flex w-100">
            <div class="d-flex align-items-center justify-content-between w-100 flex-wrap">
                <div class="me-3">
                    <p class="fw-semibold fs-16 mb-0">{{ $user->email }}
                    </p>
                    <p class="text-muted mb-0">Email</p>
                </div>
                <div class="me-3">
                    <p class="fw-semibold fs-16 mb-0">{{ $user->mobile }}
                    </p>
                    <p class="text-muted mb-0">Phone</p>
                </div>
                <div class="me-3">
                    <p class="fw-semibold fs-16 mb-0">{{ $user->basiq_user_id }}
                    </p>
                    <p class="text-muted mb-0">User ID</p>
                </div>
            </div>
        </div>
    </div>
</div>
