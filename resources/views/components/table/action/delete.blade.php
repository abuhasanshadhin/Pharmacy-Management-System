@props([
'route' => null,
'id' => 'edit-specific-row',
'text' => '',
'icon' => 'bi bi-pencil'
])

<form action="" onclick="return confirm('Are you sure?')" class="d-inline-block" method="POST">
    <input type="hidden" name="_token" value="VuStOSNsDd3YzrhduCyo7ttEj5WCGHEmCdD8Vx2h" autocomplete="off">                                    <input type="hidden" name="_method" value="DELETE">                                    <button type="submit" class="btn btn-sm btn-outline-danger">
        <span></span>
        <i class="bi bi-trash"></i>

    </button>
</form>
