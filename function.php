<?php
class user{
    public function login(){
        require_once 'login.php';
    }
    public function validateLogin(){
        if (isset($_POST['data'])) {
            // echo 'pero que ha pasao';
            // var_dump($_P OST["userdata"]);
            parse_str($_POST["data"], $userdata); // -----------------This is how we unserialize a form data
            // var_dump($userdata['username']); var_dump($userdata['password']);
            // $action = $_POST['action'];
            $name = $userdata['username'];
            $pass = $userdata['password'];
            $query = "SELECT user_name, password FROM users WHERE user_name = '$name' AND password = '$pass'";
            // var_dump($query);
            // Es mejor prepare porque nos ayuda a evitar el sql injection
            include 'conexion.php'; //si la pongo por fuera, no funciona :(
            $query_prepare = $con->prepare($query);
            $query_prepare->execute();
            
            // El fetchColumn puede acceder a los resultados por columna ("columnas": id,user_name,password)
            // Row count cuenta los resultados arrojados por la consulta
            if ($query_prepare->rowCount() > 0) {
                $user = $query_prepare->fetchAll();
                foreach ($user as $row) {
                    $_SESSION['user'] = $row["user_name"];
                }
                echo 'Correct';
            } else {
                echo 'Fail';
            }
        }
    }

}

class dashboard {
    public function index(){
        $product = new products();
        $productos = $product->getProductsToFinish();
        require_once 'view/dashboard.php';
    }
} 

class products{
    public function index(){
        // if(isset($_GET['parameter'])){
        //     echo 'que transa';
        // }
        $productos = $this->getProducts();
        require_once 'view/products.php';
    }
    public function newProduct(){
        $id="";$product="";$action="";
        if (isset($_GET['parameter']) && !empty($_GET['parameter'])) {
            $product = $this->getProductToEdit($_GET['parameter']);
            $action = 'editProduct';
        }
        require_once 'view/new-product.php';
    }
    public function saveProducts(){
        if($_POST){
            $function = "";
            if(isset($_GET['function']) && !empty($_GET['function'])){
                $function =  $_GET['function'];
            }

            switch ($function) {
                case 'newProduct':
                    $name = $_POST['name'];
                    $price = $_POST['price'];
                    $procedence_store = $_POST['procedence_store'];
                    $store_price = $_POST['store_price'];
                    $quantity = $_POST['quantity'];
                    $image = isset($_FILES['image']) ? $_FILES['image'] : false;
        
                    $image_name = null;
                    if ($image) {
                        $image_name = $image['name'];
                        if ($image_name != null) {
                            //almacenar la imagen en un directorio
                            $directory = 'images/';
                            move_uploaded_file($image['tmp_name'],$directory.$image_name);
                        }
                    }
        
                    $status = "";
                    if ($quantity >= 2 && $quantity <= 5) {
                        $status = "warning";
                    } else if($quantity >= 0 && $quantity <= 1){
                        $status = "empty";
                    } else {
                        $status = "full";
                    }
        
                    $query = "INSERT INTO products VALUES(null,'$name',$price,'$image_name','$procedence_store',$store_price,$quantity,'$status')";
                    include 'conexion.php'; //si la pongo por fuera, no funciona :(
                    $query_prepare = $con->prepare($query);
                    if($query_prepare->execute()){
                        echo 'done';
                    }
                    break;
                case 'editProduct':
                    $name = $_POST['name'];
                    $price = $_POST['price'];
                    $procedence_store = $_POST['procedence_store'];
                    $store_price = $_POST['store_price'];
                    $quantity = $_POST['quantity'];
                    $image = isset($_FILES['image']) ? $_FILES['image'] : false;
        
                    var_dump($image);


                    // $image_name = null;
                    // if ($image) {
                    //     $image_name = $image['name'];
                    //     if ($image_name != null) {
                    //         //almacenar la imagen en un directorio
                    //         $directory = 'images/';
                    //         move_uploaded_file($image['tmp_name'],$directory.$image_name);
                    //     }
                    // }
        
                    // $status = "";
                    // if ($quantity >= 2 && $quantity <= 5) {
                    //     $status = "warning";
                    // } else if($quantity >= 0 && $quantity <= 1){
                    //     $status = "empty";
                    // } else {
                    //     $status = "full";
                    // }
        
                    // $query = "INSERT INTO products VALUES(null,'$name',$price,'$image_name','$procedence_store',$store_price,$quantity,'$status')";
                    // include 'conexion.php'; //si la pongo por fuera, no funciona :(
                    // $query_prepare = $con->prepare($query);
                    // if($query_prepare->execute()){
                    //     echo 'done';
                    // }
                    break;
                case 'deleteProduct':
                    # code...
                    break;
                default:
                    # code...
                    break;
            }
        }
    }
    public function getProducts(){
        $query = "SELECT * FROM products ORDER BY name ASC";
        include 'conexion.php';
        $query_prepare = $con->prepare($query);
        $query_prepare->execute();
        $row = $query_prepare->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    public function getProductsToFinish(){
        $query = "SELECT * FROM products where status = 'warning'";
        include 'conexion.php';
        $query_prepare = $con->prepare($query);
        $query_prepare->execute();
        $row = $query_prepare->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    public function getProductsFinished(){
        $query = "SELECT * FROM products where status = 'empty'";
        include 'conexion.php';
        $query_prepare = $con->prepare($query);
        $query_prepare->execute();
        $row = $query_prepare->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    public function getProductToEdit($id){
        $query = "SELECT * FROM products where id=$id";
        include 'conexion.php';
        $query_prepare = $con->prepare($query);
        $query_prepare->execute();
        $row = $query_prepare->fetchAll(PDO::FETCH_ASSOC);
        foreach ($row as $producto) {
            return $producto;
        }
    }
    public function getProductToDelete($id){
        $query = "SELECT * FROM products where status = 'warning' LIMIT 4";
        include 'conexion.php';
        $query_prepare = $con->prepare($query);
        $query_prepare->execute();
        $row = $query_prepare->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }

}
?>