<?php
    include_once LAYOUTS . 'main_head.php';
    setHeader($d);
?>

<div class="content-wrapper">
    <div class="content-header">
        <h1>Pedidos</h1>
        <p class="text-muted">Gestión de pedidos y clientes</p>
    </div>

    <div class="content-body">
        <div class="form-container">
            <div class="text-center mb-4">
                <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addClientModal">
                    <i class="bi bi-person-plus"></i> Nuevo Cliente
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOrderModal">
                    <i class="bi bi-cart-plus"></i> Nuevo Pedido
                </button>
            </div>

            <!-- Lista de Clientes -->
            <div class="table-container mb-5">
                <h5 class="text-center mb-4">Clientes Registrados</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    No hay clientes registrados
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Lista de Pedidos -->
            <div class="table-container">
                <h5 class="text-center mb-4">Pedidos Activos</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Fecha de Entrega</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    No hay pedidos activos
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para nuevo cliente -->
<div class="modal fade" id="addClientModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="clientForm">
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" name="phone">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <textarea class="form-control" name="address" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="clientForm" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para nuevo pedido -->
<div class="modal fade" id="addOrderModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="orderForm">
                    <div class="mb-3">
                        <label class="form-label">Cliente</label>
                        <select class="form-select" name="client_id" required>
                            <!-- Los clientes se cargarán dinámicamente aquí -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha de Entrega</label>
                        <input type="date" class="form-control" name="delivery_date" required>
                    </div>
                    <div class="products-list">
                        <!-- Los productos se agregarán dinámicamente aquí -->
                    </div>
                    <button type="button" class="btn btn-secondary mt-3" id="addProductBtn">
                        <i class="bi bi-plus-circle"></i> Agregar Producto
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="orderForm" class="btn btn-primary">Crear Pedido</button>
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
        // Cargar datos iniciales
        loadOrders();
        loadClients();
        loadProductsForOrder();

        // Guardar nuevo cliente
        $("#saveClientBtn").click(function() {
            const formData = new FormData($("#addClientForm")[0]);
            
            fetch("/Client/addClient", {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.r) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cliente agregado correctamente'
                    });
                    $("#addClientModal").modal('hide');
                    loadClients();
                    $("#addClientForm")[0].reset();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al agregar el cliente'
                    });
                }
            })
            .catch(error => console.error('Error:', error));
        });

        // Agregar producto al pedido
        $("#addProductToOrder").click(function() {
            const productRow = `
                <div class="row mb-2 product-row">
                    <div class="col-5">
                        <select class="form-control product-select" required>
                            <!-- Productos se cargarán dinámicamente -->
                        </select>
                    </div>
                    <div class="col-3">
                        <input type="number" class="form-control product-quantity" placeholder="Cantidad" required>
                    </div>
                    <div class="col-3">
                        <input type="number" class="form-control product-price" placeholder="Precio" required>
                    </div>
                    <div class="col-1">
                        <button type="button" class="btn btn-danger btn-sm remove-product">×</button>
                    </div>
                </div>
            `;
            $("#orderProducts").append(productRow);
            loadProductOptions();
            updateOrderTotal();
        });

        // Eliminar producto del pedido
        $(document).on('click', '.remove-product', function() {
            $(this).closest('.product-row').remove();
            updateOrderTotal();
        });

        // Actualizar total al cambiar cantidad o precio
        $(document).on('change', '.product-quantity, .product-price', function() {
            updateOrderTotal();
        });

        // Guardar pedido
        $("#saveOrderBtn").click(function() {
            const formData = new FormData($("#addOrderForm")[0]);
            const products = [];
            
            $('.product-row').each(function() {
                products.push({
                    product_id: $(this).find('.product-select').val(),
                    quantity: $(this).find('.product-quantity').val(),
                    price: $(this).find('.product-price').val()
                });
            });

            formData.append('products', JSON.stringify(products));
            
            fetch("/Client/addOrder", {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.r) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pedido creado correctamente'
                    });
                    $("#addOrderModal").modal('hide');
                    loadOrders();
                    $("#addOrderForm")[0].reset();
                    $("#orderProducts").empty();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al crear el pedido'
                    });
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    function loadOrders() {
        fetch("/Client/getOrders")
            .then(response => response.json())
            .then(orders => {
                const tbody = $("#orderTableBody");
                tbody.empty();
                
                orders.forEach(order => {
                    tbody.append(`
                        <tr>
                            <td>${order.name}</td>
                            <td>${order.fecha}</td>
                            <td>${order.delivery_date}</td>
                            <td>$${order.total}</td>
                            <td><span class="badge ${getStatusBadgeClass(order.status)}">${order.status}</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary" onclick="viewOrder(${order.id})">
                                    👁️ Ver
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteOrder(${order.id})">
                                    🗑️ Eliminar
                                </button>
                            </td>
                        </tr>
                    `);
                });
            })
            .catch(error => console.error('Error:', error));
    }

    function loadClients() {
        fetch("/Client/getClients")
            .then(response => response.json())
            .then(clients => {
                const clientList = $("#clientList");
                const clientSelect = $("#orderClient");
                
                clientList.empty();
                clientSelect.empty();
                clientSelect.append('<option value="">Seleccionar cliente...</option>');
                
                clients.forEach(client => {
                    clientList.append(`
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">${client.name}</h5>
                                    <p class="card-text">
                                        <small>
                                            ${client.email ? `📧 ${client.email}<br>` : ''}
                                            ${client.phone ? `📱 ${client.phone}` : ''}
                                        </small>
                                    </p>
                                    <button class="btn btn-sm btn-info" onclick="viewClientHistory(${client.id})">
                                        📜 Ver historial
                                    </button>
                                </div>
                            </div>
                        </div>
                    `);
                    
                    clientSelect.append(`<option value="${client.id}">${client.name}</option>`);
                });
            })
            .catch(error => console.error('Error:', error));
    }

    function loadProductsForOrder() {
        fetch("/Inventory/getProducts")
            .then(response => response.json())
            .then(products => {
                window.availableProducts = products;
                loadProductOptions();
            })
            .catch(error => console.error('Error:', error));
    }

    function loadProductOptions() {
        if(window.availableProducts) {
            const select = $('.product-select').last();
            select.empty();
            select.append('<option value="">Seleccionar producto...</option>');
            
            window.availableProducts.forEach(product => {
                select.append(`<option value="${product.id}">${product.name} (${product.quantity} ${product.unit})</option>`);
            });
        }
    }

    function updateOrderTotal() {
        let total = 0;
        $('.product-row').each(function() {
            const quantity = parseFloat($(this).find('.product-quantity').val()) || 0;
            const price = parseFloat($(this).find('.product-price').val()) || 0;
            total += quantity * price;
        });
        $("#orderTotal").val(total.toFixed(2));
    }

    function getStatusBadgeClass(status) {
        const classes = {
            'pending': 'bg-warning text-dark',
            'processing': 'bg-info',
            'completed': 'bg-success',
            'cancelled': 'bg-danger'
        };
        return classes[status] || 'bg-secondary';
    }

    function viewClientHistory(clientId) {
        fetch(`/Client/getOrders/${clientId}`)
            .then(response => response.json())
            .then(orders => {
                let html = '<div class="table-responsive"><table class="table">';
                html += '<thead><tr><th>Fecha</th><th>Total</th><th>Estado</th></tr></thead><tbody>';
                
                orders.forEach(order => {
                    html += `
                        <tr>
                            <td>${order.fecha}</td>
                            <td>$${order.total}</td>
                            <td><span class="badge ${getStatusBadgeClass(order.status)}">${order.status}</span></td>
                        </tr>
                    `;
                });
                
                html += '</tbody></table></div>';

                Swal.fire({
                    title: 'Historial de Pedidos',
                    html: html,
                    width: '600px'
                });
            })
            .catch(error => console.error('Error:', error));
    }
</script>

<?php
    closeFooter();
?>
