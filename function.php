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
        // $AllSales = $this->showSales();
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

        //Folio automatico de venta
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
                'Total' => $total_product
            );
        }
        echo json_encode($newdata);
        // return $array_product;
    }
    public function saveSale(){
        if ($_POST) {
            include 'conexion.php';
            $flag=false;$query="";
            if ($_POST['status'] == 'credito') {
                $query = "UPDATE clients SET credit_limit = ".$_POST['credit']." WHERE id = ".$_POST['client'];
                var_dump($query);
                $query_prepare = $con->prepare($query);
                $query_prepare->execute();
            }
            $products = json_decode($_POST['products'],true); //el parametro true vuelve array al objeto json
            var_dump($products);
            foreach ($products as $value) {
                var_dump("INSERT INTO sales VALUES(null,".$_POST['client'].",".$value['id'].",".$value['Cantidad'].",'".$_POST['folio']."','".$_POST['status']."',CURRENT_TIME(),".$_POST['subtotal'].",".$_POST['total'].")");
                $query_product = $con->prepare("INSERT INTO sales VALUES(null,".$_POST['client'].",".$value['id'].",".$value['Cantidad'].",'".$_POST['folio']."','".$_POST['status']."',CURRENT_TIME(),".$_POST['subtotal'].",".$_POST['total'].")");
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
        }
    }
    public function showSales(){
        include 'conexion.php';
        $folio_array = [];
        $products_array = [];
        $query="SELECT s.id, s.folio, c.name as 'Cliente', p.name as 'Producto', s.cantidad, s.pay_method as 'Estatus', s.total, s.fecha 
                FROM sales s
                INNER JOIN clients c ON c.id = s.id_client
                LEFT JOIN products p ON p.id = s.id_product";
        
        $query_prepare = $con->prepare($query);
        $query_prepare->execute();
        $sales = $query_prepare->fetchAll(PDO::FETCH_ASSOC);
        // ["folio"]=> string(3) "123" ["Cliente"]=> string(15) "maria guadalupe" ["Producto"]=> string(16) "mini trikitrakes" ["Estatus"]=> string(5) "fiado" ["total"]=> string(2) "60" ["fecha"]=> string(19) "2021-03-13 21:08:58" 
        // Llenar array de productos
        // var_dump($sales);
        foreach ($sales as $product) {
            array_push($products_array, [$product['folio'], $product['Producto'], $product['cantidad']]);
        }
        // Llenar array de folios y tr
        $index = 1;
        foreach ($sales as $sale) {
            if (!in_array($sale['folio'], $folio_array)){
                array_push($folio_array, $sale['folio']);
                echo "<tr>";
                echo "    <td>".$index++."</td>";
                echo "    <td>".$sale['folio']."</td>";
                echo "    <td>".$sale['Cliente']."</td>";
                echo "    <td><a class='expand' data-sale='".$sale['folio']."'>Expandir</a></td>";
                echo "    <td>".$sale['Estatus']."</td>";
                echo "    <td>".$sale['total']."</td>";
                echo "    <td>".$sale['fecha']."</td>";
                echo "    <td>";
                echo "      <a href='?page=sales&action=newSale&parameter=".$sale['folio']."' class='btn btn-primary'>Editar</a>";
                echo "      <a type='button' data-bs-toggle='modal' data-bs-target='#deleteModal' class='deleteSaleData btn btn-danger' data-href='?page=sales&action=getSaleToDelete&id=".$sale['id']."'>Eliminar</a>";
                echo "    </td>";
                echo "</tr>";
                echo "<tr>";
                echo "    <td colspan='8'>";
                echo "        <div class='table-expandible' id='".$sale['folio']."'>";
                echo "            <table>";
                echo "                <thead>";
                echo "                    <tr>";
                echo "                        <th>Producto</th>";
                echo "                        <th>Cantidad</th>";
                echo "                    </tr>";
                echo "                </thead>";
                echo "                <tbody>";
                foreach ($products_array as $value) {
                    if ($value['0'] == $sale['folio']) {
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
    public function getSaleToEdit($folio){
        include 'conexion.php';
        $query_prepare_product = $con->prepare("SELECT s.id_product as 'id', p.name, p.store_price, s.cantidad, p.quantity, (s.cantidad * p.store_price) as 'Total' FROM sales s INNER JOIN products p ON p.id = s.id_product WHERE s.folio = $folio");
        $query_prepare_product->execute();
        $products = $query_prepare_product->fetchAll(PDO::FETCH_ASSOC);

        $array_productos = [];
        foreach ($products as $index => $row){
            array_push($array_productos, ['index'=>($index+1), 'id_product'=>$row['id'], 'nombre'=>$row['name'], 'precio'=>$row['store_price'], 'cantidad'=>$row['cantidad'], 'disponibles'=>$row['quantity'], 'total'=>$row['Total']]);
        }
        $query_prepare = $con->prepare("SELECT * FROM sales WHERE folio = $folio");
        $query_prepare->execute();
        $sale = $query_prepare->fetchAll(PDO::FETCH_ASSOC);
        foreach ($sale as $row) {
            return array($row, $array_productos); 
        }
    }
}
?>