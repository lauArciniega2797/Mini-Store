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
        require_once 'view/dashboard.php';
    }
    public function getProductsToFinish(){

    }
} 

class products{
    public function index(){
        require_once 'view/new-product.php';
    }
    public function saveProducts(){
        // var_dump($_POST);
        if($_POST){
            // $datos = json_decode($_POST['data'], true);
            // parse_str($_POST['data'], $datos);
            var_dump($_POST);
            // $price = $datos['price'];
            // // var_dump(get_object_vars($datos));
            // $name = $datos['name'];
            // $procedence_store = $datos['procedence_store'];
            // $store_price = $datos['store_price'];
            // $quantity = $datos['quantity'];
            // // $image = $_FILES[$datos['image']] != null ? $_FILES[$datos['image']] : false;
            // $image = $datos['image'];
            // var_dump($name);
            // var_dump($price);
            // var_dump($procedence_store);
            // var_dump($store_price);
            // var_dump($quantity);
            // var_dump($image);
            // // $name = $_POST['name'];
            // // $price = $_POST['price'];
            // // $procedence_store = $_POST['procedence_store'];
            // // $store_price = $_POST['store_price'];
            // // $quantity = $_POST['quantity'];
            // // $image = isset($_FILES['image']) ? $_FILES['image'] : false;

            // $image_name = null;
            // if ($image) {
            //     $image_name = $image['name'];
            //     if ($image_name != null) {
            //         //almacenar la imagen en un directorio
            //         $directory = $_SERVER['DOCUMENT_ROOT'].'/Mini-Store/images/';
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

            // $query = "INSERT INTO users VALUES(null,'$name',$price,'$image','$procedence_store',$store_price,$quantity,'$status')";
            // include 'conexion.php'; //si la pongo por fuera, no funciona :(
            // $query_prepare = $con->prepare($query);
            // $query_prepare->execute();

            // if ($query_prepare->execute()) {
            //     echo 'todo chido';
            // } else {
            //     echo 'malo';
            // }
        }
    }
}
?>