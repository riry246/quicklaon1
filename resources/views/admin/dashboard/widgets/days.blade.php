<div class="btn-group" role="group" aria-label="Basic example">
    @foreach ([1, 7, 14, 30, 60, 90, 120] as $days)
        <button onclick="showMetric('{{ $url }}',{{ $days }})" type="button"
            class="btn btn-{{ $days == $period ? 'primary' : 'primary-light text-dark' }} btn-sm btn-wave">{{ $days }}D</button>
    @endforeach
</div>
