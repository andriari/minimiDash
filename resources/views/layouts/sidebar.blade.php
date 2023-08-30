<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ url('') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>
        @if(count(array_intersect(Session::get('user.permission'), ['order_verification', 'order_delivery', 'groupbuy'])) > 0)
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#order-menu" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">Transaction</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-calendar-multiple menu-icon"></i>
            </a>
            <div class="collapse" id="order-menu">
                <ul class="nav flex-column sub-menu">
                    @if(in_array('order_verification', Session::get('user.permission')))
                    <li class="nav-item"> <a class="nav-link" href="{{ url('order/verification') }}">Beli Sendiri</a></li>
                    @endif
                    @if(in_array('order_delivery', Session::get('user.permission')))
                    <li class="nav-item"> <a class="nav-link" href="{{ url('order/delivery') }}">Delivery</a></li>
                    @endif
                    @if(in_array('groupbuy', Session::get('user.permission')))
                    <li class="nav-item"> <a class="nav-link" href="{{ url('order/groupbuy') }}">Beli Bareng</a></li>
                    @endif
                </ul>
            </div>
        </li>
        @endif
        @if(in_array('product', Session::get('user.permission')))
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#product-menu" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">Product</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-parking menu-icon"></i>
            </a>
            <div class="collapse" id="product-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ url('product/digital') }}">Digital</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ url('product') }}">Physical</a></li>
                </ul>
            </div>
        </li>
        @endif
        @if(in_array('collection', Session::get('user.permission')))
        <li class="nav-item">
            <a class="nav-link" href="{{ url('collection') }}">
                <span class="menu-title">Collection</span>
                <i class="mdi mdi-file-image menu-icon"></i>
            </a>
        </li>
        @endif
        @if(in_array('category', Session::get('user.permission')))
        <li class="nav-item">
            <a class="nav-link" href="{{ url('category') }}">
                <span class="menu-title">Category</span>
                <i class="mdi mdi-animation menu-icon"></i>
            </a>
        </li>
        @endif
        @if(in_array('voucher', Session::get('user.permission')))
        <li class="nav-item">
            <a class="nav-link" href="{{ url('voucher') }}">
                <span class="menu-title">Voucher</span>
                <i class="mdi mdi-tag-text-outline menu-icon"></i>
            </a>
        </li>
        @endif
        @if(in_array('brand', Session::get('user.permission')))
        <li class="nav-item">
            <a class="nav-link" href="{{ url('brand') }}">
                <span class="menu-title">Brand</span>
                <i class="mdi mdi-cards menu-icon"></i>
            </a>
        </li>
        @endif
        @if(in_array('banner', Session::get('user.permission')))
        <li class="nav-item">
            <a class="nav-link" href="{{ url('banner') }}">
                <span class="menu-title">Banner</span>
                <i class="mdi mdi-file-image menu-icon"></i>
            </a>
        </li>
        @endif
        @if(in_array('content', Session::get('user.permission')))
        <li class="nav-item">
            <a class="nav-link" href="{{ url('content/post') }}">
                <span class="menu-title">Reviews</span>
                <i class="mdi mdi-note-text menu-icon"></i>
            </a>
        </li>
        @endif
        @if(in_array('article', Session::get('user.permission')))
        <li class="nav-item">
            <a class="nav-link" href="{{ url('article') }}">
                <span class="menu-title">Article</span>
                <i class="mdi mdi-newspaper menu-icon"></i>
            </a>
        </li>
        @endif
        @if(in_array('user', Session::get('user.permission')))
        <li class="nav-item">
            <a class="nav-link" href="{{ url('user') }}">
                <span class="menu-title">User</span>
                <i class="mdi mdi-account menu-icon"></i>
            </a>
        </li>
        @endif
        @if(in_array('affiliate', Session::get('user.permission')))
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#affiliate-menu" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">Affiliate</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-lead-pencil menu-icon"></i>
            </a>
            <div class="collapse" id="affiliate-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ url('affiliate/article') }}">Article</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ url('affiliate/bank') }}">Bank Verification</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ url('affiliate/withdraw') }}">Withdrawal Process</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ url('affiliate/credit') }}">Credit Status</a></li>
                </ul>
            </div>
        </li>
        @endif
        @if(in_array('point', Session::get('user.permission')))
        <li class="nav-item">
            <a class="nav-link" href="{{ url('point') }}">
                <span class="menu-title">Point</span>
                <i class="mdi mdi-chart-bubble menu-icon"></i>
            </a>
        </li>
        @endif
        @if(in_array('reward', Session::get('user.permission')))
        <li class="nav-item">
            <a class="nav-link" href="{{ url('reward') }}">
                <span class="menu-title">Reward</span>
                <i class="mdi mdi-gift menu-icon"></i>
            </a>
        </li>
        @endif
        @if(in_array('agent', Session::get('user.permission')))
        <li class="nav-item">
            <a class="nav-link" href="{{ url('agent') }}">
                <span class="menu-title">Agent</span>
                <i class="mdi mdi-account-network menu-icon"></i>
            </a>
        </li>
        @endif
        @if(in_array('admin', Session::get('user.permission')))
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#admin-menu" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">Admin</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-account-key menu-icon"></i>
            </a>
            <div class="collapse" id="admin-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ url('admin') }}">List</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ url('admin/role') }}">Roles</a></li>
                </ul>
            </div>
        </li>
        @endif
    </ul>
</nav>