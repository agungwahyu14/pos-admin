<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - 5.4.12 Coffee</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #fff;
            color: var(--bs-body-color);
        }
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background-color: #FFFFFF;
            border-right: 1px solid var(--bs-primary);
            z-index: 1040;
            transition: transform 0.3s ease;
        }
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }
        .navbar-top {
            height: 72px;
            background-color: var(--bs-primary);
            color: #FFFFFF;
            border-bottom: 1px solid var(--bs-primary);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 24px;
        }
        .content-area {
            padding: 24px;
            flex: 1;
        }
        .sidebar-brand {
            height: 72px;
            display: flex;
            align-items: center;
            padding: 0 24px;
            font-size: 1.25rem;
            font-weight: 700;
            background-color: var(--bs-primary);
            color: #FFFFFF;
            border-bottom: 1px solid var(--bs-primary);
        }
        .sidebar-nav {
            padding: 16px;
            height: calc(100vh - 72px);
            overflow-y: auto;
        }
        .nav-item-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: var(--bs-body-color);
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .nav-item-link:hover, .nav-item-link.active {
            background-color: var(--bs-secondary);
            color: var(--bs-primary);
        }
        .nav-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 600;
            color: #6B7280;
            margin: 16px 0 8px 16px;
        }
        
        .sidebar-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0,0,0,0.5);
            z-index: 1030;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .sidebar-backdrop.show {
                display: block;
                opacity: 1;
            }
        }
    </style>
</head>
<body>

    <!-- Mobile Backdrop -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('images/White.png') }}" alt="Logo" class="me-3" style="max-height: 32px; object-fit: contain;" onerror="this.style.display='none'">
            <span>5.4.12 Coffee</span>
        </div>
        <div class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="nav-item-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2 me-3 fs-5"></i> Dashboard
            </a>
            
            <div class="nav-title">Master Data</div>
            <a href="{{ route('admin.categories.index') }}" class="nav-item-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-tags me-3 fs-5"></i> Categories
            </a>
            <a href="{{ route('admin.products.index') }}" class="nav-item-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam me-3 fs-5"></i> Products
            </a>
            <a href="{{ route('admin.users.index') }}" class="nav-item-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people me-3 fs-5"></i> Users
            </a>
            
            <div class="nav-title">Transactions</div>
            <a href="{{ route('admin.orders.index') }}" class="nav-item-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-receipt me-3 fs-5"></i> Orders
            </a>
            <a href="{{ route('admin.shifts.index') }}" class="nav-item-link {{ request()->routeIs('admin.shifts.*') ? 'active' : '' }}">
                <i class="bi bi-clock-history me-3 fs-5"></i> Shifts
            </a>
            
            <div class="nav-title">System</div>
            <a href="{{ route('admin.reports.index') }}" class="nav-item-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <i class="bi bi-graph-up me-3 fs-5"></i> Reports
            </a>
            <a href="{{ route('admin.settings.index') }}" class="nav-item-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear me-3 fs-5"></i> Settings
            </a>
            
            <form method="POST" action="{{ route('admin.logout') }}" class="mt-4">
                @csrf
                <button type="submit" class="nav-item-link w-100 text-danger bg-transparent border-0 text-start">
                    <i class="bi bi-box-arrow-right me-3 fs-5"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="navbar-top">
            <!-- Mobile Toggle Button -->
            <button class="btn btn-link text-white d-lg-none me-auto p-0 border-0" id="sidebarToggle">
                <i class="bi bi-list fs-1"></i>
            </button>
            
            <div class="d-flex align-items-center">
                <span class="me-3 fw-medium text-white">{{ auth()->user()->name ?? 'Admin' }}</span>
                <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 40px; height: 40px;">
                    {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                </div>
            </div>
        </div>
        
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('sidebarToggle');
            const backdrop = document.getElementById('sidebarBackdrop');

            if (sidebar && toggleBtn && backdrop) {
                function toggleSidebar() {
                    sidebar.classList.toggle('show');
                    backdrop.classList.toggle('show');
                }
                toggleBtn.addEventListener('click', toggleSidebar);
                backdrop.addEventListener('click', toggleSidebar);
            }

            // Initialize Toasts
            const toastElList = [].slice.call(document.querySelectorAll('.toast'));
            const toastList = toastElList.map(function (toastEl) {
                return new bootstrap.Toast(toastEl, { delay: 3000 });
            });
            toastList.forEach(toast => toast.show());

            // SweetAlert2 for Delete Confirmations
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this data!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#05808C',
                        cancelButtonColor: '#EF4444',
                        confirmButtonText: 'Yes, delete it!',
                        background: '#FFFFFF',
                        customClass: {
                            popup: 'rounded-4 shadow-lg border-0',
                            confirmButton: 'btn btn-primary px-4 py-2 rounded-3',
                            cancelButton: 'btn btn-danger px-4 py-2 rounded-3 ms-2'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>

    <!-- Global Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-4" style="z-index: 1055;">
        @if(session('success'))
        <div class="toast align-items-center border-0 shadow-sm rounded-3 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex bg-white rounded-3">
                <div class="toast-body d-flex align-items-center px-4 py-3">
                    <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                    <div class="fw-medium text-dark">{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close me-3 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <!-- Progress bar -->
            <div class="bg-success" style="height: 3px; width: 100%; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px; animation: progress 3s linear;"></div>
        </div>
        @endif

        @if(session('error'))
        <div class="toast align-items-center border-0 shadow-sm rounded-3 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex bg-white rounded-3">
                <div class="toast-body d-flex align-items-center px-4 py-3">
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-4 me-3"></i>
                    <div class="fw-medium text-dark">{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close me-3 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="bg-danger" style="height: 3px; width: 100%; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px; animation: progress 3s linear;"></div>
        </div>
        @endif
    </div>

    <style>
        @keyframes progress {
            from { width: 100%; }
            to { width: 0%; }
        }
    </style>
    @stack('scripts')
</body>
</html>
