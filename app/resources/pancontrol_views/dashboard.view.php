<?php
    include_once PC_LAYOUTS . 'main_head.php';
    setPanControlHeader($d);
?>

<h1 class="title">Panel Resumen</h1>

<section class="dashboard">
  <div class="card">
    <h3>📉 Ingredientes bajos</h3>
    <ul class="list">
      <?php if (empty($d->lowStockProducts)): ?>
        <li>No hay productos con stock bajo</li>
      <?php else: ?>
        <?php foreach ($d->lowStockProducts as $product): ?>
          <li>
            <?= htmlspecialchars($product->name) ?> 
            <span class="alert"><?= $product->quantity ?> <?= $product->unit ?> ⚠️</span>
          </li>
        <?php endforeach; ?>
      <?php endif; ?>
    </ul>
  </div>

  <div class="card">
    <h3>📊 Ganancias del día</h3>
    <p>Ganancias: <strong>$<?= number_format($d->todayEarnings->earnings, 2) ?></strong></p>
    <p>Pérdidas: <strong class="danger">$<?= number_format($d->todayEarnings->losses, 2) ?></strong></p>
  </div>

  <div class="card">
    <h3>📈 Resumen General</h3>
    <div class="bar-chart">
      <div class="bar" style="height: 40%"></div>
      <div class="bar" style="height: 60%"></div>
      <div class="bar" style="height: 80%"></div>
      <div class="bar" style="height: 50%"></div>
      <div class="bar" style="height: 70%"></div>
    </div>
    <p style="margin-top: 10px;">
      <small>Representación visual de ingresos vs egresos</small>
    </p>
  </div>
</section>

<?php
    include_once PC_LAYOUTS . 'main_foot.php';
    setPanControlFooter($d);
    closePanControlFooter();
?>
