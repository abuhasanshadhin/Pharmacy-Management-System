@extends('layout.app')
@php
    $breadcrumbs = [
           ['text' => 'App Settings'],
           ['text' => 'Roles','link' => route('role.index')],
           ['text' => 'Create Role']
      ];
@endphp
@section('content')
    <x-container
        title="Roles"
        :breadcrumb="$breadcrumbs"
        :button="true"
        url="role.index"
        btn_title="Back"
    >
        <x-form class="row" :inputsInline="true" action="{{ route('role.store') }}" method="post">
            <x-form.input
                class="col-lg-6"
                label="Role Name"
                name="name"
                placeholder="Ex:admin"
            ></x-form.input>
            <div class="clearfix my-3 px-5">
                <div class="float-end">
                    <input type="checkbox" id="checked-all" class="form-check-input">
                    <label for="checked-all" class="form-check-label">{{ translator('Check All') }}</label>
                </div>
            </div>
            <div class="row pe-4">
                @foreach($permissions as $module => $permission)
                    <div class="rounded-3 py-1 mb-2 mx-1">
                        <div class="row">
                            <div class="col-lg-2 text-end">
                                <h6 class="mb-0">{{ $module }}</h6>
                            </div>
                            <div class="col-lg-10">
                                <div class="row">
                                    @foreach($permission as $action)
                                        <div class="col-lg-3 mb-1">
                                            <div class="form-check">
                                                <input class="form-check-input action-check" name="permissions[{{$action['id']}}]" type="checkbox" value="{{$action['name']}}"
                                                       id="{{ strtolower($module) }}-{{ strtolower($action['label']) }}">
                                                <label class="form-check-label" for="{{ strtolower($module) }}-{{ strtolower($action['label']) }}">
                                                    {{ $action['label'] }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-3 row justify-content-end">
                <div class="col-lg-2 text-end">
                    <x-form.button type="submit" label="Submit"></x-form.button>
                </div>
            </div>
        </x-form>
    </x-container>
@endsection
@push('scripts')
    <script>
        let allChecked = document.getElementById('checked-all');
        let allAction = document.querySelectorAll('.action-check');
        allChecked.addEventListener('change', function() {
            allAction.forEach((item) => {
                item.checked = allChecked.checked;
            })
        })
    </script>
@endpush
