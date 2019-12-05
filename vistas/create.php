<?php
//error_reporting(E_ALL & ~(E_STRICT|E_NOTICE));
session_start();
if(!isset($_SESSION['USUARIO']['email'])){
    //echo $_SESSION['USUARIO']['email'];
    //exit();
    header("location: login.php");
    exit();
}

// Incluimos el controlador a los objetos a usar
//require_once "../dirs.php";

// Incluimos los directorios a trabajar
require_once $_SERVER['DOCUMENT_ROOT']."/iaw/crudpdo/dirs.php";
require_once CONTROLLER_PATH."ControladorAlumno.php";
require_once CONTROLLER_PATH."ControladorImagen.php";
require_once UTILITY_PATH."funciones.php";

 
// Variables temporales
$dni = $nombre = $email = $password = $idioma = $matricula = $lenguaje = $fecha = $imagen ="";
$dniErr = $nombreErr = $emailErr = $passwordErr = $idiomaErr= $matriculaErr = $fechaErr = $imagenErr= "";
 
// Procesamos el formulario al pulsar el botón aceptar de esta ficha
if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["aceptar"]){

    // Procesamos el dni
    $dniVal = filtrado($_POST["dni"]);
    if(empty($dniVal)){
        $dniErr = "Por favor introduzca un DNI válido.";
    }elseif(!filter_var($dniVal, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[0-9]{8}[A-Za-z]{1}/")))){
            $dniErr = "Por favor introduzca un DNI con formato válido XXXXXXXXL, donde X es un dígito y L una letra.";
    }
     // Buscamos que no exista el aulumno
     $controlador = ControladorAlumno::getControlador();
     $alumno = $controlador->buscarAlumnoDni($dniVal);
    if(isset($alumno)){
        //alerta(print_r($alumno->getDni()));
        $dniErr = "Ya existe un alumno con DNI:" .$dniVal. " en la Base de Datos";
    }else{
        $dni= $dniVal;
    }
    
    // Procesamos el nombre
    $nombreVal = filtrado(($_POST["nombre"]));
    if(empty($nombreVal)){
        $nombreErr = "Por favor introduzca un nombre válido con solo carávteres alfabéticos.";
        // Un ejemplo de validar expresiones regulares directamente desde PHP
    } elseif(!preg_match("/([^\s][A-zÀ-ž\s]+$)/", $nombreVal)) { //filter_var($nombreVal, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/([^\s][A-zÀ-ž\s]+$)/")))){
        $nombreErr = "Por favor introduzca un nombre válido con solo carávteres alfabéticos.";
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
    } else{
        $email= $emailVal;
    }

    // Procesamos el password
    $passwordVal = filtrado($_POST["password"]);
    if(empty($passwordVal) || strlen($passwordVal)<5){
        $passwordErr = "Por favor introduzca password válido y que sea mayor que 5 caracteres.";
    } else{
        $password= hash('md5',$passwordVal);
    }

    // Procsamos idiomas
    if(isset($_POST["idioma"])){
        $idioma = filtrado(implode(", ", $_POST["idioma"]));
    }else{
        $idiomaErr = "Debe elegir al menos un idioma";
    }

    // Procesamos matrícula
    if(isset($_POST["matricula"])){
        $matricula = filtrado($_POST["matricula"]);
    }else{
        $matriculaErr = "Debe elegir al menos una matricula";
    }
    
    // Procesamos lenguaje
    if(isset($_POST["lenguaje"])){
        $lenguaje = filtrado($_POST["lenguaje"]);
    }else{
        $matriculaErr = "Debe elegir al menos una matricula";
    }
    
     // Procesamos fecha
     $fecha = date("d-m-Y", strtotime(filtrado($_POST["fecha"])));
     $hoy = date("d-m-Y", time());
 
     // Comparamos las fechas
     $fecha_mat = new DateTime($fecha);
     $fecha_hoy = new DateTime($hoy);
     $interval = $fecha_hoy->diff($fecha_mat);
 
     if($interval->format('%R%a días')>0){
         $fechaErr = "La fecha no puede ser superior a la fecha actual";

     }else{
         $fecha = date("d/m/Y",strtotime($fecha));
     }



    // Procesamos la foto
    $propiedades = explode("/", $_FILES['imagen']['type']);
    $extension = $propiedades[1];
    $tam_max = 50000; // 50 KBytes
    $tam = $_FILES['imagen']['size'];
    $mod = true; // Si vamos a modificar

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
    }

    // Chequeamos los errores antes de insertar en la base de datos
    if(empty($dniErr) && empty($nombreErr) && empty($passwordErr) && empty($emailErr) && 
        empty($idiomaErr) && empty($matriculaErr) && empty($fechaErr) && empty($imagenErr)){
        // creamos el controlador de alumnado
        $controlador = ControladorAlumno::getControlador();
        $estado = $controlador->almacenarAlumno($dni, $nombre, $email, $password, $idioma, $matricula, $lenguaje, $fecha, $imagen);
        if($estado){
            //El registro se ha lamacenado corectamente
            //alerta("Alumno/a creado con éxito");
            header("location: ../index.php");
            exit();
        }else{
            header("location: error.php");
            exit();
        }
    }else{
        alerta("Hay errores al procesar el formulario revise los errores");
    }

}else{
    $idioma="castellano";
    $matricula="modular";
    $fecha = date("Y-m-d");
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
                        <h2>Crear Alumno/a</h2>
                    </div>
                    <p>Por favor rellene este formulario para añadir un nuevo alumno/a a la base de datos de la clase.</p>
                    <!-- Formulario-->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                     <!-- DNI-->
                        <div class="form-group <?php echo (!empty($dniErr)) ? 'error: ' : ''; ?>">
                            <label>DNI</label>
                            <input type="text" required name="dni" class="form-control" value="<?php echo $dni; ?>" 
                                pattern="[0-9]{8}[A-Za-z]{1}" title="Debe poner 8 números y una letra">
                            <span class="help-block"><?php echo $dniErr;?></span>
                        </div>
                        <!-- Nombre-->
                        <div class="form-group <?php echo (!empty($nombreErr)) ? 'error: ' : ''; ?>">
                            <label>Nombre</label>
                            <input type="text" required name="nombre" class="form-control" value="<?php echo $nombre; ?>" 
                                pattern="([^\s][A-zÀ-ž\s]+)"
                                title="El nombre no puede contener números"
                                minlength="3">
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
                            <input type="password" required name="password" class="form-control" value="<?php //echo $password; ?>"
                                minlength="5">
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
                        <input type="file" required name="imagen" class="form-control-file" id="imagen" accept="image/jpeg">    
                        <span class="help-block"><?php echo $imagenErr;?></span>    
                        </div>
                        <!-- Botones --> 
                         <button type="submit" name= "aceptar" value="aceptar" class="btn btn-success"> <span class="glyphicon glyphicon-floppy-save"></span>  Aceptar</button>
                         <button type="reset" value="reset" class="btn btn-info"> <span class="glyphicon glyphicon-repeat"></span>  Limpiar</button>
                        <a href="../index.php" class="btn btn-primary"><span class="glyphicon glyphicon-chevron-left"></span> Volver</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
<br><br><br>
<!-- Pie de la página web -->
<?php require_once VIEW_PATH."pie.php"; ?>