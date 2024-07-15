@if($errors->any())
    <div class="alert-message alert alert-dismissible fade show mb-0 pb-0" role="alert">
        <strong class="text-danger">Whoops! Something went wrong!</strong>
        <ul class="list-unstyled">
            @foreach ($errors->all() as $error)
                <li class="text-danger">
                    <i class="bi bi-exclamation-triangle-fill pe-3"></i>
                    <span>{{ $error }}</span>
                </li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert-message alert alert-dismissible fade show mb-0 pb-0" role="alert">
        <div class="alert alert-danger alert-dismissible  d-flex align-items-center" role="alert">
            <div class="pe-3"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div>
                <strong>Error!</strong>
                {{ session('error') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('success'))
    <div>
        <div class="alert alert-success alert-dismissible  d-flex align-items-center" role="alert">
            <div class="pe-3"><i class="bi bi-check-circle-fill"></i></div>
            <div>
                <strong>{{ translator('Success') }}!</strong>
                {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif
