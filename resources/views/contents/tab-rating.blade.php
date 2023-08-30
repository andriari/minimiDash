<div class="tab-pane fade" id="pills-rating" role="tabpanel" aria-labelledby="pills-rating-tab">
    <form class="forms-sample">
        @foreach($rating as $row)
        <div class="form-group row">
            <label for="rating_value" class="col-sm-2 col-form-label">{{ $row->rating_name }}</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="rating_value" value="{{ $row->rating_value }}" readonly>
            </div>
        </div>
        @endforeach
    </form>
</div>

@push('script')
<script>
</script>
@endpush