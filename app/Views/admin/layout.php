<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin Panel') ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <style>
        .sidebar {
            min-height: 100vh;
            background: #2c3e50;
            color: white;
            position: fixed;
            width: 250px;
        }
        
        .sidebar .nav-link {
            color: white;
            padding: 1rem 1.5rem;
            border-left: 4px solid transparent;
        }
        
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: #34495e;
            border-left-color: #3498db;
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            background: #f8f9fa;
            min-height: 100vh;
        }
        
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="p-4 text-center">
            <img src="<?= base_url('image/bluebirdlogo.png') ?>" alt="Logo" width="80" class="mb-3">
            <h5>SkyBird Hotel</h5>
            <small>Admin Panel</small>
        </div>
        
        <nav class="nav flex-column">
            <a class="nav-link <?= current_url() == base_url('admin') ? 'active' : '' ?>" href="<?= base_url('admin') ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a class="nav-link <?= strpos(current_url(), 'bookings') !== false ? 'active' : '' ?>" href="<?= base_url('admin/bookings') ?>">
                <i class="fas fa-bed"></i> Bookings
            </a>
            <a class="nav-link <?= strpos(current_url(), 'payments') !== false ? 'active' : '' ?>" href="<?= base_url('admin/payments') ?>">
                <i class="fas fa-money-bill-wave"></i> Payments
            </a>
            <a class="nav-link <?= strpos(current_url(), 'rooms') !== false ? 'active' : '' ?>" href="<?= base_url('admin/rooms') ?>">
                <i class="fas fa-door-open"></i> Rooms
            </a>
            <a class="nav-link <?= strpos(current_url(), 'staff') !== false ? 'active' : '' ?>" href="<?= base_url('admin/staff') ?>">
                <i class="fas fa-users"></i> Staff
            </a>
            <a class="nav-link" href="<?= base_url('auth/logout') ?>">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </div>
    
    <div class="main-content">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <span class="navbar-brand"><?= esc($title ?? 'Admin Panel') ?></span>
                <div class="navbar-nav ms-auto">
                    <span class="navbar-text">
                        Welcome, <?= session()->get('username') ?>!
                    </span>
                </div>
            </div>
        </nav>
        
        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- Main Content -->
        <?= $this->renderSection('content') ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        // Initialize DataTables
        document.addEventListener('DOMContentLoaded', function() {
            $('.table').DataTable({
                responsive: true,
                pageLength: 25
            });
        });
    </script>
</body>
</html>