<?php
  session_start();
  include 'function.php';
  if (isset($_GET['page'])) {
    if (!empty($_GET['page'])) {
      if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
        $clase = $_GET['page'];
      } else {
        $clase ="user";
        // header('Location: ?page=user&action=');
      }
    } else {
      if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
        $clase = "dashboard";
      } else {
        $clase = "user";
        // header('Location: ?page=user&action=');
      }
    }
  }
  else {
    echo 'Clase no declarada en la url';
  }

  if (class_exists($clase)) { //si la clase anterior existe
    $objectInstance = new $clase; //instanciamos la clase
    
    if (isset($_GET['action'])) {
      if (!empty($_GET['action'])) {
        if (method_exists($objectInstance, $_GET['action'])) {
          $action = $_GET['action'];
          $objectInstance->$action();
        } else{
          echo 'Esa no es una accion valida';
        }
      } else {
        $action = 'index';
        $objectInstance->$action();
      }
    } else {
      echo 'Action no declarada en la url';
    }

    // if (isset($_GET['action']) && !empty($_GET['action'])) {
    //   if (method_exists($objectInstance, $_GET['action'])) {
    //     $action = $_GET['action'];
    //     $objectInstance->$action();
    //   } else{
    //     echo 'Esa no es una accion valida';
    //   }
    // } else if(empty($_GET['action'])) {
    //   if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    //     $action = "index";
    //   } else {
    //     $action = "index"; //aqui va el login
    //   }
    //   $objectInstance->$action();
    // } else {
    //   echo 'Esa acción no es valida';
    // }
  } else {
    echo 'la clase no existe';
  }

?>