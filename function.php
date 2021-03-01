<?php
class user{
    public function index(){
        require_once 'login.php';
    }
    public function closeSesion(){
        session_destroy();
        header('Location: ?page=user&action=');
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
            $function = "";$id="";
            if(isset($_GET['function']) && !empty($_GET['function'])){
                $function = $_GET['function'];
            }
            if(isset($_GET['id']) && !empty($_GET['id'])){
                $id =  $_GET['id'];
            }
            if (isset($_POST['function']) && !empty($_POST['function'])) {
                $function = $_POST['function'];
            }
            if (isset($_POST['id']) && !empty($_POST['id'])) {
                $id = $_POST['id'];
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
        
                    $status = "";
                    if ($quantity >= 2 && $quantity <= 5) {
                        $status = "warning";
                    } else if($quantity >= 0 && $quantity <= 1){
                        $status = "empty";
                    } else {
                        $status = "full";
                    }
                    $image_name = null;
                    if ($image) {
                        $image_name = $image['name'];
                        if ($image_name != null) {
                            //almacenar la imagen en un directorio
                            $directory = 'images/';
                            move_uploaded_file($image['tmp_name'],$directory.$image_name);
                            $query = "UPDATE products SET name = '$name', price = $price, image = '$image_name', procedence_store = '$procedence_store', store_price = $store_price, quantity=$quantity, status = '$status' WHERE id = $id";
                        } else {
                            $query = "UPDATE products SET name = '$name', price = $price, procedence_store = '$procedence_store', store_price = $store_price, quantity=$quantity, status = '$status' WHERE id =  $id";
                        }
                    }
        
                    var_dump($query);
                    // $query = "INSERT INTO products VALUES(null,'$name',$price,'$image_name','$procedence_store',$store_price,$quantity,'$status')";
                    include 'conexion.php'; //si la pongo por fuera, no funciona :(
                    $query_prepare = $con->prepare($query);
                    if($query_prepare->execute()){
                        echo 'done';
                    }
                    break;
                case 'deleteProduct':
                    $query = "DELETE FROM products WHERE id = $id";
                    include 'conexion.php'; //si la pongo por fuera, no funciona :(
                    $query_prepare = $con->prepare($query);
                    if($query_prepare->execute()){
                        echo 'done';
                    }
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
    public function getProductToDelete(){
        $id="";
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $_GET['id'];
        }
        $query = "SELECT id, name, image FROM products where id=$id";
        include 'conexion.php';
        $query_prepare = $con->prepare($query);
        $query_prepare->execute();
        $row = $query_prepare->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($row);
        
        echo json_encode($row);
    }

}

class clients {
    public function index(){
        $client = $this->getClients();
        require_once 'view/clients.php';
    }
    public function newClient(){
        $id="";$client="";$action="";
        if (isset($_GET['parameter']) && !empty($_GET['parameter'])) {
            $client = $this->getClientToEdit($_GET['parameter']);
            $action = 'editClient';
        }
        require_once 'view/new-client.php';
    }
    // http://localhost/Mini-Store/?page=clients&action=saveClient&function=undefined&id=undefined
    public function saveClient(){
        if($_POST){
            var_dump($_POST);
            $function = "";$id="";
            if(isset($_GET['function']) && !empty($_GET['function'])){
                $function = $_GET['function'];
            }
            if(isset($_GET['id']) && !empty($_GET['id'])){
                $id =  $_GET['id'];
            }
            if (isset($_POST['function']) && !empty($_POST['function'])) {
                $function = $_POST['function'];
            }
            if (isset($_POST['id']) && !empty($_POST['id'])) {
                $id = $_POST['id'];
            }
            var_dump($id);
            var_dump($function);
            switch ($function) {
                case 'newClient':
                    $name = $_POST['name'];
                    $email = $_POST['email'];
                    $phone = $_POST['phone'];
                    $bank_reference = $_POST['bank_reference'];
                    $query="";

                    if (isset($_POST["checkCredit"]) && $_POST["checkCredit"] == 0){
                        echo "Checkbox seleccionado";
                        $credit_limit = $_POST['credit_limit'];
                        $credit_days = $_POST['credit_days'];
                        // INSERT INTO clients VALUES(null,'margarita','example@example.com',15467984,0,200,2,1234567890)
                        $query = 'INSERT INTO clients VALUES(null,"'.$name.'","'.$email.'",'.$phone.','.$_POST['checkCredit'].','.$credit_limit.','.$credit_days.','.$bank_reference.')';
                    } else{
                        echo "Checkbox no seleccionado";
                        $query = 'INSERT INTO clients VALUES(null,"'.$name.'","'.$email.'",'.$phone.',1,null,null,'.$bank_reference.')';
                        // $query = 'INSERT INTO clients VALUES(null,'$name','$email','$phone',null,null,null,'$bank_reference')';
                    }
                    include 'conexion.php'; //si la pongo por fuera, no funciona :(
                    $query_prepare = $con->prepare($query);
                    if($query_prepare->execute()){
                        echo 'done';
                    }
                    break;
                case 'editClient':
                    echo 'Esta llegando a editar';
                    $name = $_POST['name'];
                    $email = $_POST['email'];
                    $phone = $_POST['phone'];
                    $bank_reference = $_POST['bank_reference'];
                    $query="";

                    if (isset($_POST["checkCredit"]) && $_POST["checkCredit"] == 0){
                        echo "Checkbox seleccionado";
                        $credit_limit = $_POST['credit_limit'];
                        $credit_days = $_POST['credit_days'];
                        // INSERT INTO clients VALUES(null,'margarita','example@example.com',15467984,0,200,2,1234567890)
                        $query = 'UPDATE clients SET name = "'.$name.'", email = "'.$email.'", phone = '.$phone.', approved_credit = '.$_POST['checkCredit'].', credit_limit = '.$credit_limit.', credit_days = '.$credit_days.', bank_reference = '.$bank_reference.' WHERE id = '.$id;
                    } else{
                        echo "Checkbox no seleccionado";
                        $query = 'UPDATE clients SET name = "'.$name.'", email = "'.$email.'", phone = '.$phone.', approved_credit = 1, credit_limit = null, credit_days = null, bank_reference = '.$bank_reference.' WHERE id = '.$id;
                    }

                    var_dump($query);
                    include 'conexion.php'; //si la pongo por fuera, no funciona :(
                    $query_prepare = $con->prepare($query);
                    if($query_prepare->execute()){
                        echo 'done';
                    }
                    break;
                case 'deleteClient':
                    $query = "DELETE FROM clients WHERE id = $id";
                    include 'conexion.php'; //si la pongo por fuera, no funciona :(
                    $query_prepare = $con->prepare($query);
                    if($query_prepare->execute()){
                        echo 'done';
                    }
                    break;
            }
        }
    }
    public function getClients(){
        $query = "SELECT * FROM clients ORDER BY id";
        include 'conexion.php';
        $query_prepare = $con->prepare($query);
        $query_prepare->execute();
        $row = $query_prepare->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    public function getClientToEdit($id){
        $query = "SELECT * FROM clients where id=$id";
        include 'conexion.php';
        $query_prepare = $con->prepare($query);
        $query_prepare->execute();
        $row = $query_prepare->fetchAll(PDO::FETCH_ASSOC);
        foreach ($row as $producto) {
            return $producto;
        }
    }
    public function getClientToDelete(){
        $id="";
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $_GET['id'];
        }
        $query = "SELECT id, name FROM clients where id=$id";
        include 'conexion.php';
        $query_prepare = $con->prepare($query);
        $query_prepare->execute();
        $row = $query_prepare->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($row);
        
        echo json_encode($row);
    }
}
?>