<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                {{-- core --}}
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Inventory</h6>
                    <ul>
                        <li class="{{ Request::is('products', 'product-details') ? 'active' : '' }}"><a
                                href="{{ route('admin.products.index') }}"><i data-feather="box"></i><span>Products</span></a>
                        </li>
                        <li class="{{ Request::is('categories') ? 'active' : '' }}"><a
                                href="{{ route('admin.categories.index') }}"><i
                                    data-feather="codepen"></i><span>Category</span></a>
                        </li>
                        <li class="{{ Request::is('brands') ? 'active' : '' }}"><a
                                href="{{ route('admin.brands.index') }}">
                                <i data-feather="tag"></i><span>Brands</span></a>
                        </li>
                        <li class="{{ Request::is('zones') ? 'active' : '' }}"><a
                                href="{{ route('admin.zones.index') }}">
                                <i data-feather="map-pin"></i><span>Zones</span></a>
                        </li>
                        <li class="{{ Request::is('racks') ? 'active' : '' }}"><a
                                href="{{ route('admin.racks.index') }}">
                                <i data-feather="layers"></i><span>Racks</span></a>
                        </li>

                    </ul>
                </li>
                {{-- peoples --}}
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Peoples</h6>
                    <ul>
                        <li class="{{ Request::is('suppliers') ? 'active' : '' }}"><a
                                href="{{ route('admin.suppliers.index') }}"><i
                                    data-feather="users"></i><span>Suppliers</span></a>
                        </li>
                        <li class="{{ Request::is('zones') ? 'active' : '' }}"><a
                                href="{{ route('admin.zones.index') }}"><i
                                    data-feather="archive"></i><span>Zones</span></a>
                        </li>
                    </ul>
                </li>
                {{-- user management --}}
                <li class="submenu-open">
                    <h6 class="submenu-hdr">User Management</h6>
                    <ul>
                        <li class="{{ Request::is('users') ? 'active' : '' }}"><a href="{{ route('users.index') }}"><i
                                    data-feather="user-check"></i><span>Users</span></a>
                        </li>
                        <li class="{{ Request::is('roles') ? 'active' : '' }}"><a href="{{ route('roles.index') }}"><i
                                    data-feather="shield"></i><span>Roles &
                                    Permissions</span></a></li>
                    </ul>
                </li>

                {{-- setting --}}
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Settings</h6>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="{{ Request::is('general-settings', 'security-settings', 'notification', 'connected-apps') ? 'active subdrop' : '' }}"><i
                                    data-feather="settings"></i><span>General
                                    Settings</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="{{ url('general-settings') }}"
                                        class="{{ Request::is('general-settings') ? 'active' : '' }}">Profile</a>
                                </li>
                                <li><a href="{{ url('security-settings') }}"
                                        class="{{ Request::is('security-settings') ? 'active' : '' }}">Security</a>
                                </li>
                                {{-- add new here --}}
                            </ul>
                        </li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <li class="{{ Request::is('signin') ? 'active' : '' }}">
                                <a href="#"
                                    onclick="event.preventDefault();
                                        this.closest('form').submit();"><i
                                        data-feather="log-out"></i><span>Logout</span> </a>
                            </li>
                        </form>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->
