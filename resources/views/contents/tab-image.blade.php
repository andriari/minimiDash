<div class="tab-pane fade" id="pills-image" role="tabpanel" aria-labelledby="pills-image-tab">
    <div class="row">
        @foreach ($image as $row)
        <div class="col-sm-4">
            <div class="card">
                <img class="card-img-top" src="{{ $row->cont_gallery_picture }}">
                <div class="card-body p-1">
                    <h5 class="card-title">{{ $row->cont_gallery_title }}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">{{ $row->cont_gallery_alt }}</h6>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@push('script')
<script>
</script>
@endpush