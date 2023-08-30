<div class="tab-pane fade" id="pills-variant" role="tabpanel" aria-labelledby="pills-variant-tab">
    <div class="row mb-3">
        <div class="col-lg">
            <button type="button" class="btn btn-sm btn-gradient-primary btn-icon-text" data-toggle="modal" data-target="#createVariantModal">
                <i class="mdi mdi-plus btn-icon-prepend"></i> Add
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Variant Name</th>
                            <th>SKU</th>
                            <th>Stock</th>
                            <th>Weight</th>
                            <th>Price</th>
                            <th>Price Beli Bareng</th>
                            <th class="bg-danger">Agent Price</th>
                            <th>Buy Restriction</th>
                            <th>Show</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($variant as $row)
                        <tr>
                            <td>{{ $row->variant_name }}</td>
                            <td>{{ $row->variant_sku }}</td>
                            <td>{{ $row->stock_count }}</td>
                            <td>{{ number_format($row->stock_weight) }} g</td>
                            <td>{{ $row->stock_price }}</td>
                            <td>{{ $row->stock_price_gb }}</td>
                            <td class="bg-danger">{{ $row->stock_agent_price }}</td>
                            <td>{{ $row->stock_restriction_count }}</td>
                            <td>
                                @if ($row->publish == 0)
                                <label class="badge badge-gradient-danger btn-block">Hidden</label>
                                @else
                                <label class="badge bg-gradient-light text-dark btn-block">Shown</label>
                                @endif
                            </td>
                            <td>
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-sm btn-block btn-gradient-primary btn-icon-text" data-toggle="modal" data-target="#editVariantModal" data-id="{{ $row->variant_id }}" data-name="{{ $row->variant_name }}" data-variant_sku="{{ $row->variant_sku }}" data-stock_count="{{ $row->stock_count }}" data-stock_weight="{{ $row->stock_weight }}" data-stock_price="{{ $row->stock_price }}" data-stock_price_gb="{{ $row->stock_price_gb }}" data-stock_agent_price="{{ $row->stock_agent_price }}" data-stock_restriction_count="{{ $row->stock_restriction_count }}">
                                    <i class="mdi mdi-pencil-box-outline btn-icon-prepend"></i> Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-block btn-gradient-danger btn-icon-text" data-toggle="modal" data-target="#deleteVariantModal" data-id="{{ $row->variant_id }}" data-title="{{ $row->variant_name }}"><i class="mdi mdi-delete btn-icon-prepend"></i>Delete</button>
                                @if ($row->publish == 0)
                                <button type="button" class="btn btn-sm btn-block btn-gradient-success btn-icon-text" data-toggle="modal" data-target="#publishVariantModal" data-id="{{ $row->variant_id }}" data-title="{{ $row->variant_name }}" data-mode="1">Show</button>
                                @else
                                <button type="button" class="btn btn-sm btn-block bg-gradient-dark text-white btn-icon-text" data-toggle="modal" data-target="#publishVariantModal" data-id="{{ $row->variant_id }}" data-title="{{ $row->variant_name }}" data-mode="0">Hide</button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="createVariantModal" tabindex="-1" role="dialog" aria-labelledby="createVariantModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createVariantModalLabel">Create Variant</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <form id="createVariantForm" method="POST" action="{{ url('product/variant') }}" class="forms-sample" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $info->product_id }}">
                                <div class="form-group">
                                    <label for="variant_name">Variant Name</label>
                                    <input type="text" class="form-control" name="variant_name" id="variant_name" placeholder="Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="variant_sku">SKU</label>
                                    <input type="text" class="form-control" name="variant_sku" id="variant_sku" placeholder="SKU" required>
                                </div>
                                <div class="form-group">
                                    <label for="stock_count">Stock</label>
                                    <input type="number" class="form-control" name="stock_count" id="stock_count" placeholder="Stock" required>
                                </div>
                                <div class="form-group">
                                    <label for="stock_weight">Weight</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="stock_weight" id="stock_weight" placeholder="Weight (gram)" value="0" step=".01" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">gram</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="stock_price">Price</label>
                                    <input type="number" class="form-control" name="stock_price" id="stock_price" placeholder="Price" required>
                                </div>
                                <div class="form-group">
                                    <label for="stock_price">Beli Bareng Price</label>
                                    <input type="number" class="form-control" name="stock_price_gb" id="stock_price_gb" placeholder="Beli Bareng Price" required>
                                </div>
                                <div class="form-group">
                                    <label for="stock_agent_price">Agent Price</label>
                                    <input type="number" class="form-control" name="stock_agent_price" id="stock_agent_price" placeholder="Agent Price" required>
                                </div>
                                <div class="form-group">
                                    <label for="stock_price">Buy Restriction Count</label>
                                    <input type="number" class="form-control" name="stock_restriction_count" id="stock_restriction_count" placeholder="Buy Restriction Count" required>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" form="createVariantForm" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editVariantModal" tabindex="-1" role="dialog" aria-labelledby="editVariantModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editVariantModalLabel">Edit Variant</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <form id="editVariantForm" method="POST" class="forms-sample" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="variant_id" id="variant_id">
                                <div class="form-group">
                                    <label for="edit_variant_name">Variant Name</label>
                                    <input type="text" class="form-control" name="variant_name" id="edit_variant_name" placeholder="Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_variant_sku">SKU</label>
                                    <input type="text" class="form-control" name="variant_sku" id="edit_variant_sku" placeholder="SKU" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_stock_count">Stock</label>
                                    <input type="number" class="form-control" name="stock_count" id="edit_stock_count" placeholder="Stock" required>
                                </div>
                                <div class="form-group">
                                    <label for="stock_weight">Weight</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="stock_weight" id="edit_stock_weight" placeholder="Weight (gram)" value="0" step=".01" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">gram</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="edit_stock_price">Price</label>
                                    <input type="number" class="form-control" name="stock_price" id="edit_stock_price" placeholder="Price" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_stock_price_gb">Beli Bareng Price</label>
                                    <input type="number" class="form-control" name="stock_price_gb" id="edit_stock_price_gb" placeholder="Beli Bareng Price" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_stock_agent_price">Agent Price</label>
                                    <input type="number" class="form-control" name="stock_agent_price" id="edit_stock_agent_price" placeholder="Agent Price" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_stock_restriction_count">Buy Restriction Count</label>
                                    <input type="number" class="form-control" name="stock_restriction_count" id="edit_stock_restriction_count" placeholder="Buy Restriction Count" required>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" form="editVariantForm" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteVariantModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Variant</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form class="form-horizontal" method="POST" id="deleteVariantForm">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" form="deleteVariantForm">Yes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="publishVariantModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Publish Voucher</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form class="form-horizontal" action="{{ route('product.variant.publish') }}" method="POST" id="publishVoucherForm">
                        @csrf
                        <input type="hidden" name="variant_id" id="variant_id">
                        <input type="hidden" name="mode" id="mode">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" form="publishVoucherForm">Yes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    $('#editVariantModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var name = button.data('name')
        var variant_sku = button.data('variant_sku')
        var stock_count = button.data('stock_count')
        var stock_price = button.data('stock_price')
        var stock_weight = button.data('stock_weight')
        var stock_agent_price = button.data('stock_agent_price')
        var stock_price_gb = button.data('stock_price_gb')
        var stock_restriction_count = button.data('stock_restriction_count')
        var modal = $(this)
        modal.find('.modal-title').text('Edit ' + name)
        modal.find('.modal-body #variant_id').val(id)
        modal.find('.modal-body #edit_variant_name').val(name)
        modal.find('.modal-body #edit_variant_sku').val(variant_sku)
        modal.find('.modal-body #edit_stock_count').val(stock_count)
        modal.find('.modal-body #edit_stock_price').val(stock_price)
        modal.find('.modal-body #edit_stock_weight').val(stock_weight)
        modal.find('.modal-body #edit_stock_agent_price').val(stock_agent_price)
        modal.find('.modal-body #edit_stock_price_gb').val(stock_price_gb)
        modal.find('.modal-body #edit_stock_restriction_count').val(stock_restriction_count)
        $('#editVariantForm').attr('action', '{{ url("product/variant") }}/' + id);
    });

    $('#deleteVariantModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var title = button.data('title')
        var modal = $(this)
        modal.find('.modal-title').text('Delete ' + title + '?')
        $('#deleteVariantForm').attr('action', '{{ url("product/variant") }}/' + id);
    });

    $('#publishVariantModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var title = button.data('title')
        var mode = button.data('mode')
        var modal = $(this)
        var text_title = 'Show ' + title + '?'
        if (mode == 0)
            text_title = 'Hide ' + title + '?'
        modal.find('.modal-title').text(text_title)
        modal.find('.modal-header #variant_id').val(id)
        modal.find('.modal-header #mode').val(mode)
    });
</script>
@endpush