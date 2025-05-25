<?php
    function setHeader($args){
        $ua = as_object($args->ua);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$args->title?></title>
    
    <!-- Bootstrap & Icons -->
    <link rel="stylesheet" href="<?=BASE_URL?>public/assets/css/bootstrap.css">
    <link rel="stylesheet" href="<?=BASE_URL?>public/assets/css/admin.css">
    <link rel="stylesheet" href="<?=BASE_URL?>public/assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3><i class="bi bi-shop"></i> PanControl</h3>
            </div>

            <ul class="list-unstyled components">
                <li class="<?= !isset($_GET['uri']) || $_GET['uri'] == '' ? 'active' : '' ?>">
                    <a href="<?=BASE_URL?>public/" class="nav-link">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="<?= isset($_GET['uri']) && strpos($_GET['uri'], 'Inventory') !== false ? 'active' : '' ?>">
                    <a href="<?=BASE_URL?>public/Inventory" class="nav-link">
                        <i class="bi bi-box-seam"></i> Inventario
                    </a>
                </li>
                <li class="<?= isset($_GET['uri']) && strpos($_GET['uri'], 'Orders') !== false ? 'active' : '' ?>">
                    <a href="<?=BASE_URL?>public/Orders" class="nav-link">
                        <i class="bi bi-cart"></i> Pedidos
                    </a>
                </li>
                <li class="<?= isset($_GET['uri']) && strpos($_GET['uri'], 'Finance') !== false ? 'active' : '' ?>">
                    <a href="<?=BASE_URL?>public/Finance" class="nav-link">
                        <i class="bi bi-currency-dollar"></i> Finanzas
                    </a>
                </li>
                <li>
                    <a href="<?=BASE_URL?>public/Session/logout" class="nav-link text-danger">
                        <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <div class="container-fluid">
                    </button>
                    
                    <div class="ms-auto">
                        <div class="dropdown">
                            <button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> 
                                <?=isset($ua->username) ? $ua->username : 'Usuario'?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="<?=BASE_URL?>public/Session/logout">
                                        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="container-fluid py-3">
                <!-- Aquí va el contenido de la página -->
                
            </div> <!-- Cierra container-fluid -->
        </div> <!-- Cierra content -->
    </div> <!-- Cierra wrapper -->
    
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar
        document.getElementById('sidebarCollapse').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>
<?php
    } // Cierra la función setHeader
?>