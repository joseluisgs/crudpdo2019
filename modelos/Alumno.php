<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Alumno
 *
 * @author link
 */
class Alumno {
    //put your code here
    private $id;
    private $dni;
    private $nombre;
    private $email;
    private $password;
    private $idioma;
    private $matricula;
    private $lenguaje;
    private $fecha;
    private $imagen;

    
    // Constructor
    public function __construct($id, $dni, $nombre, $email, $password, $idioma, $matricula, $lenguaje, $fecha, $imagen) {
        $this->id = $id;
        $this->dni = $dni;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->password = $password;
        $this->idioma = $idioma;
        $this->matricula = $matricula;
        $this->lenguaje = $lenguaje;
        $this->fecha = $fecha;
        $this->imagen = $imagen;
    }
    
    // **** GETS & SETS
    function getId() {
        return $this->id;
    }

    function getDni() {
        return $this->dni;
    }

    function getNombre() {
        return $this->nombre;
    }

    
    function getEmail() {
        return $this->email;
    }

    function getPassword() {
        return $this->password;
    }

    function getIdioma() {
        return $this->idioma;
    }

    function getMatricula() {
        return $this->matricula;
    }

    function getLenguaje() {
        return $this->lenguaje;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getImagen() {
        return $this->imagen;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setDni($dni) {
        $this->dni = $dni;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setEmail($email) {
        $this->email = $email;
    }
    
    function setPassword($password) {
        //$this->password = md5($password);
        $this->password = md5($password);
    } 

    function setIdioma($idioma) {
        $this->idioma= $idioma;
    } 

    function setMatricula($matricula) {
        $this->matricula= $matricula;
    } 

    function setLenguaje($lenguaje) {
        $this->lenguaje= $lenguaje;
    } 

    function setFecha($fecha) {
        $this->fecha= $fecha;
    } 

    function setImagen($imagen) {
        $this->imagen= $imagen;
    } 
}

