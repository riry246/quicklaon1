<ul class="nav nav-tabs mb-3 border-0" role="tablist">
    <li class="nav-item" role="presentation"> <a class="nav-link active" data-bs-toggle="tab" role="tab"
            href="#summary-dropdown" aria-selected="true">

            <i class="bi bi-graph-up-arrow mx-2"></i>
            Summary</a> </li>
    <li class="nav-item" role="presentation"> <a class="nav-link" data-bs-toggle="tab" role="tab"
            href="#income-dropdown" aria-selected="false" tabindex="-1">
            <i class="bi bi-wallet mx-2"></i>

            Income</a> </li>
    <li class="nav-item dropdown" role="presentation"> <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
            href="javascript:void(0);" role="button" aria-expanded="false">
            <i class="bi bi-coin mx-2"></i>
            Expenses</a>
        <ul class="dropdown-menu" style="">
            @foreach ($expenses as $k => $v)
                <li><a class="dropdown-item" data-bs-toggle="tab" role="tab"
                        href="#{{ str_replace(' ', '', strtolower($k)) }}-dropdown" aria-selected="false"
                        tabindex="-1">{{ $loan_helper->beautifyVariableName($k) }}</a></li>
            @endforeach
        </ul>
    </li>
</ul>
