<?php

    function setPanControlFooter($args){
?>
    </main>
  </div>

  <script src="<?=JS?>jquery.js"></script>
  <script src="<?=JS?>sweetalert2.js"></script>
  
  <script>
    const panControl = {
      user: {
        sv: <?= $args->ua->sv ? 'true' : 'false' ?>,
        id: "<?= $args->ua->id ?? '' ?>",
        username: "<?= $args->ua->username ?? '' ?>",
        tipo: "<?= $args->ua->tipo ?? '' ?>"
      },
      
      showLoading: function(element) {
        element.classList.add('loading');
        element.disabled = true;
      },
      
      hideLoading: function(element) {
        element.classList.remove('loading');
        element.disabled = false;
      },
      
      showModal: function(overlay) {
        document.getElementById(overlay).style.display = 'block';
      },
      
      hideModal: function(overlay) {
        document.getElementById(overlay).style.display = 'none';
      },
      
      showMessage: function(message, type = 'success') {
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: type,
            text: message,
            timer: 3000,
            showConfirmButton: false
          });
        } else {
          alert(message);
        }
      },
      
      confirmDelete: function(callback) {
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
          }).then((result) => {
            if (result.isConfirmed) {
              callback();
            }
          });
        } else {
          if (confirm('¿Estás seguro de que quieres eliminar este elemento?')) {
            callback();
          }
        }
      }
    };
  </script>
<?php
    }
    
    function closePanControlFooter(){
?>
</body>
</html>
<?php 
    }
?>
