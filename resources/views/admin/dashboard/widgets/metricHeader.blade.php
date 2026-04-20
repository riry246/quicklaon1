<div
    class="d-flex align-items-center justify-content-between w-100 flex-wrap p-3  border-bottom border-block-end-dashed">
    @foreach ($metricshead as $k => $v)
        <div class="me-3">
            <p class="text-muted mb-0">{{ $dashboard_helper->removeSlug(ucfirst($k)) }}</p>
            @if ($k == 'period')
                <p class="text-danger fw-semibold ">{{ $v }} Days</p>
            @else<p class="fw-normal">
                    {{ $v['total'] }}</p>
            @endif
        </div>
    @endforeach
</div>
