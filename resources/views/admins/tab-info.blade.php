<div class="tab-pane fade show active" id="pills-info" role="tabpanel" aria-labelledby="pills-info-tab">
    <form method="POST" action="{{ route('admin.update', ['admin' => $info->admin_id]) }}" class="forms-sample" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="username">User Name</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="User Name" value="{{ $info->username }}">
        </div>
        <div class="form-group">
            <label for="role_id">Role</label>
            {{ Form::select('role_id', $roles, $info->role_id, ['class' => 'form-control', 'id' => 'role_id']) }}
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            {{ Form::select('status', [ 1 => 'Active', 0 => 'Inactive'], $info->status, ['class' => 'form-control', 'id' => 'status']) }}
        </div>
        <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
        <a href="{{ route('admin.index') }}" class="btn btn-light">Cancel</a>
    </form>
</div>