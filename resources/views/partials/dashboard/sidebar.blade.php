<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="javascript:void(0)">Projek Ningrat</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="javascript:void(0)">PN</a>
        </div>
        <ul class="sidebar-menu">
            @role('reseller')
            <li class="menu-header">Shop</li>
            <li class="nav-item dropdown">
                <a href="{{ route('member.showr') }}" class="nav-link"><i class="fas fa-user"></i><span>Member Shop</span></a>
            </li>
            @endrole
            <li class="menu-header">Dashboard</li>
            <li class="nav-item dropdown">
                <a href="{{ route('dashboard') }}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            @if (!auth()->user()->isReseller())
            <li class="menu-header">Order</li>
            <li class="nav-item dropdown {{ request()->segment(2) == 'order' ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fa fa-shopping-cart"></i>
                    <span>Order</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('stock.index') }}">Produk / Stok Barang</a></li>
                    <li><a class="nav-link" href="{{ route('order.index') }}">Repeat Order</a></li>
                    @role('reseller')
                    @else
                    <li><a class="nav-link" href="{{ route('transaction.index') }}">Riwayat Transaksi</a></li>
                    @endrole
                </ul>
            </li>
            @endif

            {{-- Menu Master --}}
            @role('superadmin')
            <li class="menu-header">Master</li>
            <li class="nav-item dropdown {{ request()->segment(2) == 'master' ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fa fa-fighter-jet"></i>
                    <span>Master</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('category.index') }}">Kategori</a></li>
                    <li><a class="nav-link" href="{{ route('product.index') }}">Produk</a></li>
                    <li><a class="nav-link" href="{{ route('diskon-kategori.index') }}">Diskon Kategori</a></li>
                    <li><a class="nav-link" href="{{ route('discount.index') }}">Diskon</a></li>
                    <li><a class="nav-link" href="{{ route('royalty.index') }}">Royalty</a></li>
                </ul>
            </li>
            @endrole
            {{-- End Menu Master --}}
            @if (!auth()->user()->isReseller())
            <li class="menu-header">Pengaturan</li>
            <li class="nav-item dropdown {{ request()->segment(2) == 'pengaturan' ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-cog"></i>
                    <span>Pengaturan</span></a>
                {{-- List Pengaturan --}}
                @role('superadmin')
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('users.index') }}">Users</a></li>
                    <li><a class="nav-link" href="{{ route('courier.index') }}">Courier</a></li>
                    <li><a class="nav-link" href="{{ route('point.index') }}">Point</a></li>
                    <li><a class="nav-link" href="{{ route('reward.index') }}">Reward</a></li>
                    <li><a class="nav-link" href="{{ route('setting.index') }}">Ketentuan Belanja</a></li>
                    <li><a class="nav-link" href="{{ route('maintenance') }}">User Maintenance</a></li>
                </ul>
                @else
                {{-- Else List Pengaturan --}}
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('users.index') }}">Users</a></li>
                    <li><a class="nav-link" href="{{ route('profile.index') }}">Profile</a></li>
                </ul>
                @endrole
                {{-- End List Pengaturan --}}
            </li>
            @endif
        </ul>

        <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
            <a href="#" class="btn btn-primary btn-lg btn-block btn-icon-split">
                <i class="fas fa-phone"></i> Contact Us
            </a>
        </div>
    </aside>
</div>