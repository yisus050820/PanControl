<?php

    function setFooter($args){
        $ua = as_object( $args->ua );
?>
            </div> <!-- /container-fluid -->
        </div> <!-- /content -->
    </div> <!-- /wrapper -->

    <!-- Scripts -->
    <script src="<?=BASE_URL?>public/assets/js/jquery.js"></script>
    <script src="<?=BASE_URL?>public/assets/js/bootstrap.js"></script>
    <script src="<?=BASE_URL?>public/assets/js/sweetalert2.js"></script>
    <script src="<?=BASE_URL?>public/assets/js/app.js"></script>
    
    <script>
        $(document).ready(function () {
            // Toggle sidebar
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>
<?php
    }
    function closeFooter(){?>
</body>
</html>
<?php }