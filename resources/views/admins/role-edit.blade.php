@extends('layouts.app')

@section('title', 'Roles')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title">Edit {{ $info->role_name }}</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.role.index') }}">Roles</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $info->role_name }}</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form action="{{ url('admin/role/'.$info->role_id) }}" method="POST" id="editForm">
                    @csrf
                    <input type="hidden" name="role_id" value="{{ $info->role_id }}" />
                    <div class="form-group">
                        <label class="font-weight-bold" for="role_name">Role Name</label>
                        <input type="text" class="form-control" id="role_name" name="role_name" placeholder="Role Name" value="{{ $info->role_name }}">
                    </div>
                    <label class="font-weight-bold">Permissions</label>
                    @foreach($menus as $row)
                    <div class="custom-control custom-checkbox">
                        <input class="form-check-input" type="checkbox" name="menu_id[]" value="{{ $row->menu_id }}" id="check-{{ $row->menu_id }}" @if(in_array($row->menu_id, $menu_id)) checked @endif />
                        <label class="form-check-label" for="check-{{ $row->menu_id }}">
                            {{ $row->menu_name }}
                        </label>
                    </div>
                    @endforeach
                </form>
            </div>
            <div class="card-footer bg-white">
                <button class="btn btn-primary float-right" type="submit" form="editForm">Submit</button>
                <a class="btn btn-secondary" href="{{ url('admin/role') }}" role="button">Cancel</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
@endpush