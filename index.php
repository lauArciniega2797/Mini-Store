<?php
session_start();
  include 'function.php';

  if (isset($_GET['page'])) {
    $clase = $_GET['page'];
  } else if(!isset($_GET['page'])) {
    if (isset($_SESSION['user'])) {
      $clase = "dashboard";
    }
    $clase = "user"; //aqui va el login
  } else {
    echo 'Clase no valida';
  }

  if (class_exists($clase)) { //si la clase anterior existe
    $Objetcinstance = new $clase; //instanciamos la clase
    
    if (isset($_GET['action'])) {
      if (method_exists($Objetcinstance, $_GET['action'])) {
        $action = $_GET['action'];
        $Objetcinstance->$action();
      } else{
        echo 'Esa no es una accion valida';
      }
    } else if(!isset($_GET['action'])) {
      if (isset($_SESSION['user'])) {
        $action = "index";
      }
      $action = "login"; //aqui va el login
      $Objetcinstance->$action();
      var_dump($Objetcinstance, $action);
    } else {
      echo 'Esa acción no es valida';
    }
  }

?>