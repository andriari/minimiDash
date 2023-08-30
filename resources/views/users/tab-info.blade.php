<div class="tab-pane fade show active" id="pills-info" role="tabpanel" aria-labelledby="pills-info-tab">
    <form class="forms-sample">
        <div class="form-group">
            <label for="created_at">Sign Up Date</label>
            <input type="text" class="form-control" id="created_at" placeholder="User ID" value="{{ date('d F Y', strtotime($info->created_at)) }}" readonly>
        </div>
        <div class="form-group">
            <label for="user_id">User ID</label>
            <input type="text" class="form-control" id="user_id" placeholder="User ID" value="{{ $info->user_id }}" readonly>
        </div>
        <div class="form-group">
            <label for="user_uri">Username</label>
            <input type="text" class="form-control" id="user_uri" placeholder="Username" value="{{ $info->user_uri }}" readonly>
        </div>
        <div class="form-group">
            <label for="fullname">Name</label>
            <input type="text" class="form-control" id="fullname" placeholder="Name" value="{{ $info->fullname }}" readonly>
        </div>
        <div class="form-group">
            <label for="gender">Gender</label>
            <input type="text" class="form-control" id="gender" placeholder="Gender" value="{{ $info->gender==1 ? 'Male':'Female' }}" readonly>
        </div>
        <div class="form-group">
            <label for="lives_in">Live in</label>
            <input type="text" class="form-control" id="lives_in" placeholder="Live in" value="{{ $info->lives_in }}" readonly>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" class="form-control" id="email" placeholder="Email" value="{{ $info->email }}" readonly>
        </div>
        <div class="form-group">
            <label for="socmed_instagram">Instagram</label>
            <input type="text" class="form-control" id="socmed_instagram" placeholder="Instagram" value="{{ $info->socmed_instagram }}" readonly>
        </div>
        <div class="form-group">
            <label for="website">Website</label>
            <input type="text" class="form-control" id="website" placeholder="Website" value="{{ $info->website }}" readonly>
        </div>
        <div class="form-group">
            <label for="point_count">Point</label>
            <input type="text" class="form-control" id="point_count" placeholder="Point" value="{{ $info->point_count }}" readonly>
        </div>
        <div class="form-group">
            <label for="personal_bio">Bio</label>
            <textarea class="form-control" id="personal_bio" rows="4" placeholder="Description" readonly>{{ $info->personal_bio }}</textarea>
        </div>
        <div class="form-group">
            <label for="review_approved">Review Approved</label>
            <input type="text" class="form-control" id="review_approved" placeholder="Review Approved" value="{{ $info2['approved'] }}" readonly>
        </div>
        <div class="form-group">
            <label for="review_rejected">Review Rejected</label>
            <input type="text" class="form-control" id="review_rejected" placeholder="Review Rejected" value="{{ $info2['rejected'] }}" readonly>
        </div>
    </form>
</div>

@push('script')
@endpush