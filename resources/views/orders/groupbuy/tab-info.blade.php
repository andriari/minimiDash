<div class="tab-pane fade show active" id="pills-info" role="tabpanel" aria-labelledby="pills-info-tab">
    <form class="forms-sample" enctype="multipart/form-data">
        <div class="form-group row">
            <label for="cg_id" class="col-sm-2 col-form-label">Group ID</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="cg_id" value="{{ $info->cg_id }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="fullname" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="fullname" value="{{ $info->fullname }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="product_name" class="col-sm-2 col-form-label">Product</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="product_name" value="{{ $info->product_name }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="product_price" class="col-sm-2 col-form-label">Price</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="product_price" value="{{ $info->product_price }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="product_rating" class="col-sm-2 col-form-label">Rating</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="product_rating" value="{{ $info->product_rating }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="category_name" class="col-sm-2 col-form-label">Category</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="category_name" value="{{ $info->category_name }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="subcat_name" class="col-sm-2 col-form-label">Sub Category</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="subcat_name" value="{{ $info->subcat_name }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="brand_name" class="col-sm-2 col-form-label">Brand</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="brand_name" value="{{ $info->brand_name }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="link" class="col-sm-2 col-form-label">Share URL</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="link" value="{{ $info->share_link_url }}" readonly>
            </div>
        </div>
    </form>
</div>