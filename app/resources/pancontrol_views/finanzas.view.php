<?php
    include_once PC_LAYOUTS . 'main_head.php';
    setPanControlHeader($d);
?>

<h1 class="title">Resumen Financiero</h1>

<section class="dashboard">
  <div class="card">
    <h3>💵 Ingresos del mes</h3>
    <p><strong>$<?= number_format($d->monthlyEarnings->income, 2) ?></strong></p>
  </div>

  <div class="card">
    <h3>📤 Egresos del mes</h3>
    <p class="danger"><strong>$<?= number_format($d->monthlyEarnings->expenses, 2) ?></strong></p>
  </div>

  <div class="card">
    <h3>📈 Ganancia neta</h3>
    <p><strong>$<?= number_format($d->monthlyEarnings->net_earnings, 2) ?></strong></p>
  </div>

  <div class="card" style="grid-column: span 3">
    <h3>📊 Ingresos vs Egresos (últimos 5 meses)</h3>
    <div class="bar-graph">
      <div class="bar-group">
        <div class="bar ingreso" style="height: 80%"></div>
        <div class="bar egreso" style="height: 40%"></div>
        <span>Ene</span>
      </div>
      <div class="bar-group">
        <div class="bar ingreso" style="height: 90%"></div>
        <div class="bar egreso" style="height: 50%"></div>
        <span>Feb</span>
      </div>
      <div class="bar-group">
        <div class="bar ingreso" style="height: 70%"></div>
        <div class="bar egreso" style="height: 30%"></div>
        <span>Mar</span>
      </div>
      <div class="bar-group">
        <div class="bar ingreso" style="height: 85%"></div>
        <div class="bar egreso" style="height: 45%"></div>
        <span>Abr</span>
      </div>
      <div class="bar-group">
        <div class="bar ingreso" style="height: 95%"></div>
        <div class="bar egreso" style="height: 60%"></div>
        <span>May</span>
      </div>
    </div>
    <div style="margin-top: 20px; display: flex; justify-content: center; gap: 20px;">
      <div style="display: flex; align-items: center; gap: 5px;">
        <div style="width: 20px; height: 20px; background-color: #2563eb; border-radius: 4px;"></div>
        <span>Ingresos</span>
      </div>
      <div style="display: flex; align-items: center; gap: 5px;">
        <div style="width: 20px; height: 20px; background-color: #dc2626; border-radius: 4px;"></div>
        <span>Egresos</span>
      </div>
    </div>
  </div>

  <!-- Resumen adicional -->
  <div class="card" style="grid-column: span 3">
    <h3>📋 Resumen del Mes Actual</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 15px;">
      <div style="text-align: center; padding: 10px; background-color: #f0f9ff; border-radius: 8px;">
        <h4 style="color: #2563eb; margin-bottom: 5px;">Total Ingresos</h4>
        <p style="font-size: 24px; font-weight: bold; color: #16a34a;">
          $<?= number_format($d->monthlyEarnings->income, 2) ?>
        </p>
      </div>
      <div style="text-align: center; padding: 10px; background-color: #fef2f2; border-radius: 8px;">
        <h4 style="color: #dc2626; margin-bottom: 5px;">Total Egresos</h4>
        <p style="font-size: 24px; font-weight: bold; color: #dc2626;">
          $<?= number_format($d->monthlyEarnings->expenses, 2) ?>
        </p>
      </div>
      <div style="text-align: center; padding: 10px; background-color: #f0fdf4; border-radius: 8px;">
        <h4 style="color: #16a34a; margin-bottom: 5px;">Ganancia Neta</h4>
        <p style="font-size: 24px; font-weight: bold; color: <?= $d->monthlyEarnings->net_earnings >= 0 ? '#16a34a' : '#dc2626' ?>;">
          $<?= number_format($d->monthlyEarnings->net_earnings, 2) ?>
        </p>
      </div>
    </div>
  </div>
</section>

<?php
    include_once PC_LAYOUTS . 'main_foot.php';
    setPanControlFooter($d);
?>

<script>
// Aquí podrías agregar lógica para cargar datos dinámicos del gráfico
document.addEventListener('DOMContentLoaded', function() {
  // Simular datos dinámicos para el gráfico de barras
  // En una implementación real, estos datos vendrían del backend
  console.log('Finanzas cargadas correctamente');
});
</script>

<?php closePanControlFooter(); ?>
