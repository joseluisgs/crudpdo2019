<?php
// Incluimos el controlador a los objetos a usar
require_once $_SERVER['DOCUMENT_ROOT']."/iaw/crudpdo/dirs.php";
require_once CONTROLLER_PATH."ControladorAlumno.php";
require_once UTILITY_PATH."funciones.php";

// Compramos la existencia del parámetro id antes de usarlo
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Cargamos el controlador de alumnos
    $id = decode($_GET["id"]);
    $controlador = ControladorAlumno::getControlador();
    $alumno= $controlador->buscarAlumno($id);
    if (is_null($alumno)){
        // hay un error
        header("location: error.php");
        exit();
    } 
}
?>

<!-- Cabecera de la página web -->
<?php require_once VIEW_PATH."cabecera.php"; ?>

<!-- Cuerpo de la página web -->
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>Ficha de Alumno/a</h1>
                    </div>
                    <table>
                        <tr>
                            <td class="col-xs-11" class="align-top">
                                <div class="form-group" class="align-left">
                                    <label>DNI</label>
                                    <p class="form-control-static"><?php echo $alumno->getDni(); ?></p>
                                </div>
                            </td>
                            <td class="align-left">
                                <label>Fotografía</label><br>
                                <img src='<?php echo "../imagenes/" . $alumno->getImagen() ?>' class='rounded' class='img-thumbnail' width='48' height='auto'>
                            </td>
                        </tr>
                    </table>
                
                    <div class="form-group">
                        <label>Nombre</label>
                        <p class="form-control-static"><?php echo $alumno->getNombre(); ?></p>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                            <p class="form-control-static"><?php echo $alumno->getEmail(); ?></p>
                    </div>
                    <div class="form-group">
                        <label>Contraseña</label>
                        <p class="form-control-static"><?php echo str_repeat("*",strlen($alumno->getPassword())); ?></p>
                    </div>
                    <div class="form-group">
                        <label>Idioma</label>
                            <p class="form-control-static"><?php echo $alumno->getIdioma(); ?></p>
                    </div>
                    <div class="form-group">
                        <label>Matricula</label>
                            <p class="form-control-static"><?php echo $alumno->getMatricula(); ?></p>
                    </div>
                    <div class="form-group">
                        <label>Lenguaje</label>
                            <p class="form-control-static"><?php echo $alumno->getLenguaje(); ?></p>
                    </div>
                    <div class="form-group">
                        <label>Fecha</label>
                            <p class="form-control-static"><?php echo $alumno->getFecha(); ?></p>
                    </div>
                    <p><a href="../index.php" class="btn btn-primary"><span class="glyphicon glyphicon-ok"></span> Aceptar</a></p>
                </div>
            </div>        
        </div>
    </div>
<br><br><br>
<!-- Pie de la página web -->
<?php require_once VIEW_PATH."pie.php"; ?>
