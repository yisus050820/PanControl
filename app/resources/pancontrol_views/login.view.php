<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?=$d->title?></title>
  <link rel="stylesheet" href="<?=PC_CSS?>login.css">
</head>
<body>
  <div class="login-container">
    <div class="login-box">
      <h1 class="logo">PanControl</h1>
      <h2>Iniciar Sesión</h2>
      <form id="pancontrol-login-form">
        <label for="username">Usuario</label>
        <input type="text" id="username" name="username" placeholder="usuario" required>

        <label for="passwd">Contraseña</label>
        <input type="password" id="passwd" name="passwd" placeholder="••••••••" required>

        <button type="submit">Ingresar</button>
      </form>
      <div id="error-message" style="color: red; margin-top: 10px; display: none;"></div>
      <p class="note">Bienvenidos | CRUD PanControl Universidad de Colima</p>
    </div>
  </div>

  <script src="<?=JS?>jquery.js"></script>
  <script src="<?=JS?>sweetalert2.js"></script>
  <script>
    $(function() {
      const $form = $("#pancontrol-login-form");
      const $error = $("#error-message");

      $form.on("submit", function(e) {
        e.preventDefault();
        e.stopPropagation();

        const data = new FormData(this);
        
        fetch('/PanControl/authenticate', {
          method: 'POST',
          body: data
        })
        .then(resp => resp.json())
        .then(resp => {
          if (resp.r !== false) {
            location.href = "/PanControl/dashboard";
          } else {
            $error.text(resp.message || "Credenciales incorrectas").show();
          }
        })
        .catch(err => {
          console.error(err);
          $error.text("Error al conectar con el servidor").show();
        });
      });
    });
  </script>
</body>
</html>
