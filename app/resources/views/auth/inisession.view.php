<?php
    include_once LAYOUTS . 'auth_head.php';
    setHeader($d);
?>

<div class="login-container">
    <div class="logo">
        <h1>PANCONTROL</h1>
    </div>
    <div class="login-box">
        <h2>Inicio de sesión</h2>
        <form id="login-form" class="login-form">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-person-fill"></i>
                    </span>
                    <input type="text" 
                           class="form-control" 
                           id="username" 
                           name="username"
                           placeholder="Número de control" 
                           required>
                </div>
            </div>
            
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock-fill"></i>
                    </span>
                    <input type="password" 
                           class="form-control" 
                           id="passwd" 
                           name="passwd"
                           placeholder="Contraseña" 
                           required>
                </div>
            </div>

            <div class="form-group">
                <div class="error-message">
                    <small class="form-text text-danger d-none" id="error">
                        Sus datos de inicio de sesión son incorrectos
                    </small>
                </div>                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-box-arrow-in-right"></i> Iniciar sesión
                </button>
                        ¿No tienes cuenta? Regístrate
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
    include_once LAYOUTS . 'auth_foot.php';
    setFooter($d);
?>
<script>
$(function(){
    const $lf = $("#login-form");
    
    $lf.on("submit", function(e){
        e.preventDefault();
        e.stopPropagation();
        
        // Mostrar indicador de carga
        Swal.fire({
            title: 'Verificando credenciales',
            text: 'Por favor espere...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const data = new FormData(this);
        
        fetch(BASE_URL + 'public/Session/userAuth', {
            method: 'POST',
            body: data
        })
        .then(resp => resp.json())
        .then(resp => {
            if(resp.r !== false) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Bienvenido!',
                    text: 'Iniciando sesión...',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = resp.redirect;
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de acceso',
                    text: 'Usuario o contraseña incorrectos',
                    confirmButtonText: 'Intentar de nuevo',
                    confirmButtonColor: '#3085d6'
                });
                $("#error").removeClass('d-none');
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al procesar la solicitud',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#3085d6'
            });
        });
    });
});
</script>
<?php
    closefooter();
?>
