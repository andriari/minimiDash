<div class="tab-pane fade show active" id="pills-info" role="tabpanel" aria-labelledby="pills-info-tab">
    <form method="POST" action="{{ route('agent.update', ['agent' => $info->agent_id]) }}" class="forms-sample" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="agent_id" value="{{ $info->agent_id }}">
        <div class="form-group">
            <label for="agent_name">Name</label>
            <input type="text" class="form-control" name="agent_name" id="agent_name" placeholder="Agent Name" value="{{ $info->agent_name }}" required>
        </div>
        <div class="form-group">
            <label>Logo</label>
            <input type="file" name="agent_logo" class="file-upload-default">
            <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                    <button class="file-upload-browse btn btn-gradient-primary" type="button">Browse</button>
                </span>
            </div>
        </div>
        <div class="form-group">
            <label for="agent_pic_name">PIC</label>
            <input type="text" class="form-control" name="agent_pic_name" id="agent_pic_name" placeholder="PIC" value="{{ $info->agent_pic_name }}" required>
        </div>
        <div class="form-group">
            <label for="agent_pic_phone">Phone</label>
            <input type="text" class="form-control" name="agent_pic_phone" id="agent_pic_phone" placeholder="Phone" value="{{ $info->agent_pic_phone }}" required>
        </div>
        <div class="form-group">
            <label for="agent_type">Type</label>
            {{ Form::select('agent_type', ['1' => 'Distributor', '2' => 'Producer'], $info->agent_type, ['class' => 'form-control', 'id' => 'agent_type']) }}
        </div>
        <div class="form-group">
            <label for="agent_address">Address</label>
            <textarea class="form-control" name="agent_address" id="agent_address" rows="4" placeholder="Address" required>{{ $info->agent_address }}</textarea>
        </div>
        <div class="form-group">
            <label for="agent_bank_name">Bank Name</label>
            <input type="text" class="form-control" name="agent_bank_name" id="agent_bank_name" placeholder="Bank Name" value="{{ $info->agent_bank_name }}" required>
        </div>
        <div class="form-group">
            <label for="agent_bank_account">Bank Account</label>
            <input type="text" class="form-control" name="agent_bank_account" id="agent_bank_account" placeholder="Bank Account" value="{{ $info->agent_bank_account }}" required>
        </div>
        <div class="form-group">
            <label for="agent_bank_account">Bank Account Name</label>
            <input type="text" class="form-control" name="agent_account_name" id="agent_account_name" placeholder="Bank Account Name" value="{{ $info->agent_account_name }}" required>
        </div>
        <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
        <a href="{{ route('agent.index') }}" class="btn btn-light">Cancel</a>
    </form>
</div>

@push('script')
<script>
    $(document).ready(function() {});
</script>
@endpush