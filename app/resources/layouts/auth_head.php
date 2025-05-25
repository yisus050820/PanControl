<?php
    function setHeader($args) {
        $ua = as_object($args->ua);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$args->title?></title>
    
    <!-- Bootstrap & Icons -->
    <link rel="stylesheet" href="<?=BASE_URL?>public/assets/css/bootstrap.css">
    <link rel="stylesheet" href="<?=BASE_URL?>public/assets/css/login.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    
    <script>
        const BASE_URL = '<?=BASE_URL?>';
    </script>
</head>
<body class="auth-page">
<?php
    }
?>