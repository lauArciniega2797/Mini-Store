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
                        $query = 'INSERT INTO clients VALUES(null,"'.$name.'","'.$email.'",'.$phone.','.$_POST['checkCredit'].','.$credit_limit.','.$credit_days.',"'.$bank_reference.'")';
                    } else{
                        echo "Checkbox no seleccionado";
                        $query = 'INSERT INTO clients VALUES(null,"'.$name.'","'.$email.'",'.$phone.',1,null,null,"'.$bank_reference.'")';
                        // $query = 'INSERT INTO clients VALUES(null,'$name','$email','$phone',null,null,null,'$bank_reference')';
                    }
                    var_dump($query);
                    include 'conexion.php'; //si la pongo por fuera, no funciona :(
                    $query_prepare = $con->prepare($query);
                    if($query_prepare->execute()){
                        echo 'done';
                    }
                    break;
                case 'editClient':
                    echo 'Esta llegando a editar';
                    $name = $_POST['name'];
                    $email = isset($_POST['email']) && !empty($_POST['email']) ? $_POST['email'] : 'N/A';
                    $phone = isset($_POST['phone']) && !empty($_POST['phone']) ? $_POST['phone'] : 'null';
                    $bank_reference = $_POST['bank_reference'];
                    $query="";

                    if (isset($_POST["checkCredit"]) && $_POST["checkCredit"] == 0){
                        echo "Checkbox seleccionado";
                        $credit_limit = $_POST['credit_limit'];
                        $credit_days = $_POST['credit_days'];
                        // INSERT INTO clients VALUES(null,'margarita','example@example.com',15467984,0,200,2,1234567890)
                        $query = 'UPDATE clients SET name = "'.$name.'", email = "'.$email.'", phone = '.$phone.', approved_credit = '.$_POST['checkCredit'].', credit_limit = '.$credit_limit.', credit_days = '.$credit_days.', bank_reference = "'.$bank_reference.'" WHERE id = '.$id;
                    } else{
                        echo "Checkbox no seleccionado";
                        $query = 'UPDATE clients SET name = "'.$name.'", email = "'.$email.'", phone = '.$phone.', approved_credit = 1, credit_limit = null, credit_days = null, bank_reference = "'.$bank_reference.'" WHERE id = '.$id;
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

class providers {
    public function index(){
        $providers = $this->getProviders();
        require_once 'view/providers.php';
    }
    public function newProvider(){
        $id="";$provider="";$action="";
        if (isset($_GET['parameter']) && !empty($_GET['parameter'])) {
            $providers = $this->getProviderToEdit($_GET['parameter']);
            $action = 'editProvider';
        }
        require_once 'view/new-provider.php';
    }
    public function saveProvider(){
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
                case 'newProvider':
                    $rfc = $_POST['rfc'];
                    $comercial_name = $_POST['comercial_name'];
                    $type = $_POST['type'];
                    $phone = $_POST['phone'];
                    $street = $_POST['street'];
                    $suburb = $_POST['suburb'];
                    $number = $_POST['number'];
                    $postal_code = $_POST['postal_code'];
                    $tag = $_POST['tag'];
                    // "INSERT INTO providers VALUES('sdfsdfasdf6as54d6fa464','Chavita','Dulceria',,'','',,,'Pesimo jefe')"
                    $query="INSERT INTO providers VALUES(null,'$rfc','$comercial_name','$type',$phone,'$street','$suburb',$number,$postal_code,'$tag')";
                    var_dump($query);
                    include 'conexion.php'; //si la pongo por fuera, no funciona :(
                    $query_prepare = $con->prepare($query);
                    if($query_prepare->execute()){
                        echo 'done';
                    }
                    break;
                case 'editProvider':
                    $rfc = $_POST['rfc'];
                    $comercial_name = $_POST['comercial_name'];
                    $type = $_POST['type'];
                    $phone = $_POST['phone'];
                    $street = $_POST['street'];
                    $suburb = $_POST['suburb'];
                    $number = $_POST['number'];
                    $postal_code = $_POST['postal_code'];
                    $tag = $_POST['tag'];
                    // $query="INSERT INTO providers VALUES(null,'$rfc','$comercial_name','$type',$phone,'$street','$suburb',$number,$postal_code,'$tag')";
                    $query="UPDATE providers SET RFC='$rfc',comercial_name='$comercial_name',type='$type',phone=$phone,street='$street',suburb='$suburb',number=$number,postal_code=$postal_code,tag='$tag' WHERE id = $id";
                    var_dump($query);
                    include 'conexion.php'; //si la pongo por fuera, no funciona :(
                    $query_prepare = $con->prepare($query);
                    if($query_prepare->execute()){
                        echo 'done';
                    }
                    break;
                case 'deleteProvider':
                    $query = "DELETE FROM providers WHERE id = $id";
                    include 'conexion.php'; //si la pongo por fuera, no funciona :(
                    $query_prepare = $con->prepare($query);
                    if($query_prepare->execute()){
                        echo 'done';
                    }
                    break;
            }
        }
    }
    public function getProviders(){
        $query = "SELECT * FROM providers ORDER BY id";
        include 'conexion.php';
        $query_prepare = $con->prepare($query);
        $query_prepare->execute();
        $row = $query_prepare->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    public function getProviderToEdit($id) {
        $query = "SELECT * FROM providers where id=$id";
        include 'conexion.php';
        $query_prepare = $con->prepare($query);
        $query_prepare->execute();
        $row = $query_prepare->fetchAll(PDO::FETCH_ASSOC);
        foreach ($row as $provider) {
            return $provider;
        }
    }
    public function getProviderToDelete(){
        $id="";
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $_GET['id'];
        }
        $query = "SELECT id, comercial_name FROM providers where id = $id";
        var_dump($query);
        include 'conexion.php';
        $query_prepare = $con->prepare($query);
        $query_prepare->execute();
        $row = $query_prepare->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($row);
        
        echo json_encode($row);
    }
}

class sales {
    public function index(){
        require_once 'view/sales.php';
    }
    public function newSale(){
        $id="";$sale="";$action="";
        if (isset($_GET['parameter']) && !empty($_GET['parameter'])) {
            $sale = $this->getSaleToEdit($_GET['parameter']);
            $action = 'editSale';
        }

        include 'conexion.php';
        $client = new clients(); //instancia de los objetos
        $product = new products();

        //folio automatico de venta
        $query_count = $con->prepare("SELECT COUNT(*) FROM sales");
        $query_count->execute();
        
        $count = $query_count->fetchAll(PDO::FETCH_ASSOC); //trae el total de rows en la tabla
        $clients = $client->getClients(); //traer todos los productos y clientes
        $products = $product->getProducts(); //trae todos los productos

        require_once 'view/new-sale.php';
    }
    public function getDataSelectedClient() {
        $id="";
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $id = $_POST['id'];
        }
        include 'conexion.php';
        $query_prepare = $con->prepare("SELECT approved_credit, credit_limit, credit_days FROM clients WHERE id = $id");
        $query_prepare->execute();
        $client = $query_prepare->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($client);
    }
    public function getDataSelectedProduct() {
        $id="";
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $id = $_POST['id'];
        }
        include 'conexion.php';
        $query_prepare = $con->prepare("SELECT * FROM clients WHERE id = $id");
        $query_prepare->execute();
        $product = $query_prepare->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($product);
    }
    // public $array_product = array();
    public function addProductToCar(){
        $id="";$cant="";$disponible="";
        
        if ((isset($_POST['id']) && !empty($_POST['id'])) && (isset($_POST['cantidad']) && !empty($_POST['cantidad']))) {
            $id = $_POST['id'];
            $cant = $_POST['cantidad'];
        }
        include 'conexion.php';
        $query_prepare = $con->prepare("SELECT id, name, store_price, quantity, status FROM products WHERE id = $id");
        $query_prepare->execute();
        $product = $query_prepare->fetchAll(PDO::FETCH_ASSOC);
        foreach ($product as $value) {
            $total_product =$value['store_price'] * $cant;
            $newdata =  array (
                'id' => $value['id'],
                'Producto' => $value['name'],
                'Precio' => (int)$value['store_price'],
                'Cantidad' => (int)$cant,
                'Disponibles' => (int)$value['quantity'],
                'Total' => $total_product,
                'Sale' => ''
            );
        }
        echo json_encode($newdata);
        // return $array_product;
    }
    public function saveSale(){
        if ($_POST) {
            include 'conexion.php';
            $flag=false; $query=""; $id = 0; $action = '';
            if (isset($_POST['action']) && !empty($_POST['action'])) {
                $action = $_POST['action'];
            }
            var_dump($action);
            // Actaualiza el credito en el cliente en caso de que la venta sea a credito
            switch ($action) {
                case 'editSale':
                    echo 'Esta llegando a editar';
                    $array_productos = json_decode($_POST['products'], true);
                    $query="";
                    $id_sale = isset($_GET['parameter']) && !empty($_GET['parameter']) ? $_GET['parameter'] : false;
                    $cliente = isset($_POST['client']) && !empty($_POST['client']) ? $_POST['client'] : false;
                    $credit = isset($_POST['credit']) && !empty($_POST['credit']) ? $_POST['credit'] : false;
                    $status = isset($_POST['status']) && !empty($_POST['status']) ? $_POST['status'] : false;
                    $total = isset($_POST['total']) ? $_POST['total']*1 : false;
                    $subtotal = (isset($_POST['subtotal']) && !empty($_POST['subtotal'])) || $_POST['subtotal'] == '0' ? $_POST['subtotal'] : false;
                    if ($total <= 0) {
                        $total = '0';
                    }
                    if ($cliente && $id_sale && $status && $subtotal) {
                        if ($status && $status == 'credito') {
                            if ($credit) {
                                $query = "UPDATE clients SET credit_limit = ".$credit." WHERE id = ".$cliente;
                                $query_prepare = $con->prepare($query);
                                $query_prepare->execute();
                            }
                        }
                        $query_sale = $con->prepare("UPDATE sales SET id_client = ".$cliente.", pay_method = '".$status."', subtotal = ".$subtotal.", total = ".$total." WHERE id = ".$id_sale."");
                        $query_sale->execute();
                    }

                    //para los productos
                    foreach ($array_productos as $product) {
                        if ($product['Sale'] != "") {
                            $query_comparation = $con->prepare("SELECT quantity FROM sales_products WHERE id_product = ".$product['id']." AND sale_id = ".$product['Sale']."");
                            $query_comparation->execute();
                            $quantity_sale_product = $query_comparation->fetchAll(PDO::FETCH_ASSOC);
                            if ((int)$quantity_sale_product[0]['quantity'] != $product['Cantidad']) {
                                $actualizar_products = $con->prepare("UPDATE products SET quantity = ".($product['Disponibles'] - $product['Cantidad'])." WHERE id = ".$product['id']."");
                                $actualizar_products->execute();
                            }
                            $query_product_update = $con->prepare("UPDATE sales_products SET id_product = ".$product['id'].", quantity = ".$product['Cantidad']." WHERE id_product = ".$product['id']." AND sale_id = ".$product['Sale']."");
                            $query_product_update->execute();
                        } else {
                            $query_product_insert = $con->prepare("INSERT INTO sales_products VALUES(null, ".$id_sale.", ".$product['id'].", ".$product['Cantidad'].")");
                            $query_product_insert->execute();
                        }
                    }

                    break;
                
                default:
                    echo 'Esta haciendo una nueva venta';
                    if ($_POST['status'] == 'credito') {
                        $query = "UPDATE clients SET credit_limit = ".$_POST['credit']." WHERE id = ".$_POST['client'];
                        var_dump($query);
                        $query_prepare = $con->prepare($query);
                        $query_prepare->execute();
                    }
        
                    // Inserta los datos de la venta en la tabla de la venta
                    $query_sale = "INSERT INTO sales VALUES(null, ".$_POST['client'].",'".$_POST['folio']."','".$_POST['status']."',CURRENT_TIME(),".$_POST['subtotal'].",".$_POST['total'].")";
                    var_dump($query_sale);
                    $query_prepare_sale = $con->prepare($query_sale);
                    $query_prepare_sale->execute();
        
                    // Para insertar los productos de la venta
                        // Traer el id de la venta con el folio que esta en proceso
                            $query_folio_sale = $con->prepare("SELECT id FROM sales WHERE folio = ".$_POST['folio']);
                            var_dump($query_folio_sale);
                            $query_folio_sale->execute();
                            $folio_sale = $query_folio_sale->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($folio_sale as $folio) {
                                $id = $folio;
                            }
                            var_dump($id);
        
                    $products = json_decode($_POST['products'],true); //el parametro true vuelve array al objeto json
                    var_dump($products);
                    foreach ($products as $value) {
                        var_dump("INSERT INTO sales_products VALUES(null,".$id['id'].",".$value['id'].",".$value['Cantidad'].")");
                        $query_product = $con->prepare("INSERT INTO sales_products VALUES(null,".$id['id'].",".$value['id'].",".$value['Cantidad'].")");
                        $query_product->execute();
        
                        //actualizar cantidad en stock de cada producto
                        var_dump("UPDATE products SET quantity = ".($value['Disponibles'] - $value['Cantidad'])." WHERE id = ".$value['id']."");
                        $query_product_prepare = $con->prepare("UPDATE products SET quantity = ".($value['Disponibles'] - $value['Cantidad'])." WHERE id = ".$value['id']."");
                        $query_product_prepare->execute();
                        $flag = true;
                    }
        
                    if ($flag) {
                        print_r('done');
                    }
                    break;
            }
        }
    }
    public function showSales(){
        include 'conexion.php';
        $folio_array = [];
        $products_array = [];
        
        $query_products = $con->prepare("SELECT sp.sale_id, p.name as 'producto', sp.quantity as 'cantidad' FROM sales_products sp INNER JOIN products p ON p.id = sp.id_product");
        $query_products->execute();
        $sale_products = $query_products->fetchAll(PDO::FETCH_ASSOC);

        // Llenar array de productos
        foreach ($sale_products as $product) {
            array_push($products_array, [$product['sale_id'], $product['producto'], $product['cantidad']]);
        }
        
        $query_sale = $con->prepare("SELECT s.id, s.folio, c.name AS 'cliente', s.pay_method, s.total, s.fecha FROM sales s INNER JOIN clients c ON c.id = s.id_client;");
        $query_sale->execute();
        $sales = $query_sale->fetchAll(PDO::FETCH_ASSOC);

        // Llenar array de folios y tr
        foreach ($sales as $index => $sale) {
            if (!in_array($sale['folio'], $folio_array)){
                array_push($folio_array, $sale['folio']);
                echo "<tr>";
                echo "    <td>".$index++."</td>";
                echo "    <td>".$sale['folio']."</td>";
                echo "    <td>".$sale['cliente']."</td>";
                echo "    <td><a class='expand' data-sale='".$sale['id']."'>Expandir</a></td>";
                echo "    <td>".$sale['pay_method']."</td>";
                echo "    <td>".$sale['total']."</td>";
                echo "    <td>".$sale['fecha']."</td>";
                echo "    <td>";
                echo "      <a href='?page=sales&action=newSale&parameter=".$sale['id']."' class='btn btn-primary'>Editar</a>";
                echo "      <a type='button' data-bs-toggle='modal' data-bs-target='#deleteModal' class='deleteSaleData btn btn-danger' data-href='?page=sales&action=getSaleToDelete&id=".$sale['id']."'>Eliminar</a>";
                echo "    </td>";
                echo "</tr>";
                echo "<tr>";
                echo "    <td colspan='8'>";
                echo "        <div class='table-expandible' id='".$sale['id']."'>";
                echo "            <table>";
                echo "                <thead>";
                echo "                    <tr>";
                echo "                        <th>Producto</th>";
                echo "                        <th>Cantidad</th>";
                echo "                    </tr>";
                echo "                </thead>";
                echo "                <tbody>";
                foreach ($products_array as $value) {
                    if ($value['0'] == $sale['id']) {
                        echo "              <tr>";
                        echo "                  <td>".$value[1]."</td>";
                        echo "                  <td>".$value[2]."</td>";
                        echo "              </tr>";
                    }
                }
                echo "                </tbody>";
                echo "            </table>";
                echo "        </div>";
                echo "    </td>";
                echo "</tr>";
            }
        }
    }
    public function getSaleToEdit($id){
        include 'conexion.php';
        $query_prepare_product = $con->prepare("SELECT sp.sale_id, sp.id_product as 'id', p.name as 'producto', p.store_price as 'precio', sp.quantity as 'cantidad', p.quantity as 'disponibles', (sp.quantity * p.store_price) as 'total' FROM sales_products sp INNER JOIN products p ON p.id = sp.id_product WHERE sp.sale_id = $id");
        $query_prepare_product->execute();
        $products = $query_prepare_product->fetchAll(PDO::FETCH_ASSOC);

        $array_productos = [];
        foreach ($products as $index => $row){
            array_push($array_productos, ['index'=>($index+1), 'sale_id'=>$row['sale_id'], 'id_product'=>$row['id'], 'nombre'=>$row['producto'], 'precio'=>$row['precio'], 'cantidad'=>$row['cantidad'], 'disponibles'=>$row['disponibles'], 'total'=>$row['total']]);
        }
        $query_prepare = $con->prepare("SELECT * FROM sales WHERE id = $id");
        $query_prepare->execute();
        $sale = $query_prepare->fetchAll(PDO::FETCH_ASSOC);

        foreach ($sale as $row) {
            return array($row, $array_productos); 
        }
    }
}
?>