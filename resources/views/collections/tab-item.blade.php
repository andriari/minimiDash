<div class="tab-pane fade" id="pills-item" role="tabpanel" aria-labelledby="pills-item-tab">
    <div class="row mb-3" style="border-bottom: 1px solid #ebedf2;">
        <div class="col-lg">
            <h5>Assign Product</h5>
            <form class="form-inline" method="POST" action="{{ url('collection/product/assign') }}">
                @csrf
                <input type="hidden" name="collection_id" value="{{ $info->collection_id }}">
                <input type="hidden" name="product_id" id="product_id">
                <label class="sr-only" for="product_name_assign">Product</label>
                <div class="input-group mb-2 mr-sm-2">
                    <input type="text" class="form-control mb-2 mr-sm-2" id="product_name_assign" placeholder="Product" onkeydown="if (event.keyCode == 13) return false;">
                    <div class="invalid-feedback">
                        Product Not Found.
                    </div>
                </div>
                <button type="submit" class="btn btn-gradient-primary mb-2">Submit</button>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="table-responsive">
                <table class="table table-hover" id="itemTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Brand</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="deleteProductModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form class="form-horizontal" method="POST" id="deleteProductForm">
                        @csrf
                        <input type="hidden" name="collection_id" value="{{ $info->collection_id }}">
                        <input type="hidden" name="product_id" id="remove_product_id">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" form="deleteProductForm">Yes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    $(document).ready(function() {
        $('#itemTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: "{{ url('collection/getDtItemRowData/'.$info->collection_id) }}",
            columns: [{
                    data: 'product_name',
                    name: 'minimi_product.product_name',
                },
                {
                    data: 'category_name',
                    name: 'data_category.category_name'
                },
                {
                    data: 'subcat_name',
                    name: 'data_category_sub.subcat_name'
                },
                {
                    data: 'brand_name',
                    name: 'data_brand.brand_name'
                },
                {
                    data: 'pict',
                    name: 'pict',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    });

    $('#deleteProductModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var title = button.data('title')
        var modal = $(this)
        modal.find('.modal-title').text('Remove ' + title + '?')
        modal.find('.modal-header #remove_product_id').val(id)
        $('#deleteProductForm').attr('action', '{{ url("collection/product/remove") }}');
    });

    $("#product_name_assign").autocomplete({
        source: function(request, response) {
            $(".invalid-feedback").hide();
            var param = {
                '_token': "{{ csrf_token() }}",
                'search_query': request.term
            }
            $.post("{{ url(config('env.APP_URL')) }}/product/search", param, function(data) {
                // console.log(data)
                if (data.code == 200) {
                    response($.map(data.data, function(item) {
                        return {
                            code: item.product_id,
                            value: item.product_name
                        };
                    }));
                } else {
                    $(".invalid-feedback").show();
                }
            }, "json");
        },
        select: function(event, ui) {
            $("#product_id").val(ui.item.code);
        }
    });
</script>
@endpush