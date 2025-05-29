<?php
/**
 * Script para configurar la base de datos PanControl y agregar datos de prueba
 */

echo "🚀 CONFIGURANDO PANCONTROL - Base de Datos y Datos de Prueba\n";
echo "============================================================\n\n";

// Configuración de la base de datos
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'pancontrol';

try {
    // Conectar a MySQL (sin especificar base de datos)
    echo "📡 Conectando a MySQL...\n";
    $pdo = new PDO("mysql:host=$host", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conexión exitosa a MySQL\n\n";

    // Crear base de datos si no existe
    echo "🗄️ Verificando base de datos '$database'...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Base de datos '$database' lista\n\n";

    // Conectar a la base de datos específica
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ejecutar schema
    echo "📋 Ejecutando schema de base de datos...\n";
    $schemaPath = __DIR__ . '/db/schema.sql';
    
    if (!file_exists($schemaPath)) {
        throw new Exception("No se encontró el archivo schema.sql en: $schemaPath");
    }

    $schema = file_get_contents($schemaPath);
    
    // Dividir en statements individuales
    $statements = array_filter(
        array_map('trim', explode(';', $schema)),
        function($stmt) {
            return !empty($stmt) && !preg_match('/^\s*--/', $stmt);
        }
    );

    foreach ($statements as $statement) {
        if (trim($statement)) {
            try {
                $pdo->exec($statement);
                if (preg_match('/CREATE TABLE\s+(?:IF NOT EXISTS\s+)?`?(\w+)`?/i', $statement, $matches)) {
                    echo "  ✅ Tabla '{$matches[1]}' creada/verificada\n";
                }
            } catch (PDOException $e) {
                echo "  ⚠️ Error en statement: " . $e->getMessage() . "\n";
            }
        }
    }

    echo "\n🎯 Agregando datos de prueba...\n";

    // Limpiar datos existentes (opcional)
    $cleanTables = ['order_detail', 'order', 'client', 'product', 'user'];
    foreach ($cleanTables as $table) {
        try {
            $pdo->exec("DELETE FROM `$table`");
            $pdo->exec("ALTER TABLE `$table` AUTO_INCREMENT = 1");
            echo "  🧹 Tabla '$table' limpiada\n";
        } catch (PDOException $e) {
            // Tabla puede no existir, ignorar
        }
    }

    echo "\n👤 Insertando usuarios de prueba...\n";
    $users = [
        [
            'name' => 'Administrador del Sistema',
            'username' => 'admin',
            'email' => 'admin@pancontrol.com',
            'passwd' => password_hash('admin123', PASSWORD_DEFAULT),
            'tipo' => 1, // Admin
            'activo' => 1
        ],
        [
            'name' => 'Gerente de Panadería',
            'username' => 'gerente',
            'email' => 'gerente@pancontrol.com',
            'passwd' => password_hash('gerente123', PASSWORD_DEFAULT),
            'tipo' => 1, // Admin
            'activo' => 1
        ],
        [
            'name' => 'Usuario Regular',
            'username' => 'usuario',
            'email' => 'usuario@pancontrol.com',
            'passwd' => password_hash('usuario123', PASSWORD_DEFAULT),
            'tipo' => 2, // Usuario regular
            'activo' => 1
        ]
    ];

    $userStmt = $pdo->prepare("INSERT INTO user (name, username, email, passwd, tipo, activo) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($users as $user) {
        $userStmt->execute(array_values($user));
        echo "  ✅ Usuario '{$user['username']}' creado\n";
    }

    echo "\n📦 Insertando productos de inventario...\n";
    $products = [
        ['name' => 'Harina de Trigo', 'quantity' => 50.00, 'unit' => 'kg', 'min_quantity' => 10.00],
        ['name' => 'Azúcar Blanca', 'quantity' => 25.00, 'unit' => 'kg', 'min_quantity' => 5.00],
        ['name' => 'Levadura Fresca', 'quantity' => 2.50, 'unit' => 'kg', 'min_quantity' => 0.50],
        ['name' => 'Mantequilla', 'quantity' => 15.00, 'unit' => 'kg', 'min_quantity' => 3.00],
        ['name' => 'Huevos', 'quantity' => 120, 'unit' => 'unidades', 'min_quantity' => 24],
        ['name' => 'Leche Entera', 'quantity' => 30.00, 'unit' => 'litros', 'min_quantity' => 5.00],
        ['name' => 'Sal', 'quantity' => 8.00, 'unit' => 'kg', 'min_quantity' => 2.00],
        ['name' => 'Aceite Vegetal', 'quantity' => 12.00, 'unit' => 'litros', 'min_quantity' => 2.00],
        ['name' => 'Chocolate en Polvo', 'quantity' => 5.00, 'unit' => 'kg', 'min_quantity' => 1.00],
        ['name' => 'Vainilla Líquida', 'quantity' => 1.50, 'unit' => 'litros', 'min_quantity' => 0.25],
        ['name' => 'Canela en Polvo', 'quantity' => 0.80, 'unit' => 'kg', 'min_quantity' => 0.20],
        ['name' => 'Nueces', 'quantity' => 3.00, 'unit' => 'kg', 'min_quantity' => 0.50],
        ['name' => 'Pasas', 'quantity' => 2.50, 'unit' => 'kg', 'min_quantity' => 0.50],
        ['name' => 'Crema de Leche', 'quantity' => 8.00, 'unit' => 'litros', 'min_quantity' => 2.00],
        ['name' => 'Mermelada de Fresa', 'quantity' => 4.00, 'unit' => 'kg', 'min_quantity' => 1.00]
    ];

    $productStmt = $pdo->prepare("INSERT INTO product (name, quantity, unit, min_quantity) VALUES (?, ?, ?, ?)");
    foreach ($products as $product) {
        $productStmt->execute(array_values($product));
        echo "  ✅ Producto '{$product['name']}' agregado ({$product['quantity']} {$product['unit']})\n";
    }

    echo "\n👥 Insertando clientes de prueba...\n";
    $clients = [
        [
            'name' => 'Panadería El Sol Dorado',
            'email' => 'pedidos@soldorado.com',
            'phone' => '+34 912 345 678',
            'address' => 'Calle Mayor 123, Madrid, España'
        ],
        [
            'name' => 'Café y Pastelería Central',
            'email' => 'compras@cafecentral.es',
            'phone' => '+34 913 456 789',
            'address' => 'Plaza del Carmen 45, Madrid, España'
        ],
        [
            'name' => 'Restaurante La Mesa Redonda',
            'email' => 'cocina@mesaredonda.com',
            'phone' => '+34 914 567 890',
            'address' => 'Avenida de la Castellana 200, Madrid, España'
        ],
        [
            'name' => 'Hotel Majestic - Servicio de Catering',
            'email' => 'catering@hotelmajestic.es',
            'phone' => '+34 915 678 901',
            'address' => 'Gran Vía 78, Madrid, España'
        ],
        [
            'name' => 'Supermercado Fresh Market',
            'email' => 'panaderia@freshmarket.com',
            'phone' => '+34 916 789 012',
            'address' => 'Calle de Alcalá 350, Madrid, España'
        ],
        [
            'name' => 'Escuela Culinaria Madrid',
            'email' => 'suministros@escuelamadrid.edu',
            'phone' => '+34 917 890 123',
            'address' => 'Calle de la Princesa 89, Madrid, España'
        ]
    ];

    $clientStmt = $pdo->prepare("INSERT INTO client (name, email, phone, address) VALUES (?, ?, ?, ?)");
    foreach ($clients as $client) {
        $clientStmt->execute(array_values($client));
        echo "  ✅ Cliente '{$client['name']}' agregado\n";
    }

    echo "\n📋 Creando pedidos de ejemplo...\n";
    
    // Pedido 1: Panadería El Sol Dorado
    $pdo->exec("INSERT INTO `order` (client_id, total, status, delivery_date) VALUES (1, 145.50, 'completed', '2025-05-27')");
    $orderId1 = $pdo->lastInsertId();
    
    $pdo->exec("INSERT INTO order_detail (order_id, product_id, quantity, price) VALUES 
        ($orderId1, 1, 20.00, 1.20),  -- Harina 20kg a 1.20€/kg
        ($orderId1, 2, 10.00, 0.80),  -- Azúcar 10kg a 0.80€/kg
        ($orderId1, 3, 1.00, 15.50),  -- Levadura 1kg a 15.50€/kg
        ($orderId1, 4, 5.00, 6.50),   -- Mantequilla 5kg a 6.50€/kg
        ($orderId1, 5, 48, 0.25),     -- Huevos 48 unidades a 0.25€/u
        ($orderId1, 6, 10.00, 1.10)   -- Leche 10L a 1.10€/L
    ");
    
    echo "  ✅ Pedido #$orderId1 - Panadería El Sol Dorado (€145.50) - COMPLETADO\n";

    // Pedido 2: Café Central
    $pdo->exec("INSERT INTO `order` (client_id, total, status, delivery_date) VALUES (2, 89.75, 'processing', '2025-05-28')");
    $orderId2 = $pdo->lastInsertId();
    
    $pdo->exec("INSERT INTO order_detail (order_id, product_id, quantity, price) VALUES 
        ($orderId2, 1, 15.00, 1.20),  -- Harina 15kg
        ($orderId2, 9, 2.00, 8.50),   -- Chocolate 2kg a 8.50€/kg
        ($orderId2, 10, 0.50, 12.00), -- Vainilla 0.5L a 12€/L
        ($orderId2, 14, 3.00, 4.50),  -- Crema 3L a 4.50€/L
        ($orderId2, 15, 2.00, 5.25)   -- Mermelada 2kg a 5.25€/kg
    ");
    
    echo "  ✅ Pedido #$orderId2 - Café y Pastelería Central (€89.75) - EN PROCESO\n";

    // Pedido 3: Restaurante La Mesa Redonda
    $pdo->exec("INSERT INTO `order` (client_id, total, status, delivery_date) VALUES (3, 234.80, 'pending', '2025-05-29')");
    $orderId3 = $pdo->lastInsertId();
    
    $pdo->exec("INSERT INTO order_detail (order_id, product_id, quantity, price) VALUES 
        ($orderId3, 1, 30.00, 1.20),  -- Harina 30kg
        ($orderId3, 2, 15.00, 0.80),  -- Azúcar 15kg
        ($orderId3, 4, 8.00, 6.50),   -- Mantequilla 8kg
        ($orderId3, 5, 72, 0.25),     -- Huevos 72 unidades
        ($orderId3, 6, 20.00, 1.10),  -- Leche 20L
        ($orderId3, 12, 1.50, 12.00), -- Nueces 1.5kg a 12€/kg
        ($orderId3, 13, 1.00, 8.00)   -- Pasas 1kg a 8€/kg
    ");
    
    echo "  ✅ Pedido #$orderId3 - Restaurante La Mesa Redonda (€234.80) - PENDIENTE\n";

    // Pedido 4: Hotel Majestic
    $pdo->exec("INSERT INTO `order` (client_id, total, status, delivery_date) VALUES (4, 456.25, 'pending', '2025-05-30')");
    $orderId4 = $pdo->lastInsertId();
    
    $pdo->exec("INSERT INTO order_detail (order_id, product_id, quantity, price) VALUES 
        ($orderId4, 1, 50.00, 1.15),  -- Harina 50kg (precio por volumen)
        ($orderId4, 2, 25.00, 0.75),  -- Azúcar 25kg
        ($orderId4, 3, 3.00, 15.50),  -- Levadura 3kg
        ($orderId4, 4, 12.00, 6.50),  -- Mantequilla 12kg
        ($orderId4, 5, 144, 0.25),    -- Huevos 144 unidades
        ($orderId4, 6, 30.00, 1.10),  -- Leche 30L
        ($orderId4, 8, 5.00, 2.80),   -- Aceite 5L a 2.80€/L
        ($orderId4, 14, 8.00, 4.50)   -- Crema 8L
    ");
    
    echo "  ✅ Pedido #$orderId4 - Hotel Majestic (€456.25) - PENDIENTE\n";

    // Actualizar totales de pedidos
    $pdo->exec("UPDATE `order` SET total = (SELECT SUM(quantity * price) FROM order_detail WHERE order_id = `order`.id)");

    echo "\n📊 Resumen de la configuración:\n";
    echo "================================\n";
    
    // Contar registros
    $userCount = $pdo->query("SELECT COUNT(*) FROM user")->fetchColumn();
    $productCount = $pdo->query("SELECT COUNT(*) FROM product")->fetchColumn();
    $clientCount = $pdo->query("SELECT COUNT(*) FROM client")->fetchColumn();
    $orderCount = $pdo->query("SELECT COUNT(*) FROM `order`")->fetchColumn();
    $detailCount = $pdo->query("SELECT COUNT(*) FROM order_detail")->fetchColumn();
    
    echo "👤 Usuarios: $userCount\n";
    echo "📦 Productos: $productCount\n";
    echo "👥 Clientes: $clientCount\n";
    echo "📋 Pedidos: $orderCount\n";
    echo "📝 Detalles de pedido: $detailCount\n";

    echo "\n🔑 Credenciales de acceso:\n";
    echo "========================\n";
    echo "URL: http://localhost/PI2/PanControl/\n";
    echo "Usuario Admin: admin / admin123\n";
    echo "Usuario Gerente: gerente / gerente123\n";
    echo "Usuario Regular: usuario / usuario123\n";

    echo "\n🎉 ¡CONFIGURACIÓN COMPLETADA EXITOSAMENTE!\n";
    echo "==========================================\n";
    echo "PanControl está listo para usar con datos de prueba completos.\n\n";

} catch (PDOException $e) {
    echo "❌ Error de base de datos: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
