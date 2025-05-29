<?php
    include_once PC_LAYOUTS . 'main_head.php';
    setPanControlHeader($d);
?>

<h1 class="title">Inventario</h1>

<div class="actions">
  <button class="btn" onclick="showAddProductModal()">➕ Agregar Ingrediente</button>
  <br><br>
</div>

<section class="dashboard">
  <div class="card" style="grid-column: span 3">
    <table class="table">
      <thead>
        <tr>
          <th>Ingrediente</th>
          <th>Cantidad</th>
          <th>Unidad</th>
          <th>Mín. Stock</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody id="products-table">
        <?php if (empty($d->products)): ?>
          <tr>
            <td colspan="6">No hay productos registrados</td>
          </tr>
        <?php else: ?>
          <?php foreach ($d->products as $product): ?>
            <tr data-id="<?= $product->id ?>">
              <td><?= htmlspecialchars($product->name) ?></td>
              <td><?= $product->quantity ?></td>
              <td><?= htmlspecialchars($product->unit) ?></td>
              <td><?= $product->min_quantity ?></td>
              <td class="<?= $product->status === 'OK' ? '' : 'alert' ?>">
                <?= $product->status ?>
              </td>
              <td>
                <button class="btn small" onclick="editProduct(<?= $product->id ?>)">✏️ Editar</button>
                <button class="btn small danger" onclick="deleteProduct(<?= $product->id ?>)">🗑️ Eliminar</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<!-- Modal para agregar producto -->
<div class="form-overlay" id="add-product-overlay">
  <div class="form-modal">
    <h3>Agregar Nuevo Producto</h3>
    <form id="add-product-form">
      <div class="form-group">
        <label for="product-name">Nombre del producto:</label>
        <input type="text" id="product-name" name="name" required>
      </div>
      <div class="form-group">
        <label for="product-quantity">Cantidad:</label>
        <input type="number" step="0.01" id="product-quantity" name="quantity" required>
      </div>
      <div class="form-group">
        <label for="product-unit">Unidad:</label>
        <input type="text" id="product-unit" name="unit" placeholder="kg, litros, unidades, etc." required>
      </div>
      <div class="form-group">
        <label for="product-min">Cantidad mínima:</label>
        <input type="number" step="0.01" id="product-min" name="min_quantity" required>
      </div>
      <div style="display: flex; gap: 10px; justify-content: flex-end;">
        <button type="button" class="btn secondary" onclick="panControl.hideModal('add-product-overlay')">Cancelar</button>
        <button type="submit" class="btn">Guardar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal para editar producto -->
<div class="form-overlay" id="edit-product-overlay">
  <div class="form-modal">
    <h3>Editar Producto</h3>
    <form id="edit-product-form">
      <input type="hidden" id="edit-product-id">
      <div class="form-group">
        <label for="edit-product-name">Nombre del producto:</label>
        <input type="text" id="edit-product-name" name="name" required>
      </div>
      <div class="form-group">
        <label for="edit-product-quantity">Cantidad:</label>
        <input type="number" step="0.01" id="edit-product-quantity" name="quantity" required>
      </div>
      <div class="form-group">
        <label for="edit-product-unit">Unidad:</label>
        <input type="text" id="edit-product-unit" name="unit" required>
      </div>
      <div class="form-group">
        <label for="edit-product-min">Cantidad mínima:</label>
        <input type="number" step="0.01" id="edit-product-min" name="min_quantity" required>
      </div>
      <div style="display: flex; gap: 10px; justify-content: flex-end;">
        <button type="button" class="btn secondary" onclick="panControl.hideModal('edit-product-overlay')">Cancelar</button>
        <button type="submit" class="btn">Actualizar</button>
      </div>
    </form>
  </div>
</div>

<?php
    include_once PC_LAYOUTS . 'main_foot.php';
    setPanControlFooter($d);
?>

<script>
function showAddProductModal() {
  panControl.showModal('add-product-overlay');
}

function editProduct(id) {
  // Obtener datos del producto de la tabla
  const row = document.querySelector(`tr[data-id="${id}"]`);
  const cells = row.querySelectorAll('td');
  
  document.getElementById('edit-product-id').value = id;
  document.getElementById('edit-product-name').value = cells[0].textContent;
  document.getElementById('edit-product-quantity').value = cells[1].textContent;
  document.getElementById('edit-product-unit').value = cells[2].textContent;
  document.getElementById('edit-product-min').value = cells[3].textContent;
  
  panControl.showModal('edit-product-overlay');
}

function deleteProduct(id) {
  panControl.confirmDelete(() => {
    fetch(`/PanControl/Product/delete/${id}`, {
      method: 'POST'
    })
    .then(resp => resp.json())
    .then(resp => {
      if (resp.r) {
        panControl.showMessage('Producto eliminado correctamente');
        location.reload();
      } else {
        panControl.showMessage(resp.message || 'Error al eliminar producto', 'error');
      }
    })
    .catch(err => {
      console.error(err);
      panControl.showMessage('Error al conectar con el servidor', 'error');
    });
  });
}

// Formulario de agregar producto
document.getElementById('add-product-form').addEventListener('submit', function(e) {
  e.preventDefault();
  
  const formData = new FormData(this);
  
  fetch('/PanControl/Product/create', {
    method: 'POST',
    body: formData
  })
  .then(resp => resp.json())
  .then(resp => {
    if (resp.r) {
      panControl.showMessage('Producto agregado correctamente');
      panControl.hideModal('add-product-overlay');
      location.reload();
    } else {
      panControl.showMessage(resp.message || 'Error al agregar producto', 'error');
    }
  })
  .catch(err => {
    console.error(err);
    panControl.showMessage('Error al conectar con el servidor', 'error');
  });
});

// Formulario de editar producto
document.getElementById('edit-product-form').addEventListener('submit', function(e) {
  e.preventDefault();
  
  const id = document.getElementById('edit-product-id').value;
  const formData = new FormData(this);
  
  fetch(`/PanControl/Product/update/${id}`, {
    method: 'POST',
    body: formData
  })
  .then(resp => resp.json())
  .then(resp => {
    if (resp.r) {
      panControl.showMessage('Producto actualizado correctamente');
      panControl.hideModal('edit-product-overlay');
      location.reload();
    } else {
      panControl.showMessage(resp.message || 'Error al actualizar producto', 'error');
    }
  })
  .catch(err => {
    console.error(err);
    panControl.showMessage('Error al conectar con el servidor', 'error');
  });
});
</script>

<?php closePanControlFooter(); ?>
