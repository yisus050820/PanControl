<?php
    include_once LAYOUTS . 'main_head.php';

    setHeader($d);
   
?>
<div class="container">
    <div class="card mt-5 w-50 mx-auto">
        <div class="card-body">
            <h5 class="card-title">Registrarse</h5>
            <hr>
            <form action="" id="register-form">
                <div class="form-group input-group mb-3">
                    <label for="name" class="input-group-text">
                        <i class="bi bi-person-vcard"></i>
                    </label>
                    <input type="text" class="form-control" 
                            id="name" name="name"
                            placeholder="Nombre completo" required>
                </div>
                <div class="form-group input-group mb-3">
                    <label for="email" class="input-group-text">
                        <i class="bi bi-envelope-at"></i>
                    </label>
                    <input type="email" class="form-control" 
                            id="email" name="email"
                            placeholder="Correo electrónico" required>
                </div>
                <div class="form-group input-group mb-3">
                    <label for="username" class="input-group-text">
                        <i class="bi bi-person-fill"></i>
                    </label>
                    <input type="text" class="form-control" 
                            id="username" name="username"
                            placeholder="Nombre de usuario" required>
                </div>
                <div class="form-group input-group mb-3">
                    <label for="passwd" class="input-group-text">
                        <i class="bi bi-lock-fill"></i>
                    </label>
                    <input type="password" class="form-control" 
                            id="passwd" name="passwd"
                            placeholder="Contraseña" required>
                </div>
                <div class="form-group input-group mb-3">
                    <label for="passwd2" class="input-group-text">
                        <i class="bi bi-lock-fill"></i>
                    </label>
                    <input type="password" class="form-control" 
                            id="passwd2" name="passwd2"
                            placeholder="Confirmar contraseña" required>
                </div>
                <div class="d-grid gap-2 my-2">
                    <small class="form-text text-danger d-none">
                        No se pudo realizar el registro, inténtelo más tarde.
                    </small>
                    <hr>
                    <button class="btn btn-primary mt-3" type="submit">
                        Continuar <i class="bi bi-chevron-right"></i>
                    </button>
                    <a href="/Session/iniSession" class="btn btn-link">Ya tengo cuenta</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
    include_once LAYOUTS . 'main_foot.php';

    setFooter($d);

?>
    <script>//Script de la vista Inisession
        $( function (){
            const $rf = $("#register-form")
            $rf.on("submit", function(e){
                e.preventDefault()
                e.stopPropagation()
                let $p1 = $("#passwd")
                let $p2 = $("#passwd2")
                if( $p1.val() !== $p2.val() ){
                  Swal.fire({
                    icon : "error",
                    text : "Las contraseñas no coinciden"
                  }).then( () => {
                    $p2.val("")
                    $p2.trigger("focus")
                  })
                }else{
                    const data = new FormData( this )
                    fetch( app.routes.register,{
                        method : 'POST',
                        body : data
                    })
                    .then( resp => resp.json() )
                    .then( resp => {
                        if( resp.r !== false){
                            location.href = app.routes.inisession
                        }else{
                            $("#error").removeClass("d-none")
                        }
                    }).catch(err => console.error( err ))
                }

            })
        })
    </script>

<?php

    closefooter();
