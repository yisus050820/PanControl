<?php
    include_once LAYOUTS . 'main_head.php';
    setHeader($d);
?>

<div class="content-wrapper">
    <div class="content-header">
        <h1>Finanzas</h1>
        <p class="text-muted">Control de ingresos y gastos</p>
    </div>

    <div class="content-body">
        <div class="form-container">
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Ingresos del Día</h5>
                            <div class="display-5 text-success mb-3">$0.00</div>
                            <p class="text-muted mb-0">Total de ventas: 0</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Gastos del Día</h5>
                            <div class="display-5 text-danger mb-3">$0.00</div>
                            <p class="text-muted mb-0">Total de compras: 0</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-container mt-5">
                <h5 class="text-center mb-4">Registro de Transacciones</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Concepto</th>
                                <th class="text-end">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    No hay transacciones registradas
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
