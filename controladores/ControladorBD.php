<?php

/**
 * Description of ConectorBD
 * V. 1.1
 * @author link
 */

/**
 * Conector BD usando objetos MySQL con PDO
 */
class ControladorBD {
    
    // Configuración del servidor
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "CRUD";
    private $server ="mysql";
    
    // Variables
    private $bd; // Relativo a la conexion de la base de datos
    private $rs; // ResultSet donde se almacena las consultas
    private $st; // donde se almacena el statement paremetrizado
    
    // Variable instancia para Singleton
    static private $instancia = null;

    // constructor--> Private por el patrón Singleton
    private function __construct() {
        //echo "Conector creado";
    }
    
     /**
     * Patrón Singleton. Ontiene una instancia del Manejador de la BD
     * @return instancia de conexion
     */
    public static function getControlador() {
        if (self::$instancia == null) {
            self::$instancia = new ControladorBD();
        }
        return self::$instancia;
    }

    /**
     * Abre la conexión a la BD
     */
    public function abrirBD() {
        try {
            // Preparado para mysql
            //$this->bd = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
            $this->bd = new PDO($this->server.":host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
            // Ponemos el modo de errores de PDO a excepciones
            $this->bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Conexión satisfactoria"; 
        } catch (PDOException $e) {
            die("conexión fallida " . $e->getMessage());
        }
    }

    /**
     * Cierra la conexión y el manejador de la BD
     */
    public function cerrarBD() {
        //$this->bd->close();
        $this->bd = null;
        $this->rs = null;
        $this->st = null;
        //echo "BD cerrada";
    }

    /**
     * Actualiza la BD a través de una consulta
     * @param type $consulta
     * @return boolean
     */

    public function actualizarBD($consulta, $parametros=null) {
        if($parametros!=null)
            return $this->actualizarBDParametros($consulta,$parametros);
        else
            return $this->actualizarBDDirecta($consulta);
    }

    private function actualizarBDDirecta($consulta){
        if ($this->bd->exec($consulta) != 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function actualizarBDParametros($consulta, $parametros){
        $this->st = $this->bd->prepare($consulta);
        return $this->st->execute($parametros);
    }


   
    /**
     * REaliza una consulta a la BD
     */

    public function consultarBD($consulta, $parametros=null){
        if($parametros!=null)
            return $this->consultarBDParametros($consulta,$parametros);
        else
            return $this->consultarBDDirecta($consulta);
            
    }

    private function consultarBDDirecta($consulta) {
        $this->rs = $this->bd->query($consulta);
        return $this->rs;
    }

    private function consultarBDParametros($consulta, $parametros) {
        $this->st = $this->bd->prepare($consulta);
        $this->st->execute($parametros);
        return $this->st;
    }

    /**
     * Devuelve los datos de conexion
     * @return type
     */
    public function datosConexion() {
        return $this->servername;
    }

    private function alerta($texto) {
        echo '<script type="text/javascript">alert("' . $texto . '")</script>';
    }

}
