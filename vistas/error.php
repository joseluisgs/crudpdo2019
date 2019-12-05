<!-- Cabecera de la página web -->
<?php 
    require_once "cabecera.php"; 
?>

    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>Operación no permitida</h1>
                    </div>
                    <div class="alert alert-danger fade in">
                        <p>Lo siento, estás intentando realizar una operación no válida o ha habido un error de procesamiento, como por ejemplo actualizar o insertar un DNI que ya existe. <br> Por favor <a href="../index.php" class="alert-link">regresa</a> e inténtelo de nuevo.</p>
                    </div>
                </div>
            </div>        
        </div>
    </div>

<!-- Pie de la página web -->
<?php require_once "pie.php"; ?>