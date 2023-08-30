@extends('layouts.app')

@section('title', 'Home | Change Password')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> Change Password </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Change Password</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" method="POST" action="{{ url('change_password') }}">
                    @csrf
                    <div class="form-group">
                        <label for="old_password">Old Password</label>
                        <input type="password" class="form-control" name="old_password" id="old_password" placeholder="Old Password">
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" class="form-control" name="new_password" id="new_password" placeholder="New Password">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Password">
                    </div>
                    <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $('#confirm_password').on('keyup', function() {
        if ($('#new_password').val() == $('#confirm_password').val()) {
            $('#confirm_password').removeClass('is-invalid');
            document.getElementById('submitBtn').disabled = false;
        } else {
            $('#confirm_password').addClass('is-invalid');
            document.getElementById('submitBtn').disabled = true;
        }
    });
</script>
@endpush