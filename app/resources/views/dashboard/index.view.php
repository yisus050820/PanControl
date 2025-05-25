<?php
    include_once LAYOUTS . 'main_head.php';
    setHeader($d);
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1 class="dashboard-title">Panel de Control</h1>
    </div>
    
    <div class="dashboard-stats">
        <!-- Control de Inventario -->
        <div class="stat-card">
            <div class="card shadow-lg">
                <div class="card-body">
                    <div class="stat-header">
                        <div class="icon-wrapper bg-blue-100">
                            <i class="bi bi-box-seam text-primary"></i>
                        </div>
                        <div class="stat-info">
                            <h2 class="stat-title">Control de Inventario</h2>
                            <p class="stat-subtitle">Productos registrados</p>
                        </div>
                    </div>
                    <div class="stat-value">0</div>
                    <a href="<?=BASE_URL?>public/Inventory" class="stat-action">
                        Ver Inventario
                        <i class="bi bi-arrow-right transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Pedidos Pendientes -->
        <div class="stat-card">
            <div class="card shadow-lg">
                <div class="card-body">
                    <div class="stat-header">
                        <div class="icon-wrapper bg-yellow-100">
                            <i class="bi bi-cart text-warning"></i>
                        </div>
                        <div class="stat-info">
                            <h2 class="stat-title">Pedidos Pendientes</h2>
                            <p class="stat-subtitle">Pedidos sin procesar</p>
                        </div>
                    </div>
                    <div class="stat-value">0</div>
                    <a href="<?=BASE_URL?>public/Orders" class="stat-action">
                        Ver Pedidos
                        <i class="bi bi-arrow-right transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Ingresos del día -->
        <div class="stat-card">
            <div class="card shadow-lg">
                <div class="card-body">
                    <div class="stat-header">
                        <div class="icon-wrapper bg-green-100">
                            <i class="bi bi-graph-up-arrow text-success"></i>
                        </div>
                        <div class="stat-info">
                            <h2 class="stat-title">Ingresos del día</h2>
                            <p class="stat-subtitle">Ventas realizadas</p>
                        </div>
                    </div>
                    <div class="stat-value">$0.00</div>
                    <a href="<?=BASE_URL?>public/Finance" class="stat-action">
                        Ver Detalles
                        <i class="bi bi-arrow-right transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Gastos del día -->
        <div class="stat-card">
            <div class="card shadow-lg">
                <div class="card-body">
                    <div class="stat-header">
                        <div class="icon-wrapper bg-red-100">
                            <i class="bi bi-graph-down-arrow text-danger"></i>
                        </div>
                        <div class="stat-info">
                            <h2 class="stat-title">Gastos del día</h2>
                            <p class="stat-subtitle">Compras realizadas</p>
                        </div>
                    </div>
                    <div class="stat-value">$0.00</div>
                    <a href="<?=BASE_URL?>public/Finance" class="stat-action">
                        Ver Detalles
                        <i class="bi bi-arrow-right transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Actividad Reciente -->
    <div class="recent-activity">
        <div class="card shadow-lg">
            <div class="card-body">
                <h2 class="activity-title">Actividad Reciente</h2>
                <p class="activity-subtitle">Resumen de operaciones</p>
                <div class="activity-content">
                    <p class="no-data-message">No hay actividad reciente para mostrar</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    include_once LAYOUTS . 'main_foot.php';
    setFooter($d);
    closefooter();
?>
