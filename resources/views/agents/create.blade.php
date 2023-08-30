@extends('layouts.app')

@section('title', 'Agent | Add')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> Agent </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Agent</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Add Agent</h4>
                <form method="POST" action="{{ url('agent') }}" class="forms-sample" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="agent_name">Name</label>
                        <input type="text" class="form-control" name="agent_name" id="agent_name" placeholder="Agent Name" value="{{ old('agent_name') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Logo</label>
                        <input type="file" name="agent_logo" class="file-upload-default">
                        <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image" required>
                            <span class="input-group-append">
                                <button class="file-upload-browse btn btn-gradient-primary" type="button">Browse</button>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="agent_pic_name">PIC</label>
                        <input type="text" class="form-control" name="agent_pic_name" id="agent_pic_name" placeholder="PIC" value="{{ old('agent_pic_name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="agent_pic_phone">Phone</label>
                        <input type="text" class="form-control" name="agent_pic_phone" id="agent_pic_phone" placeholder="Phone" value="{{ old('agent_pic_phone') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="agent_type">Type</label>
                        {{ Form::select('agent_type', ['1' => 'Distributor', '2' => 'Producer'], old('agent_type'), ['class' => 'form-control', 'id' => 'agent_type']) }}
                    </div>
                    <div class="form-group">
                        <label for="agent_address">Address</label>
                        <textarea class="form-control" name="agent_address" id="agent_address" rows="4" placeholder="Address" required>{{ old('agent_address') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="agent_bank_name">Bank Name</label>
                        <input type="text" class="form-control" name="agent_bank_name" id="agent_bank_name" placeholder="Bank Name" value="{{ old('agent_bank_name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="agent_bank_account">Bank Account</label>
                        <input type="text" class="form-control" name="agent_bank_account" id="agent_bank_account" placeholder="Bank Account" value="{{ old('agent_bank_account') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="agent_bank_account">Bank Account Name</label>
                        <input type="text" class="form-control" name="agent_account_name" id="agent_account_name" placeholder="Bank Account Name" value="{{ old('agent_account_name') }}" required>
                    </div>
                    <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                    <a href="{{ route('agent.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {});
</script>
@endpush