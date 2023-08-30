<div class="tab-pane fade" id="pills-review" role="tabpanel" aria-labelledby="pills-review-tab">
    <div class="row">
        <div class="col" id="reviews">
            @foreach($review as $row)
            <div class="card mb-3 w-100" style="border: 1px solid rgba(0, 0, 0, 0.125)">
                <div class="card-header">
                    {{ $row->product_name }}
                    @switch($row->content_curated)
                    @case(1)
                    <span class="badge badge-success">Approved</span>
                    @break
                    @case(2)
                    <span class="badge badge-danger">Declined</span>
                    @break
                    @default
                    <span class="badge badge-secondary">Pending</span>
                    @endswitch
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $row->content_title }}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">{{ $row->content_subtitle }}</h6>
                    <p class="card-text">{{ $row->category_name }} <i class="mdi mdi-arrow-right-bold"></i> {{ $row->subcat_name }} <i class="mdi mdi-arrow-right-bold"></i> {{ $row->brand_name }} <i class="mdi mdi-arrow-right-bold"></i> {{ $row->product_name }}</p>
                    <p class="card-text">{{ $row->content_text }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @if($offset != "empty")
    <div class="row">
        <div class="col">
            <button type="button" class="btn btn-sm btn-block btn-gradient-info btn-icon-text" id="loadMorebtn" data-limit="10" data-offset="{{ $offset }}">
                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                <span class="loading d-none">Loading...</span>
                <span class="loadmore"><i class="mdi mdi-autorenew btn-icon-prepend"></i>Load More</span>
            </button>
            <!-- <button type="button" class="btn btn-sm btn-block btn-gradient-info btn-icon-text" data-limit="" data-offset=""><i class="mdi mdi-autorenew btn-icon-prepend"></i>Load More</button> -->
        </div>
    </div>
    @endif
</div>

@push('script')
<script>
    $('#loadMorebtn').click(function(e) {
        $('#loadMorebtn').prop('disabled', true);
        $('.spinner-border').removeClass('d-none');
        $('.loading').removeClass('d-none');
        $('.loadmore').addClass('d-none');
        var param = {
            'limit': $(this).data('limit'),
            'offset': $(this).data('offset'),
        }
        url = "{!! route('user.review', ['id' => $info->user_id]) !!}";
        $.get(url, param, function(data) {
                if (data.code == 200) {
                    var data = data.data;
                    $.each(data.review, function(i, item) {
                        var badge;
                        switch (item.content_curated) {
                            case 1:
                                badge = "<span class=\"badge badge-success\">Approved</span>";
                                break;
                            case 2:
                                badge = "<span class=\"badge badge-danger\">Declined</span>";
                                break;
                            default:
                                badge = "<span class=\"badge badge-secondary\">Pending</span>";
                        }
                        $('#reviews').append("<div class=\"card mb-3 w-100\" style=\"border: 1px solid rgba(0, 0, 0, 0.125)\"><div class=\"card-header\">" + item.product_name + " " + badge + "</div><div class=\"card-body\"><h5 class=\"card-title\">" + item.content_title + "</h5><h6 class=\"card-subtitle mb-2 text-muted\">" + item.content_subtitle + "</h6><p class=\"card-text\">" + item.category_name + " <i class=\"mdi mdi-arrow-right-bold\"></i> " + item.subcat_name + " <i class=\"mdi mdi-arrow-right-bold\"></i> " + item.brand_name + " <i class=\"mdi mdi-arrow-right-bold\"></i> " + item.product_name + "</p><p class=\"card-text\">" + item.content_text + "</p></div></div>");
                    });
                    if (data.offset != "empty") {
                        $('#loadMorebtn').data('offset', data.offset);
                    } else {
                        $('#loadMorebtn').hide();
                    }
                }
            }, "json")
            .done(function() {
                $('#loadMorebtn').prop('disabled', false);
                $('.spinner-border').addClass('d-none');
                $('.loading').addClass('d-none');
                $('.loadmore').removeClass('d-none');
            });
    });
</script>
@endpush