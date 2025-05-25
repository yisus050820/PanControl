<?php
    include_once LAYOUTS . 'main_head.php';
    setHeader($d);
?>

<div class="content-wrapper">
    <div class="content-header">
        <h1>Inventario</h1>
        <p class="text-muted">Gestión de productos</p>
    </div>

    <div class="content-body">
        <div class="form-container">
            <div class="text-center mb-4">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <i class="bi bi-plus-lg"></i> Nuevo Producto
                </button>
            </div>

            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Cantidad</th>
                                <th>Unidad</th>
                                <th>Cantidad Mínima</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Los productos se cargarán dinámicamente aquí -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar/editar producto -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="productForm">
                    <div class="mb-3">
                        <label class="form-label">Nombre del Producto</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Cantidad</label>
                            <input type="number" class="form-control" name="quantity" step="0.01" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Unidad</label>
                            <select class="form-select" name="unit" required>
                                <option value="kg">Kilogramos</option>
                                <option value="g">Gramos</option>
                                <option value="l">Litros</option>
                                <option value="ml">Mililitros</option>
                                <option value="pza">Piezas</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cantidad Mínima</label>
                        <input type="number" class="form-control" name="min_quantity" step="0.01" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="productForm" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>

<?php
    include_once LAYOUTS . 'main_foot.php';
    setFooter($d);
    closefooter();
?>

<script>
    $(function(){
        // Cargar productos al iniciar
        loadProducts();

        // Guardar nuevo producto
        $("#productForm").submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch("/Inventory/addProduct", {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.r) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Producto agregado correctamente'
                    });
                    $("#addProductModal").modal('hide');
                    loadProducts();
                    $("#productForm")[0].reset();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al agregar el producto'
                    });
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    function loadProducts() {
        fetch("/Inventory/getProducts")
            .then(response => response.json())
            .then(products => {
                const tbody = $("#inventoryTable tbody");
                tbody.empty();
                
                products.forEach(product => {
                    const status = getStockStatus(product.quantity, product.min_quantity);
                    tbody.append(`
                        <tr>
                            <td>${product.name}</td>
                            <td>${product.quantity}</td>
                            <td>${product.unit}</td>
                            <td>${product.min_quantity}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-primary" onclick="editProduct(${product.id})">
                                    ✏️ Editar
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteProduct(${product.id})">
                                    🗑️ Eliminar
                                </button>
                            </td>
                        </tr>
                    `);
                });
            })
            .catch(error => console.error('Error:', error));
    }

    function getStockStatus(quantity, minQuantity) {
        if (quantity <= 0) {
            return { class: 'bg-danger', text: 'Sin Stock' };
        } else if (quantity <= minQuantity) {
            return { class: 'bg-warning text-dark', text: 'Stock Bajo' };
        }
        return { class: 'bg-success', text: 'OK' };
    }

    function deleteProduct(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede revertir",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/Inventory/deleteProduct/${id}`, {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if(data.r) {
                        Swal.fire('Eliminado', 'El producto ha sido eliminado', 'success');
                        loadProducts();
                    } else {
                        Swal.fire('Error', 'No se pudo eliminar el producto', 'error');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    }
</script>
