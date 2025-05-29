<?php
    function setPanControlHeader($args){
        $ua = as_object( $args->ua );
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?=$args->title?></title>
  <link rel="stylesheet" href="<?=PC_CSS?>dashboard.css">
  <style>
    .btn.loading { 
      opacity: 0.6; 
      cursor: not-allowed; 
    }
    .form-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      display: none;
      z-index: 1000;
    }
    .form-modal {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: white;
      padding: 20px;
      border-radius: 10px;
      min-width: 400px;
      z-index: 1001;
    }
    .form-group {
      margin-bottom: 15px;
    }
    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }
    .form-group input, .form-group select, .form-group textarea {
      width: 100%;
      padding: 8px;
      border: 1px solid var(--border);
      border-radius: 4px;
    }
  </style>
</head>
<body>

  <aside class="sidebar">
    <h2 class="logo">PanControl</h2>
    <nav>
      <a href="/PanControl/dashboard" <?= strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false ? 'class="active"' : '' ?>>🏠 Inicio</a>
      <a href="/PanControl/inventario" <?= strpos($_SERVER['REQUEST_URI'], 'inventario') !== false ? 'class="active"' : '' ?>>📦 Inventario</a>
      <a href="/PanControl/clientes" <?= strpos($_SERVER['REQUEST_URI'], 'clientes') !== false ? 'class="active"' : '' ?>>📋 Clientes y Pedidos</a>
      <a href="/PanControl/finanzas" <?= strpos($_SERVER['REQUEST_URI'], 'finanzas') !== false ? 'class="active"' : '' ?>>💰 Finanzas</a>
    </nav>
  </aside>

  <div class="main">
    <header class="header">
      <div></div>
      <div class="user-controls">
        <span><?= isset($ua->username) ? $ua->username : 'Usuario' ?></span> | 
        <a href="/PanControl/logout">Cerrar Sesión</a>
      </div>
    </header>

    <main class="content">
<?php
    }
?>
