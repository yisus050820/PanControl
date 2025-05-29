# PanControl - Sistema de Gestión de Panadería

Un sistema completo de gestión desarrollado en PHP para pequeñas y medianas panaderías, que incluye control de inventario, gestión de clientes, manejo de pedidos y reportes financieros.

## 🚀 Características Principales

- **Gestión de Inventario**: Control completo de productos con alertas de stock bajo
- **Administración de Clientes**: Registro y seguimiento de clientes con historial de pedidos
- **Sistema de Pedidos**: Creación, modificación y seguimiento de pedidos con diferentes estados
- **Reportes Financieros**: Dashboard con métricas en tiempo real y análisis de ventas
- **Autenticación Segura**: Sistema de login con roles de usuario
- **Interfaz Responsiva**: Diseño moderno que se adapta a dispositivos móviles

## 📋 Requisitos del Sistema

- **Servidor Web**: Apache 2.4+
- **PHP**: 7.4 o superior
- **Base de Datos**: MySQL 5.7+ o MariaDB 10.3+
- **XAMPP**: Recomendado para desarrollo local

## 🛠️ Instalación

### Paso 1: Configuración del Entorno

1. Instala [XAMPP](https://www.apachefriends.org/download.html)
2. Inicia Apache y MySQL desde el panel de control de XAMPP
3. Clona o descarga este proyecto en la carpeta `htdocs` de XAMPP

### Paso 2: Configuración de la Base de Datos

1. Abre phpMyAdmin en tu navegador: `http://localhost/phpmyadmin`
2. Ejecuta el script SQL completo ubicado en `db/pancontrol_datos_completos.sql`
   - Este script creará automáticamente la base de datos `pancontrol`
   - Creará todas las tablas necesarias
   - Insertará datos de prueba (productos, clientes, pedidos, usuarios)
   ```

### Paso 3: Acceso al Sistema

1. Abre tu navegador web
2. Navega a: `http://localhost/tu-proyecto/PanControl/login`
3. Usa las credenciales de prueba:
   - **Administrador**: `admin` / `admin123`
   - **Usuario**: `usuario1` / `user123`

## 📁 Estructura del Proyecto

```
PI2/
├── app/
│   ├── controllers/
│   │   ├── DashboardController.php
│   │   ├── PanControlAuthController.php
│   │   ├── ProductController.php
│   │   ├── ClientController.php
│   │   └── OrderController.php
│   ├── models/
│   │   ├── Product.php
│   │   ├── Client.php
│   │   └── Order.php
│   ├── views/pancontrol/
│   │   ├── login.view.php
│   │   ├── dashboard.view.php
│   │   ├── inventario.view.php
│   │   ├── clientes.view.php
│   │   └── finanzas.view.php
│   ├── pancontrol_layouts/
│   │   ├── main_head.php
│   │   └── main_foot.php
│   ├── pancontrol_config.php
│   └── App.php
├── db/
│   └── pancontrol_datos_completos.sql
├── PanControl/
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   └── index.php
├── router/
│   └── Router.php
├── setup_pancontrol_final.php
└── README.md
```

## 🎯 Módulos del Sistema

### 1. Dashboard Principal
- Resumen de ventas diarias, semanales y mensuales
- Productos con stock bajo
- Pedidos pendientes
- Métricas de clientes activos

### 2. Gestión de Inventario
- Lista completa de productos
- Agregar/editar/eliminar productos
- Control de stock con alertas automáticas
- Categorización de productos

### 3. Administración de Clientes
- Registro de nuevos clientes
- Historial de pedidos por cliente
- Información de contacto y facturación
- Búsqueda y filtrado avanzado

### 4. Sistema de Pedidos
- Creación de nuevos pedidos
- Estados: Pendiente, En Proceso, Completado, Cancelado
- Cálculo automático de totales
- Asignación de productos y cantidades

### 5. Reportes Financieros
- Gráficos de ventas por período
- Análisis de productos más vendidos
- Reportes de ingresos
- Exportación de datos



## 🛡️ Seguridad

- Autenticación basada en sesiones PHP
- Validación de datos de entrada
- Protección contra inyecciones SQL mediante prepared statements
- Control de acceso basado en roles de usuario

## 🔧 Configuración Avanzada

### Configuración de Base de Datos

Edita `app/pancontrol_config.php` para modificar la configuración:

```php
return [
    'database' => [
        'host' => 'localhost',
        'dbname' => 'pancontrol',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4'
    ]
];
```

### Rutas Personalizadas

Las rutas se configuran en `router/Router.php`. Para agregar nuevas rutas:

```php
$this->addRoute('GET', '/PanControl/nueva-ruta', [NuevoController::class, 'metodo']);
```


**Desarrollado con ❤️ para la gestión eficiente de panaderías**
