<?php
/**
 * CONFIGURACIÓN ADICIONAL DE PANCONTROL
 * 
 * Este archivo contiene configuraciones específicas para PanControl
 * como límites de stock, configuraciones de alertas, etc.
 */

// Configuración de alertas de stock
define('PC_STOCK_BAJO_LIMITE', 10); // Cantidad mínima para considerar stock bajo
define('PC_STOCK_CRITICO_LIMITE', 5); // Cantidad mínima para considerar stock crítico

// Configuración de paginación
define('PC_ITEMS_POR_PAGINA', 20); // Número de items por página en las listas

// Configuración de moneda
define('PC_MONEDA_SIMBOLO', '$'); // Símbolo de moneda
define('PC_MONEDA_NOMBRE', 'USD'); // Nombre de la moneda

// Estados de pedidos disponibles
define('PC_ESTADO_PENDIENTE', 'pendiente');
define('PC_ESTADO_EN_PROCESO', 'en_proceso'); 
define('PC_ESTADO_COMPLETADO', 'completado');
define('PC_ESTADO_CANCELADO', 'cancelado');

// Configuración de sesiones
define('PC_SESSION_TIMEOUT', 3600); // Tiempo de sesión en segundos (1 hora)

// Configuración de logs
define('PC_LOGS_ENABLED', true); // Habilitar logs del sistema
define('PC_LOGS_PATH', __DIR__ . '/../logs/'); // Ruta de los logs

// Configuración de backup
define('PC_BACKUP_ENABLED', false); // Habilitar backup automático
define('PC_BACKUP_INTERVAL', 24); // Intervalo de backup en horas

// Configuración de la empresa (para reportes y facturas)
define('PC_EMPRESA_NOMBRE', 'Mi Panadería');
define('PC_EMPRESA_DIRECCION', 'Calle Principal 123');
define('PC_EMPRESA_TELEFONO', '555-0123');
define('PC_EMPRESA_EMAIL', 'info@mipanaderia.com');

// Configuración de notificaciones
define('PC_NOTIFICACIONES_EMAIL', false); // Enviar notificaciones por email
define('PC_NOTIFICACIONES_STOCK_BAJO', true); // Notificar cuando el stock esté bajo

return [
    'stock' => [
        'limite_bajo' => PC_STOCK_BAJO_LIMITE,
        'limite_critico' => PC_STOCK_CRITICO_LIMITE,
        'notificaciones' => PC_NOTIFICACIONES_STOCK_BAJO
    ],
    'paginacion' => [
        'items_por_pagina' => PC_ITEMS_POR_PAGINA
    ],
    'moneda' => [
        'simbolo' => PC_MONEDA_SIMBOLO,
        'nombre' => PC_MONEDA_NOMBRE
    ],
    'estados_pedidos' => [
        'pendiente' => PC_ESTADO_PENDIENTE,
        'en_proceso' => PC_ESTADO_EN_PROCESO,
        'completado' => PC_ESTADO_COMPLETADO,
        'cancelado' => PC_ESTADO_CANCELADO
    ],
    'sesion' => [
        'timeout' => PC_SESSION_TIMEOUT
    ],
    'empresa' => [
        'nombre' => PC_EMPRESA_NOMBRE,
        'direccion' => PC_EMPRESA_DIRECCION,
        'telefono' => PC_EMPRESA_TELEFONO,
        'email' => PC_EMPRESA_EMAIL
    ],
    'sistema' => [
        'logs_enabled' => PC_LOGS_ENABLED,
        'logs_path' => PC_LOGS_PATH,
        'backup_enabled' => PC_BACKUP_ENABLED,
        'backup_interval' => PC_BACKUP_INTERVAL
    ]
];
