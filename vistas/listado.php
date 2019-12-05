
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header clearfix">
                    <h2 class="pull-left">Fichas del Alumnado</h2>
                </div>
                <form class="form-inline" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="no_imprimir">
                    <div class="form-group mx-sm-5 mb-2">
                        <label for="alumno" class="sr-only">Nombre o DNI</label>
                        <input type="text" class="form-control" id="buscar" name="alumno" placeholder="Nombre o DNI">
                    </div>
                    <button type="submit" class="btn btn-primary mb-2"> <span class="glyphicon glyphicon-search"></span>  Buscar</button>
                    <!-- Aquí va el nuevo botón para dar de alta, podría ir al final -->
                    <a href="javascript:window.print()" class="btn pull-right"> <span class="glyphicon glyphicon-print"></span> IMPRIMIR</a>
                    <a href="utilidades/descargar.php?opcion=TXT" class="btn pull-right" target="_blank"><span class="glyphicon glyphicon-download"></span>  TXT</a>
                    <a href="utilidades/descargar.php?opcion=PDF" class="btn pull-right" target="_blank"><span class="glyphicon glyphicon-download"></span>  PDF</a>
                    <a href="utilidades/descargar.php?opcion=XML" class="btn pull-right" target="_blank"><span class="glyphicon glyphicon-download"></span>  XML</a>
                    <a href="utilidades/descargar.php?opcion=JSON" class="btn pull-right" target="_blank"><span class="glyphicon glyphicon-download"></span>  JSON</a>
                    <a href="vistas/create.php" class="btn btn-success pull-right"><span class="glyphicon glyphicon-user"></span>  Añadir Alumno/a</a>
                    
                </form>
            </div>
            <!-- Linea para dividir -->
            <div class="page-header clearfix">        
            </div>
            <?php
            // Incluimos los ficheros que ncesitamos
            // Incluimos los directorios a trabajar
            require_once CONTROLLER_PATH."ControladorAlumno.php";
            require_once CONTROLLER_PATH . "Paginador.php";
            require_once UTILITY_PATH."funciones.php";
            // creamos la consulta dependiendo si venimos o no del formulario
            // para el buscador: select * from alumnado where nombre like "%%" or apellidos like "%%"
            if (!isset($_POST["alumno"])) {
                $nombre = "";
                $dni = "";
            } else {
                $nombre = filtrado($_POST["alumno"]);
                $dni = filtrado($_POST["alumno"]);
            }
            // Cargamos el controlador de alumnos
            $controlador = ControladorAlumno::getControlador();
            
            // Parte del paginador
            $pagina = ( isset($_GET['page']) ) ? $_GET['page'] : 1;
            $enlaces = ( isset($_GET['enlaces']) ) ? $_GET['enlaces'] : 10;


            //$lista = $controlador->listarAlumnos($nombre, $dni); //-- > Lo hará el paginador

             // Consulta a realizar -- esto lo cambiaré para la semana que viene
             $consulta = "SELECT * FROM alumnadocrud WHERE nombre LIKE :nombre OR dni LIKE :dni";
             $parametros = array(':nombre' => "%".$nombre."%", ':nombre' => "%".$nombre."%", ':dni' => "%".$dni."%");
             $limite = 2; // Limite del paginador
             $paginador  = new Paginador($consulta, $parametros, $limite);
             $resultados = $paginador->getDatos($pagina);


            // Si hay filas (no nulo), pues mostramos la tabla
            //if (!is_null($lista) && count($lista) > 0) {
            if(count( $resultados->datos)>0){
                echo "<table class='table table-bordered table-striped'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>DNI</th>";
                echo "<th>Nombre</th>";
                echo "<th>EMail</th>";
                echo "<th>Contraseña</th>";
                echo "<th>Idiomas</th>";
                echo "<th>Matrícula</th>";
                echo "<th>Lenguajes</th>";
                echo "<th>Fecha</th>";
                echo "<th>Imagen</th>";
                echo "<th>Acción</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                // Recorremos los registros encontrados
                foreach ($resultados->datos as $a) {
                //foreach ($lista as $alumno) {
                    // Esto lo hago para no cambiaros el resto de codigo, si no podría usar a directamente
                    $alumno = new Alumno($a->id, $a->dni, $a->nombre, $a->email, $a->password, $a->idioma, $a->matricula, $a->lenguaje, $a->fecha, $a->imagen);
                    // Pintamos cada fila
                    echo "<tr>";
                    echo "<td>" . $alumno->getDni() . "</td>";
                    echo "<td>" . $alumno->getNombre() . "</td>";
                    echo "<td>" . $alumno->getEmail() . "</td>";
                    echo "<td>" . str_repeat("*",strlen($alumno->getPassword())) . "</td>";
                    echo "<td>" . $alumno->getIdioma() . "</td>";
                    echo "<td>" . $alumno->getMatricula() . "</td>";
                    echo "<td>" . $alumno->getLenguaje() . "</td>";
                    echo "<td>" . $alumno->getFecha() . "</td>";
                    echo "<td><img src='imagenes/".$alumno->getImagen()."' width='48px' height='48px'></td>";
                    echo "<td>";
                    echo "<a href='vistas/read.php?id=" . encode($alumno->getId()) . "' title='Ver Alumno/a' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                    echo "<a href='vistas/update.php?id=" . encode($alumno->getId()) . "' title='Actualizar Alumno/a' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                    echo "<a href='vistas/delete.php?id=" . encode($alumno->getId()) . "' title='Borar Alumno/a' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
                echo "<ul class='pager' id='no_imprimir'>"; //  <ul class="pagination">
                echo $paginador->crearLinks($enlaces);
                echo "</ul>";
            } else {
                // Si no hay nada seleccionado
                echo "<p class='lead'><em>No se ha encontrado datos de alumnos/as.</em></p>";
            }
            ?>

        </div>
    </div>
    <div id="no_imprimir">
    <?php
        // Leemos la cookie
        if(isset($_COOKIE['CONTADOR'])){
            echo $contador;
            echo $acceso;
        }
        else
            echo "Es tu primera visita hoy";
    ?>
    </div>
    <br><br><br> 