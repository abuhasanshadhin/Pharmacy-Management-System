@props([
    'colspan' => 5
])

<td colspan="{{ $colspan }}">
    <div class="my-5 text-center">
        <i class="bi bi-info-circle h3 text-muted"></i>
        <h5 class="text-muted">{{ translator('No data found') }}</h5>
    </div>
</td>
