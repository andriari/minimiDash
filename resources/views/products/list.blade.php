@extends('layouts.app')

@section('title', 'Product')

@section('content')
@include('layouts.alert')
<div class="page-header">
    <h3 class="page-title"> Product </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Product</a></li>
            <li class="breadcrumb-item active" aria-current="page">Physical</li>
        </ol>
    </nav>
</div>
<div class="row mb-3">
    <div class="col-auto">
        <a href="{{ route('product.create') }}" class="btn btn-sm btn-gradient-primary btn-icon-text">
            <i class="mdi mdi-plus btn-icon-prepend"></i> Add </a>
    </div>
    <div class="col-auto">
        <form class="form-inline" action="{{ url('export/excel') }}" method="POST" id="exportForm">
            @csrf
            <input type="hidden" id="menu" name="menu" value="product">
            <button type="submit" class="btn btn-sm btn-gradient-primary btn-icon-text"><i class="mdi mdi-file-export btn-icon-prepend"></i> Export Excel Product</button>
        </form>
    </div>
    <div class="col-auto">
        <form class="form-inline" action="{{ url('export/excel') }}" method="POST" id="exportForm">
            @csrf
            <input type="hidden" id="menu" name="menu" value="product_sku">
            <button type="submit" class="btn btn-sm btn-gradient-primary btn-icon-text"><i class="mdi mdi-file-export btn-icon-prepend"></i> Export Excel Product SKU</button>
        </form>
    </div>
    <div class="col-auto">
        <form class="form-inline" action="{{ url('export/excel') }}" method="POST" id="exportForm">
            @csrf
            <input type="hidden" id="menu" name="menu" value="product_raw">
            <button type="submit" class="btn btn-sm btn-gradient-primary btn-icon-text"><i class="mdi mdi-file-export btn-icon-prepend"></i> Export Excel Product Raw</button>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="productTable">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Category</th>
                                <!-- <th>URL</th> -->
                                <th>Stock</th>
                                <th>Rating</th>
                                <th>Reviews</th>
                                <th>Last Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <form class="form-horizontal" method="POST" id="deleteProductForm">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" form="deleteProductForm">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        $('#productTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: "{{ url('product/getDtRowData') }}",
            columns: [{
                    data: 'product_name'
                },
                {
                    data: 'category_name',
                    name: 'data_category.category_name'
                },
                {
                    data: 'total_stock',
                    name: 'total_stock',
                    searchable: false
                },
                {
                    data: 'product_rating'
                },
                {
                    data: 'value',
                    name: 'minimi_content_rating_tab.value'
                },
                {
                    data: 'last_date'
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
        modal.find('.modal-title').text('Delete ' + title + '?')
        $('#deleteProductForm').attr('action', '{{ url("product") }}/' + id);
    });
</script>
@endpush