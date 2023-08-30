<div class="tab-pane fade" id="pills-user" role="tabpanel" aria-labelledby="pills-user-tab">
    <form class="forms-sample">
        <div class="form-group row">
            <label for="fullname" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="fullname" value="{{ $user->fullname }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="email" value="{{ $user->email }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="phone" class="col-sm-2 col-form-label">Phone</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="phone" value="{{ $user->phone }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="user_uri" class="col-sm-2 col-form-label">User URI</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="user_uri" value="{{ $user->user_uri }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="photo_profile" class="col-sm-2 col-form-label">Profile Picture</label>
            <div class="col-sm-4">
                <img src="{{ $user->photo_profile }}" class="img-thumbnail">
            </div>
        </div>
    </form>
</div>

@push('script')
<script>
</script>
@endpush