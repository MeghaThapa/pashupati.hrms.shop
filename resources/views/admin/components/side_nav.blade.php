<aside class="main-sidebar elevation-4 @if (session('isSidebarDark')) sidebar-dark-primary @endif">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <!-- Large Device  -->


        @if (session()->get('isDark') == true || session()->get('isSidebarDark') == true)
            <img class="lg-logo" src="{{ $settings['darkLogo'] }}" alt="{{ $settings['compnayName'] }}">
            <!-- Small Device -->
            <img class="sm-logo" src="{{ $settings['smallDarkLogo'] }}" alt="{{ $settings['compnayName'] }}">
        @else
            <img class="lg-logo" src="{{ $settings['logo'] }}" alt="{{ $settings['compnayName'] }}">
            <!-- Small Device -->
            <img class="sm-logo" src="{{ $settings['smallLogo'] }}" alt="{{ $settings['compnayName'] }}">
        @endif

    </a>
    <!-- Sidebar -->
    <div class="sidebar custom-sidebar" id="sideNavStyle">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-header text-bold">{{ __('ACTIVITY') }}</li>
                <li class="nav-item has-treeview {{ request()->is('home/main-entry/*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('home/main-entry/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-star"></i>
                        <p>
                            Opening
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ">
                        <li class="nav-item">
                            <a href="{{ route('openingRawmaterialEntry.index') }}"
                                class="nav-link {{ request()->is('openingRawmaterialEntry.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __(' RawMaterial Opening') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('openingtripal.index') }}"
                                class="nav-link {{ request()->is('admin/openingtripal*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>{{ __(' SingleTripal Opening') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('openingdoubletripal.index') }}"
                                class="nav-link {{ request()->is('admin/openingdoubletripal*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>{{ __('DoubleTripal Opening ') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('openingfinaltripal.index') }}"
                                class="nav-link {{ request()->is('admin/openingfinaltripal*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>{{ __('FinalTripal Opening ') }}</p>
                            </a>
                        </li>



                        <li class="nav-item">
                            <a href="{{ route('fabric.opening') }}"
                                class="nav-link {{ request()->routeIs('admin.fabric.opening') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>Fabric Opening</p>
                            </a>
                        </li>

                    <li class="nav-item">
                        <a href="{{ route('wastageStock.index') }}"
                            class="nav-link {{ request()->is('admin/openingnonwoven*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tags"></i>
                            <p>Wastage Opening</p>
                        </a>
                    </li>
                   



                        <li class="nav-item">
                            <a href="{{ route('openingnonwoven.index') }}"
                                class="nav-link {{ request()->is('admin/openingnonwoven*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>NonWoven Opening</p>
                            </a>
                        </li>



                    </ul>
                </li>

                <li class="nav-item has-treeview {{ request()->is('home/main-entry/*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('home/main-entry/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-star"></i>
                        <p>
                            Stock
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ">
                        <li class="nav-item">
                            <a href="{{ route('autoloadStock.index') }}"
                                class="nav-link {{ request()->is('autoloadStock/index*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-spinner"></i>

                                <p>{{ __('Auto Loader Stock') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('rawMaterialStock.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-boxes"></i>
                                <p>{{ __('Raw Material Stock') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('fabric-stock.index') }}"
                                class="nav-link {{ request()->is('admin/fabric-stock*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>Fabric Stock</p>
                                {{-- <p>{{ __('Categories') }}</p> --}}
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('tapeentry-stock.index') }}"
                                class="nav-link {{ request()->is('admin/tape-entry-stock/index') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-random"></i>
                                <p>{{ __('Tape EntryStock') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('stock.index') }}"
                                class="nav-link {{ request()->is('stock/index*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-boxes"></i>
                                <p>{{ __('Storein Stock') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('bagBundelStock.index') }}"
                                class="nav-link {{ request()->routeIs('bagBundelStock.index') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-area"></i>
                                <p>{{ __('Bundle Stock') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('bagSalesStock.index') }}"
                                class="nav-link {{ request()->routeIs('bagSalesStock.index') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-area"></i>
                                <p>{{ __('Bag Sales Stock') }}</p>
                            </a>
                        </li>



                        <li class="nav-item">
                            <a href="{{ route('nonwovenfabrics-receiveentrystock.index') }}"
                                class="nav-link {{ request()->routeIs('bagSalesStock.index') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-area"></i>
                                <p>{{ __('NonwovenReceive Stock') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('singletripal-stock.index') }}"
                                class="nav-link {{ request()->routeIs('bagSalesStock.index') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-area"></i>
                                <p>{{ __('SingleTripal Stock') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('doubletripal-stock.index') }}"
                                class="nav-link {{ request()->routeIs('bagSalesStock.index') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-area"></i>
                                <p>{{ __('DoubleTripal Stock') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('finaltripal-stock.index') }}"
                                class="nav-link {{ request()->routeIs('bagSalesStock.index') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-area"></i>
                                <p>{{ __('FinalTripal Stock') }}</p>
                            </a>
                        </li>


                    </ul>
                </li>

                <li class="nav-item has-treeview {{ request()->is('home/main-entry/*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('home/main-entry/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-star"></i>
                        <p>
                            Sale
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ">
                        <li class="nav-item">
                            <a href="{{ route('salefinaltripal.index') }}"
                                class="nav-link {{ request()->is('admin/salefinaltripal*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>{{ __('Sales FinalTripal') }}</p>
                            </a>
                        </li>



                    </ul>
                </li>




                {{-- <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link  {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                </li> --}}
                <li class="nav-item">
                    <a href=" {{ route('staff.index') }} "
                        class="nav-link {{ request()->is('admin/staff*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>{{ __('Staff') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('suppliers.index') }}"
                        class="nav-link {{ request()->is('admin/suppliers*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-people-carry"></i>
                        <p>{{ __('Suppliers') }}</p>
                    </a>
                </li>
                @if (auth()->user()->isAdmin())
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}"
                            class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>{{ __('Users') }}</p>
                        </a>
                    </li>
                @endif
                <li class="nav-header text-bold">{{ __('EXPENSE') }}</li>
                <li class="nav-item">
                    <a href="{{ route('expCategories.index') }}"
                        class="nav-link {{ request()->is('admin/expense-categories*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-list-alt"></i>
                        <p>{{ __('Categories') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('BswLamFabSendForPrinting.index') }}"
                        class="nav-link {{ request()->routeIs('BswLamFabSendForPrinting.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Bsw Laminated Fabric Send For Printing</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('expenses.index') }}"
                        class="nav-link {{ request()->is('admin/expenses*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-wallet"></i>
                        <p>{{ __('Expenses') }}</p>
                    </a>
                </li>

                <li class="nav-header text-bold">{{ __('PURCHASE') }}</li>
                <li class="nav-item">
                    <a href="{{ route('purchases.index') }}"
                        class="nav-link {{ request()->is('admin/purchases*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-shopping-basket"></i>
                        <p>{{ __('Purchases') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('purchaseReturn.index') }}"
                        class="nav-link {{ request()->is('admin/return-purchases*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-undo-alt"></i>
                        <p>{{ __('Return Purchases') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('purchaseDamage.index') }}"
                        class="nav-link {{ request()->is('admin/damage-purchases*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-trash-alt"></i>
                        <p>{{ __('Damage Purchases') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('purchaseInventory.index') }}"
                        class="nav-link {{ request()->is('admin/purchase-inventory*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>{{ __('Purchase Inventory') }}</p>
                    </a>
                </li>

                <li class="nav-header text-bold">{{ __('TRIPAL') }}</li>
                <li class="nav-item">
                    <a href="{{ route('tripal.index') }}"
                        class="nav-link {{ request()->is('admin/tripal*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>{{ __('SINGLE TRIPAL') }}</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{ route('doubletripal.index') }}"
                        class="nav-link {{ request()->is('admin/doubletripal*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>{{ __('DOUBLETRIPAL') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('finaltripal.index') }}"
                        class="nav-link {{ request()->is('admin/finaltripal*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>{{ __('FINALTRIPAL') }}</p>
                    </a>
                </li>






                <li class="nav-header text-bold">{{ __('Fabric') }}</li>

                <li class="nav-item">
                    <a href="{{ route('fabric-groups.index') }}"
                        class="nav-link {{ request()->is('admin/fabric-groups*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Fabric Group</p>
                        {{-- <p>{{ __('Categories') }}</p> --}}
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{ route('fabrics.index') }}"
                        class="nav-link {{ request()->is('admin/fabrics*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Fabric</p>
                        {{-- <p>{{ __('Categories') }}</p> --}}
                    </a>
                </li>




                <li class="nav-item">
                    <a href="{{ route('fabricSendReceive.index') }}" class="nav-link">
                        {{ request()->is('admin/fabrics/sendAndReceive*') ? 'active' : '' }}
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Fabric Send Receive</p>
                        {{-- <p>{{ __('Categories') }}</p> --}}
                    </a>
                </li>



                <li class="nav-item">
                    <a href="{{ route('nonwovenfabrics.index') }}"
                        class="nav-link {{ request()->is('admin/nonwovenfabrics*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Non Woven Fabric</p>
                        {{-- <p>{{ __('Categories') }}</p> --}}
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('nonwovenfabrics-receiveentry.index') }}"
                        class="nav-link {{ request()->is('admin/nonwovenfabrics-receiveentry*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Non Woven Fabric Received Entry</p>
                    </a>
                </li>



                {{-- <li class="nav-item">
                      <a href="{{ route('nonwovenfabrics-receiveentry.index') }}"
                          class="nav-link {{ request()->is('admin/nonwovenfabrics-receiveentry*') ? 'active' : '' }}">
                          <i class="nav-icon fas fa-tags"></i>
                          <p>Non Woven Fabric Received Entry</p>
                      </a>
                  </li> --}}

                <li class="nav-header text-bold">{{ __('PRODUCT') }}</li>
                <li class="nav-item">
                    <a href="{{ route('items.index') }}"
                        class="nav-link {{ request()->is('admin/items*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>{{ __('Item') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('categories.index') }}"
                        class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>{{ __('Categories') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('subCategories.index') }}"
                        class="nav-link {{ request()->is('admin/sub-categories*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-code-branch"></i>
                        <p>{{ __('Sub Categories') }}</p>
                    </a>
                </li>




                <li class="nav-item">
                    <a href="{{ route('processing.index') }}"
                        class="nav-link {{ request()->is('admin/processing-products*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tools"></i>
                        <p>{{ __('Processing') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('finished.index') }}"
                        class="nav-link {{ request()->is('admin/finished-products*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-th-list"></i>
                        <p>{{ __('Finished') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('transferred.index') }}"
                        class="nav-link {{ request()->is('admin/transferred-products*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-random"></i>
                        <p>{{ __('Transferred') }}</p>
                    </a>
                </li>
                <li class="nav-header text-bold">{{ __('Storein/out') }}</li>
                <li class="nav-item">
                    <a href="{{ route('autoload.index') }}"
                        class="nav-link {{ request()->is('autoload/index*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-spinner"></i>

                        <p>{{ __('Auto Loader') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('rawMaterial.index') }}"
                        class="nav-link {{ request()->is('rawMaterial/index*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-shopping-basket"></i>
                        <p>{{ __('Raw Material') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('purchaseOrder.index') }}"
                        class="nav-link {{ request()->is('purchaseOrder.index*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-shopping-basket"></i>
                        <p>{{ __('Purchase Order') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('storein.index') }}"
                        class="nav-link {{ request()->is('storein/index*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-shopping-basket"></i>
                        <p>{{ __('Storein') }}</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{ route('storeout.index') }}"
                        class="nav-link {{ request()->is('storeout/index*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-shopping-basket"></i>
                        <p>{{ __('Storeout') }}</p>
                    </a>
                </li>

                <li class="nav-header text-bold">{{ __('Tape') }}</li>
                <li class="nav-item">
                    <a href="{{ route('tape.opening') }}"
                        class="nav-link {{ request()->routeIs('tape.opening') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-random"></i>
                        <p>{{ __('Tape Opening') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tape.entry') }}"
                        class="nav-link {{ request()->is('admin/tape-entry') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-random"></i>
                        <p>{{ __('Tape Entry') }}</p>
                    </a>
                </li>



                <!-------------  Bag --------------------------->
                <li class="nav-header text-bold">{{ __('Bags') }}</li>
                <li class="nav-item">
                    <a href="{{ route('fabric.transfer.entry.for.bag') }}"
                        class="nav-link {{ request()->routeIs('fabric.transfer.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-area"></i>
                        <p>{{ __('Entry For Fabric Tnasfer For Bag') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('prints.and.cuts.index') }}"
                        class="nav-link {{ request()->routeIs('prints.and.cuts.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-area"></i>
                        <p>{{ __('Prints and Cuts(Rolls)') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('bagBundelling.index') }}"
                        class="nav-link {{ request()->routeIs('bagBundelling.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-area"></i>
                        <p>{{ __('Bundle Making') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('bagSelling.index') }}"
                        class="nav-link {{ request()->routeIs('bagSelling.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-area"></i>
                        <p>{{ __('Bundle Selling') }}</p>
                    </a>
                </li>

                <!-------------  Bag --------------------------->
                <li class="nav-header text-bold">{{ __('Purchase Reports') }}</li>
                <li class="nav-item">
                    <a href="{{ route('purchase.report') }}"
                        class="nav-link {{ request()->is('admin/purchase-report') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-area"></i>
                        <p>{{ __('Purchase') }}</p>
                    </a>
                </li>
                <li
                    class="nav-item has-treeview {{ request()->is('admin/processing-report') ? 'menu-open' : '' }} {{ request()->is('admin/finished-report') ? 'menu-open' : '' }} {{ request()->is('admin/transferred-report') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>
                            {{ __('Product') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ">
                        <li class="nav-item">
                            <a href="{{ route('processing.report') }}"
                                class="nav-link {{ request()->is('admin/processing-report') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tools"></i>
                                <p>{{ __('Processing') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('finished.report') }}"
                                class="nav-link {{ request()->is('admin/finished-report') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-th-list"></i>
                                <p>{{ __('Finished') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('transferred.report') }}"
                                class="nav-link {{ request()->is('admin/transferred-report') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-random"></i>
                                <p>{{ __('Transferred') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void(0)"
                                class="nav-link {{ request()->is('admin/tape-entry') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-random"></i>
                                <p>{{ __('Tape Entry') }}</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-header text-bold">{{ __('ACCOUNT') }}</li>
                <li class="nav-item">
                    <a href="{{ route('admin.setup') }}"
                        class="nav-link {{ request()->is('admin/setup') ? 'active' : '' }} {{ request()->is('admin/general-settings') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>{{ __('Setup') }}</p>
                    </a>
                </li>
                <li class="nav-item has-treeview {{ request()->is('admin/profile') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            {{ ucfirst(auth()->user()->name) }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ">
                        <li class="nav-item">
                            <a href="{{ route('admin.profile') }}"
                                class="nav-link {{ request()->is('admin/profile') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-circle"></i>
                                <p>{{ __('Profile') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link admin-logout" href="{{ route('logout') }}">
                                <i class="nav-icon fas fa-power-off"></i> {{ __('Logout') }}
                            </a>
                            <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST"
                                class="no-display logout-form">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
