<?php
session_start();
  include 'function.php';
  if (isset($_GET['page']) && !empty($_GET['page'])) { //si exite page y no esta vacia
    $clase = $_GET['page'];
  } else if(!isset($_GET['page'])) { //si no existe page y esta vacia
    if (isset($_SESSION['user'])) {
      $clase = "dashboard";
    } else {
      $clase = "user"; //aqui va el login
    }
  } else {
    echo 'Clase no valida';
  }

  if (class_exists($clase)) { //si la clase anterior existe
    $Objetcinstance = new $clase; //instanciamos la clase
    
    if (isset($_GET['action']) && !empty($_GET['action'])) {
      if (method_exists($Objetcinstance, $_GET['action'])) {
        $action = $_GET['action'];
        $Objetcinstance->$action();
      } else{
        echo 'Esa no es una accion valida';
      }
    } else if(empty($_GET['action'])) {
      if (isset($_SESSION['user'])) {
        $action = "index";
      } else {
        $action = "login"; //aqui va el login
      }
      $Objetcinstance->$action();
    } else {
      echo 'Esa acción no es valida';
    }
  }

?>