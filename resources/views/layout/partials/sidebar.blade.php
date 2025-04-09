<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                {{-- Quick Actions --}}
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Quick Actions</h6>
                    <ul>
                        <li><a href="{{ route('admin.services.create') }}"><i data-feather="plus"></i><span>Add Service</span></a></li>
                        <li><a href="{{ route('admin.sales.create') }}"><i data-feather="shopping-cart"></i><span>Add Sale</span></a></li>
                        <li><a href="{{ route('admin.purchases.create') }}"><i data-feather="shopping-bag"></i><span>Make Purchase</span></a></li>
                        <li><a href="{{ route('admin.vehicle-fuels.create') }}"><i data-feather="filter"></i><span>Fueling</span></a></li>
                        <li class="{{ Request::is('vehicles*') ? 'active' : '' }}"><a href="{{ route('admin.vehicles.index') }}"><i data-feather="truck"></i><span>Vehicles</span></a></li>
                    </ul>
                </li>

                {{-- Dashboard --}}
                <li class="{{ Request::is('/') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}"><i data-feather="home"></i><span>Dashboard</span></a>
                </li>

                {{-- Sales & Services --}}
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Sales & Services</h6>
                    <ul>
                        <li class="{{ Request::is('sales*') ? 'active' : '' }}"><a href="{{ route('admin.sales.index') }}"><i data-feather="shopping-cart"></i><span>Sale</span></a></li>
                        <li class="{{ Request::is('services*') ? 'active' : '' }}"><a href="{{ route('admin.services.index') }}"><i data-feather="truck"></i><span>Services</span></a></li>
                    </ul>
                </li>

                {{-- Purchases --}}
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Purchases</h6>
                    <ul>
                        <li class="{{ Request::is('purchases*') ? 'active' : '' }}"><a href="{{ route('admin.purchases.index') }}"><i data-feather="shopping-bag"></i><span>Purchases</span></a></li>
                    </ul>
                </li>

                {{-- Inventory --}}
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Inventory</h6>
                    <ul>
                        <li class="{{ Request::is('products*') ? 'active' : '' }}"><a href="{{ route('admin.products.index') }}"><i data-feather="box"></i><span>Products</span></a></li>
                        <li class="{{ Request::is('categories') ? 'active' : '' }}"><a href="{{ route('admin.categories.index') }}"><i data-feather="codepen"></i><span>Category</span></a></li>
                        <li class="{{ Request::is('brands') ? 'active' : '' }}"><a href="{{ route('admin.brands.index') }}"><i data-feather="tag"></i><span>Brands</span></a></li>
                        <li class="{{ Request::is('racks') ? 'active' : '' }}"><a href="{{ route('admin.racks.index') }}"><i data-feather="layers"></i><span>Racks</span></a></li>
                        <li class="{{ Request::is('drawers*') ? 'active' : '' }}"><a href="{{ route('admin.drawers.index') }}"><i data-feather="hard-drive"></i><span>Drawers</span></a></li>
                        <li class="{{ Request::is('service-charts*') ? 'active' : '' }}"><a href="{{ route('admin.service-charts.index') }}"><i data-feather="bar-chart"></i><span>Service Charts</span></a></li>
                    </ul>
                </li>

                {{-- Vehicles --}}
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Vehicles</h6>
                    <ul>
                        <li class="{{ Request::is('vehicle-model*') ? 'active' : '' }}"><a href="{{ route('admin.vehicle-models.index') }}"><i data-feather="package"></i><span>Vehicle Models</span></a></li>
                        <li class="{{ Request::is('vehicles*') ? 'active' : '' }}"><a href="{{ route('admin.vehicles.index') }}"><i data-feather="truck"></i><span>Vehicles</span></a></li>
                        <li class="{{ Request::is('vehicle-fuels*') ? 'active' : '' }}"><a href="{{ route('admin.vehicle-fuels.index') }}"><i data-feather="filter"></i><span>Fueling</span></a></li>
                    </ul>
                </li>

                {{-- Accounts & Finance --}}
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Accounts & Finance</h6>
                    <ul>
                        <li class="{{ Request::is('accounts*') ? 'active' : '' }}"><a href="{{ route('admin.accounts.index') }}"><i data-feather="credit-card"></i><span>Accounts</span></a></li>
                    </ul>
                </li>
                
                {{-- Report --}}
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Report</h6>
                    <ul>
                        <li class="{{ Request::is('vehicles.report*') ? 'active' : '' }}"><a href="{{ route('admin.vehicle.reports.index') }}"><i data-feather="bar-chart-2"></i><span>Vehicle Report</span></a></li>
                    </ul>
                </li>

                {{-- Hubs --}}
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Hubs</h6>
                    <ul>
                        <li class="{{ Request::is('hubs*') ? 'active' : '' }}"><a href="{{ route('admin.hubs.index') }}"><i data-feather="share-2"></i><span>Hub</span></a></li>
                    </ul>
                </li>

                {{-- Peoples --}}
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Peoples</h6>
                    <ul>
                        <li class="{{ Request::is('suppliers') ? 'active' : '' }}"><a href="{{ route('admin.suppliers.index') }}"><i data-feather="users"></i><span>Suppliers</span></a></li>
                        <li class="{{ Request::is('zones') ? 'active' : '' }}"><a href="{{ route('admin.zones.index') }}"><i data-feather="archive"></i><span>Zones</span></a></li>
                    </ul>
                </li>

                {{-- User Management --}}
                <li class="submenu-open">
                    <h6 class="submenu-hdr">User Management</h6>
                    <ul>
                        @permission('user-list')
                            <li class="{{ Request::is('users') ? 'active' : '' }}"><a href="{{ route('users.index') }}"><i data-feather="user-check"></i><span>Users</span></a></li>
                        @endpermission
                        <li class="{{ Request::is('roles') ? 'active' : '' }}"><a href="{{ route('roles.index') }}"><i data-feather="shield"></i><span>Roles & Permissions</span></a></li>
                    </ul>
                </li>

                {{-- Settings & Logout --}}
                <li class="submenu-open">
                    <ul>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <li class="{{ Request::is('signin') ? 'active' : '' }}">
                                <a href="#" onclick="event.preventDefault(); this.closest('form').submit();"><i data-feather="log-out"></i><span>Logout</span></a>
                            </li>
                        </form>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->
