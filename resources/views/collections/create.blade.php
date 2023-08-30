@extends('layouts.app')

@section('title', 'Collection | Add')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> Collection </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('collection.index') }}">Collection</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Add Collection</h4>
                <form method="POST" action="{{ url('collection') }}" class="forms-sample" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="collection_name">Name</label>
                        <input type="text" class="form-control" name="collection_name" id="collection_name" placeholder="Name" value="{{ old('collection_name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="collection_desc">Description</label>
                        <textarea class="form-control" name="collection_desc" id="collection_desc" rows="4" placeholder="Description">{{ old('collection_desc') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                    <a href="{{ route('collection.index') }}" class="btn btn-light">Cancel</a>
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