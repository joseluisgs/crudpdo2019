<?php
// Incluimos el controlador a los objetos a usar
require_once $_SERVER['DOCUMENT_ROOT']."/iaw/crudpdo/dirs.php";
require_once CONTROLLER_PATH."ControladorAlumno.php";
require_once CONTROLLER_PATH."ControladorImagen.php";
require_once UTILITY_PATH."funciones.php";
 
// Variables temporales
$dni = $nombre = $email = $password = $idioma = $matricula = $lenguaje = $fecha = $imagen ="";
$dniErr = $nombreErr = $emailErr = $passwordErr = $idiomaErr= $matriculaErr = $fechaErr = $imagenErr= "";
$imagenAnterior = "";

$errores=[];
 
// Procesamos la información obtenida por el get
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Voy a hacer un array list de errores para no procesar la foto
   // Procesamos el dni
   $dniVal = filtrado($_POST["dni"]);
   if(empty($dniVal)){
       $dniErr = "Por favor introduzca un DNI válido.";
   }elseif(!filter_var($dniVal, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[0-9]{8}[A-Za-z]{1}/")))){
           $dniErr = "Por favor introduzca un DNI con formato válido XXXXXXXXL, donde X es un dígito y L una letra.";
           $errores[]= $dniErr;
   } else{
       $dni= $dniVal;
   }
   
   // Procesamos el nombre
   $nombreVal = filtrado(($_POST["nombre"]));
   if(empty($nombreVal)){
       $nombreErr = "Por favor introduzca un nombre válido con solo carávteres alfabéticos.";
       // Un ejemplo de validar expresiones regulares directamente desde PHP
   } elseif(!preg_match("/([^\s][A-zÀ-ž\s]+$)/", $nombreVal)) { //filter_var($nombreVal, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/([^\s][A-zÀ-ž\s]+$)/")))){
       $nombreErr = "Por favor introduzca un nombre válido con solo carávteres alfabéticos.";
       $errores[]= $nombreErr;
   } else{
       $nombre= $nombreVal;
   }
   
   // Procesamos el email
   $emailVal = filtrado($_POST["email"]);
   if(empty($emailVal)){
       $emailsErr = "Por favor introduzca email válido.";
       // Un ejemplo de validar expresiones regulares directamente desde PHP
   //} elseif(!filter_var($apellidosVal, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
   //    $apellidosErr = "Por favor introduzca apellidos válidos.";
   $errores[]= $emailErr;
   } else{
       $email= $emailVal;
   }

   // No lo procesamos porque así lo hemos decidido
   // Procesamos el password
   $passwordVal = filtrado($_POST["password"]);
   if(empty($passwordVal) || strlen($passwordVal)<5){
       $passwordErr = "Por favor introduzca password válido y que sea mayor que 5 caracteres.";
       $errores[]= $apsswordErr;
   } else{
       // No hacemos el has que si no cambiamos la contraseña
       $password= $passwordVal;
   }

   // Procsamos idiomas
    if(isset($_POST["idioma"])){
        $idioma = filtrado(implode(", ", $_POST["idioma"]));
    }else{
        $idiomaErr = "Debe elegir al menos un idioma";
        $errores[]=  $idiomaErr;
    }

    // Procesamos matrícula
    if(isset($_POST["matricula"])){
        $matricula = filtrado($_POST["matricula"]);
    }else{
        $matriculaErr = "Debe elegir al menos una matricula";
        $errores[]= $matriculaErr;
    }

    // Procesamos lenguaje
    $lenguaje = filtrado($_POST["lenguaje"]);

    // Procesamos fecha
    $fecha = date("d-m-Y", strtotime(filtrado($_POST["fecha"])));
    $hoy = date("d-m-Y", time());

    // Comparamos las fechas
    $fecha_mat = new DateTime($fecha);
    $fecha_hoy = new DateTime($hoy);
    $interval = $fecha_hoy->diff($fecha_mat);

    if($interval->format('%R%a días')>0){
        $fechaErr = "La fecha no puede ser superior a la fecha actual";
        $errores[]=  $fechaErr;

    }else{
        $fecha = date("d/m/Y",strtotime($fecha));
    }



    // Procesamos la imagen
    // Si nos ha llegado algo mayor que cer
   if($_FILES['imagen']['size']>0 && count($errores)==0){
        $propiedades = explode("/", $_FILES['imagen']['type']);
        $extension = $propiedades[1];
        $tam_max = 50000; // 50 KBytes
        $tam = $_FILES['imagen']['size'];
        $mod = true;
        // Si no coicide la extensión
        if($extension != "jpg" && $extension != "jpeg"){
            $mod = false;
            $imagenErr= "Formato debe ser jpg/jpeg";
        }
        // si no tiene el tamaño
        if($tam>$tam_max){
            $mod = false;
            $imagenErr= "Tamaño superior al limite de: ". ($tam_max/1000). " KBytes";
        }

        // Si todo es correcto, mod = true
        if($mod){
            // salvamos la imagen
            $imagen = md5($_FILES['imagen']['tmp_name'] . $_FILES['imagen']['name'].time()) . "." . $extension;
            $controlador = ControladorImagen::getControlador();
            if(!$controlador->salvarImagen($imagen)){
                $imagenErr= "Error al procesar la imagen y subirla al servidor";
            }

            // Borramos la antigua
            $imagenAnterior = trim($_POST["imagenAnterior"]);
            if($imagenAnterior!=$imagen){
                if(!$controlador->eliminarImagen($imagenAnterior)){
                    $imagenErr= "Error al borrar la antigua imagen en el servidor";
                }
            }
        }else{
        // Si no la hemos modificado
            $imagen=trim($_POST["imagenAnterior"]);
        }

    }else{
        $imagen=trim($_POST["imagenAnterior"]);
    }

    
     // Chequeamos los errores antes de insertar en la base de datos
    if(empty($dniErr) && empty($nombreErr) && empty($passwordErr) && empty($emailErr) && 
        empty($idiomaErr) && empty($matriculaErr) && empty($fechaErr) && empty($imagenErr)){
       // creamos el controlador de alumnado
        $controlador = ControladorAlumno::getControlador();
        $estado = $controlador->actualizarAlumno($id, $dni, $nombre, $email, $password, $idioma, $matricula, $lenguaje, $fecha, $imagen);
        if($estado){
            $errores=[];
            // El registro se ha lamacenado corectamente
            header("location: ../index.php");
            exit();
        }else{
            header("location: error.php");
            exit();
        }
    }else{
        alerta("Hay errores al procesar el formulario revise los errores");
    }
    
}
    
    // Comprobamos que existe el id antes de ir más lejos
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        $id =  decode($_GET["id"]);
        $controlador = ControladorAlumno::getControlador();
        $alumno = $controlador->buscarAlumno($id);
        if (!is_null($alumno)) {
            $dni = $alumno->getDni();
            $nombre = $alumno->getNombre();
            $email = $alumno->getEmail();
            $password = $alumno->getPassword();
            $idioma = $alumno->getIdioma();
            $matricula = $alumno->getMatricula();
            $lenguaje = $alumno->getLenguaje();
            $fecha = $alumno->getFecha();
            $imagen = $alumno->getImagen();
            $imagenAnterior = $imagen;
        }else{
        // hay un error
            header("location: error.php");
            exit();
        }
    }else{
         // hay un error
            header("location: error.php");
            exit();
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
                        <h2>Modificar Alumno/a</h2>
                    </div>
                    <p>Por favor edite la nueva información para actualizar la ficha.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <td class="col-xs-11" class="align-top">
                                <!-- DNI-->
                                <div class="form-group" class="align-left" <?php echo (!empty($dniErr)) ? 'error: ' : ''; ?>">
                                    <label>DNI</label>
                                    <input type="text" required name="dni" class="form-control" value="<?php echo $dni; ?>" 
                                        pattern="[0-9]{8}[A-Za-z]{1}" title="Debe poner 8 números y una letra">
                                    <span class="help-block"><?php echo $dniErr;?></span>
                                </div>
                            </td>
                            <!-- Fotogrsfía -->
                            <td class="align-left">
                                <label>Fotografía</label><br>
                                <img src='<?php echo "../imagenes/" . $alumno->getImagen() ?>' class='rounded' class='img-thumbnail' width='48' height='auto'>
                            </td>
                        </tr>
                    </table>
                        <!-- Nombre-->
                        <div class="form-group <?php echo (!empty($nombreErr)) ? 'error: ' : ''; ?>">
                            <!-- Nombre-->
                            <label>Nombre</label>
                            <input type="text" name="nombre" class="form-control" value="<?php echo $nombre; ?>">
                            <span class="help-block"><?php echo $nombreErr;?></span>
                        </div>
                        <!-- Email -->
                        <div class="form-group <?php echo (!empty($emailErr)) ? 'error: ' : ''; ?>">
                            <label>E-Mail</label>
                            <input type="email" required name="email" class="form-control" value="<?php echo $email; ?>">
                            <span class="help-block"><?php echo $emailErr;?></span>
                        </div>
                        <!-- Password -->
                        <div class="form-group <?php echo (!empty($passwordErr)) ? 'error: ' : ''; ?>">
                            <label>Password</label>
                            <input type="password" required name="password" class="form-control" value="<?php echo ($password); ?>"
                                readonly>
                            <span class="help-block"><?php echo $passwordErr;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($idiomaErr)) ? 'error: ' : ''; ?>">
                            <label>Idiomas</label>
                            <input type="checkbox" name="idioma[]" value="castellano" <?php echo (strstr($idioma, 'castellano')) ? 'checked' : ''; ?>>Castellano</input>
                            <input type="checkbox" name="idioma[]" value="ingles" <?php echo (strstr($idioma, 'ingles')) ? 'checked' : ''; ?>>Inglés</input>
                            <input type="checkbox" name="idioma[]" value="frances" <?php echo (strstr($idioma, 'frances')) ? 'checked' : ''; ?>>Francés</input>
                            <input type="checkbox" name="idioma[]" value="chino" <?php echo (strstr($idioma, 'chino')) ? 'checked' : ''; ?>>Chino</input>
                            <span class="help-block"><?php echo $idiomaErr;?></span>
                        </div>
                        <!-- Matrícula -->
                        <div class="form-group <?php echo (!empty($matriculaErr)) ? 'error: ' : ''; ?>">
                            <label>Matrícula</label>
                            <input type="radio" name="matricula" value="modular" <?php echo (strstr($matricula, 'modular')) ? 'checked' : ''; ?>>Modular</input>
                            <input type="radio" name="matricula" value="completa" <?php echo (strstr($matricula, 'completa')) ? 'checked' : ''; ?>>Completa</input><br>
                            <span class="help-block"><?php echo $matriculaErr;?></span>
                        </div>
                        <!-- Lenguaje-->
                        <div class="form-group">
                        <label>Lenguaje</label>
                            <select name="lenguaje">
                                <option value="PHP" <?php echo (strstr($lenguaje, 'PHP')) ? 'selected' : ''; ?>>PHP</option>
                                <option value="JAVA" <?php echo (strstr($lenguaje, 'JAVA')) ? 'selected' : ''; ?>>JAVA</option>
                                <option value="C#" <?php echo (strstr($lenguaje, 'C#')) ? 'selected' : ''; ?>>C#</option>
                                <option value="PYTHON" <?php echo (strstr($lenguaje, 'PYTHON')) ? 'selected' : ''; ?>>PYTHON</option>
                            </select>
                        </div>
                        <!-- Fecha-->
                        <div class="form-group <?php echo (!empty($fechaErr)) ? 'error: ' : ''; ?>">
                        <label>Fecha de Matriculación</label>
                            <input type="date" required name="fecha" value="<?php echo date('Y-m-d', strtotime(str_replace('/', '-', $fecha)));?>"></input><div>
                            <span class="help-block"><?php echo $fechaErr;?></span>
                        </div>
                         <!-- Foto-->
                         <div class="form-group <?php echo (!empty($imagenErr)) ? 'error: ' : ''; ?>">
                        <label>Fotografía</label>
                        <!-- Solo acepto imagenes jpg -->
                        <input type="file" name="imagen" class="form-control-file" id="imagen" accept="image/jpeg">    
                        <span class="help-block"><?php echo $imagenErr;?></span>    
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="hidden" name="imagenAnterior" value="<?php echo $imagenAnterior; ?>"/>
                        <button type="submit" value="aceptar" class="btn btn-warning"> <span class="glyphicon glyphicon-refresh"></span>  Modificar</button>
                        <a href="../index.php" class="btn btn-primary"><span class="glyphicon glyphicon-chevron-left"></span> Volver</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
<br><br><br>
<!-- Pie de la página web -->
<?php require_once VIEW_PATH."pie.php"; ?>