<?php
    include_once PC_LAYOUTS . 'main_head.php';
    setPanControlHeader($d);
?>

<h1 class="title">Clientes y Pedidos</h1>

<div class="actions">
  <button class="btn" onclick="showAddOrderModal()">➕ Crear Pedido</button>
</div>

<section class="dashboard">
  <!-- Tabla de pedidos -->
  <div class="card" style="grid-column: span 3">
    <h3>📋 Pedidos recientes</h3>
    <table class="table">
      <thead>
        <tr>
          <th>Cliente</th>
          <th>Fecha</th>
          <th>Total</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($d->orders)): ?>
          <tr>
            <td colspan="5">No hay pedidos registrados</td>
          </tr>
        <?php else: ?>
          <?php foreach ($d->orders as $order): ?>
            <tr data-id="<?= $order->id ?>">
              <td><?= htmlspecialchars($order->client_name) ?></td>
              <td><?= $order->fecha ?></td>
              <td>$<?= number_format($order->total, 2) ?></td>
              <td>
                <span class="status-<?= $order->status ?>">
                  <?php
                    $statusLabels = [
                      'pending' => 'Pendiente',
                      'processing' => 'En proceso',
                      'completed' => 'Completado',
                      'cancelled' => 'Cancelado'
                    ];
                    echo $statusLabels[$order->status] ?? $order->status;
                  ?>
                </span>
              </td>
              <td>
                <button class="btn small" onclick="editOrderStatus(<?= $order->id ?>, '<?= $order->status ?>')">✏️ Editar</button>
                <button class="btn small danger" onclick="deleteOrder(<?= $order->id ?>)">🗑️ Eliminar</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Lista general de clientes -->
  <div class="card" style="grid-column: span 3">
    <h3>🧾 Clientes registrados</h3>
    <div style="margin-bottom: 15px;">
      <button class="btn small" onclick="showAddClientModal()">➕ Agregar Cliente</button>
    </div>

    <div class="client-list">
      <?php if (empty($d->clients)): ?>
        <p>No hay clientes registrados</p>
      <?php else: ?>
        <?php foreach ($d->clients as $client): ?>
          <div class="client-card" data-id="<?= $client->id ?>">
            <p><strong>Nombre:</strong> <?= htmlspecialchars($client->name) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($client->email) ?></p>
            <p><strong>Teléfono:</strong> <?= htmlspecialchars($client->phone) ?></p>
            <p><strong>Estatus:</strong> <?= htmlspecialchars($client->status) ?></p>
            <div style="margin-top: 10px;">
              <button class="btn small" onclick="editClient(<?= $client->id ?>)">✏️ Editar</button>
              <button class="btn small danger" onclick="deleteClient(<?= $client->id ?>)">🗑️ Eliminar</button>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Modal para agregar cliente -->
<div class="form-overlay" id="add-client-overlay">
  <div class="form-modal">
    <h3>Agregar Nuevo Cliente</h3>
    <form id="add-client-form">
      <div class="form-group">
        <label for="client-name">Nombre:</label>
        <input type="text" id="client-name" name="name" required>
      </div>
      <div class="form-group">
        <label for="client-email">Email:</label>
        <input type="email" id="client-email" name="email">
      </div>
      <div class="form-group">
        <label for="client-phone">Teléfono:</label>
        <input type="text" id="client-phone" name="phone">
      </div>
      <div class="form-group">
        <label for="client-address">Dirección:</label>
        <textarea id="client-address" name="address" rows="3"></textarea>
      </div>
      <div style="display: flex; gap: 10px; justify-content: flex-end;">
        <button type="button" class="btn secondary" onclick="panControl.hideModal('add-client-overlay')">Cancelar</button>
        <button type="submit" class="btn">Guardar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal para agregar pedido -->
<div class="form-overlay" id="add-order-overlay">
  <div class="form-modal">
    <h3>Crear Nuevo Pedido</h3>
    <form id="add-order-form">
      <div class="form-group">
        <label for="order-client">Cliente:</label>
        <select id="order-client" name="client_id" required>
          <option value="">Seleccionar cliente...</option>
          <?php if (!empty($d->clients)): ?>
            <?php foreach ($d->clients as $client): ?>
              <option value="<?= $client->id ?>"><?= htmlspecialchars($client->name) ?></option>
            <?php endforeach; ?>
          <?php endif; ?>
        </select>
      </div>
      <div class="form-group">
        <label for="order-total">Total:</label>
        <input type="number" step="0.01" id="order-total" name="total" required>
      </div>
      <div class="form-group">
        <label for="order-delivery">Fecha de entrega:</label>
        <input type="date" id="order-delivery" name="delivery_date">
      </div>
      <div style="display: flex; gap: 10px; justify-content: flex-end;">
        <button type="button" class="btn secondary" onclick="panControl.hideModal('add-order-overlay')">Cancelar</button>
        <button type="submit" class="btn">Crear Pedido</button>
      </div>
    </form>
  </div>
</div>

<?php
    include_once PC_LAYOUTS . 'main_foot.php';
    setPanControlFooter($d);
?>

<script>
function showAddClientModal() {
  panControl.showModal('add-client-overlay');
}

function showAddOrderModal() {
  panControl.showModal('add-order-overlay');
}

function editOrderStatus(id, currentStatus) {
  const statuses = {
    'pending': 'Pendiente',
    'processing': 'En proceso', 
    'completed': 'Completado',
    'cancelled': 'Cancelado'
  };
  
  let options = '';
  for (let [key, label] of Object.entries(statuses)) {
    options += `<option value="${key}" ${key === currentStatus ? 'selected' : ''}>${label}</option>`;
  }
  
  if (typeof Swal !== 'undefined') {
    Swal.fire({
      title: 'Cambiar estado del pedido',
      html: `<select id="status-select" class="swal2-input">${options}</select>`,
      showCancelButton: true,
      confirmButtonText: 'Actualizar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        const newStatus = document.getElementById('status-select').value;
        updateOrderStatus(id, newStatus);
      }
    });
  }
}

function updateOrderStatus(id, status) {
  const formData = new FormData();
  formData.append('status', status);
  
  fetch(`/PanControl/Order/updateStatus/${id}`, {
    method: 'POST',
    body: formData
  })
  .then(resp => resp.json())
  .then(resp => {
    if (resp.r) {
      panControl.showMessage('Estado actualizado correctamente');
      location.reload();
    } else {
      panControl.showMessage(resp.message || 'Error al actualizar estado', 'error');
    }
  })
  .catch(err => {
    console.error(err);
    panControl.showMessage('Error al conectar con el servidor', 'error');
  });
}

function deleteOrder(id) {
  panControl.confirmDelete(() => {
    fetch(`/PanControl/Order/delete/${id}`, {
      method: 'POST'
    })
    .then(resp => resp.json())
    .then(resp => {
      if (resp.r) {
        panControl.showMessage('Pedido eliminado correctamente');
        location.reload();
      } else {
        panControl.showMessage(resp.message || 'Error al eliminar pedido', 'error');
      }
    })
    .catch(err => {
      console.error(err);
      panControl.showMessage('Error al conectar con el servidor', 'error');
    });
  });
}

function deleteClient(id) {
  panControl.confirmDelete(() => {
    fetch(`/PanControl/Client/delete/${id}`, {
      method: 'POST'
    })
    .then(resp => resp.json())
    .then(resp => {
      if (resp.r) {
        panControl.showMessage('Cliente eliminado correctamente');
        location.reload();
      } else {
        panControl.showMessage(resp.message || 'Error al eliminar cliente', 'error');
      }
    })
    .catch(err => {
      console.error(err);
      panControl.showMessage('Error al conectar con el servidor', 'error');
    });
  });
}

// Formulario de agregar cliente
document.getElementById('add-client-form').addEventListener('submit', function(e) {
  e.preventDefault();
  
  const formData = new FormData(this);
  
  fetch('/PanControl/Client/create', {
    method: 'POST',
    body: formData
  })
  .then(resp => resp.json())
  .then(resp => {
    if (resp.r) {
      panControl.showMessage('Cliente agregado correctamente');
      panControl.hideModal('add-client-overlay');
      location.reload();
    } else {
      panControl.showMessage(resp.message || 'Error al agregar cliente', 'error');
    }
  })
  .catch(err => {
    console.error(err);
    panControl.showMessage('Error al conectar con el servidor', 'error');
  });
});

// Formulario de agregar pedido
document.getElementById('add-order-form').addEventListener('submit', function(e) {
  e.preventDefault();
  
  const formData = new FormData(this);
  
  fetch('/PanControl/Order/create', {
    method: 'POST',
    body: formData
  })
  .then(resp => resp.json())
  .then(resp => {
    if (resp.r) {
      panControl.showMessage('Pedido creado correctamente');
      panControl.hideModal('add-order-overlay');
      location.reload();
    } else {
      panControl.showMessage(resp.message || 'Error al crear pedido', 'error');
    }
  })
  .catch(err => {
    console.error(err);
    panControl.showMessage('Error al conectar con el servidor', 'error');
  });
});
</script>

<style>
.status-pending { color: #f59e0b; font-weight: bold; }
.status-processing { color: #2563eb; font-weight: bold; }
.status-completed { color: #16a34a; font-weight: bold; }
.status-cancelled { color: #dc2626; font-weight: bold; }
</style>

<?php closePanControlFooter(); ?>
