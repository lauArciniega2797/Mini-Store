<?php
  session_start();
  include 'function.php';

  if (isset($_GET['page'])) {

    if (!empty($_GET['page'])) {

      if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {

        $clase = $_GET['page'];

      } else {

        $clase ="user";

      }
    } else {

      if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {

        $clase = "dashboard";
        
      } else {

        $clase = "user";
        
      }
    }
  }
  else {

    if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {

      $clase = "dashboard";

    } else {

      $clase = "user";

    }
  }

  if (class_exists($clase)) { //si la clase anterior existe

    $objectInstance = new $clase; //instanciamos la clase
    
    if (isset($_GET['action'])) {

      if (!empty($_GET['action'])) {

        if (method_exists($objectInstance, $_GET['action'])) {

          $action = $_GET['action'];
          
        } else{
          
          $action = "index";

        }
      } else {

        $action = 'index';

      }
    } else {
      
      $action = 'index';
      
    }
    
    $objectInstance->$action();
  }

?>