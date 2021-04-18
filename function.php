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
            parse_str($_POST["data"], $userdata); // -----------------This is how we unserialize a form data
            $name = $userdata['username'];
            $pass = $userdata['password'];
            $query = "SELECT user_name, password FROM users WHERE user_name = '$name' AND password = '$pass'";
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
        $proovedores = new providers();
        $getProviders = $proovedores->getProviders();
        require_once 'view/new-product.php';
    }
    public function saveProducts(){
        $all_done = false;
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
                    include 'conexion.php'; //si la pongo por fuera, no funciona :(
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
        
                    //proveedor
                    $provider='';$provider_name='';
                    $query_providers = $con->prepare('SELECT comercial_name FROM providers WHERE id='.$procedence_store);
                    if ($query_providers->execute()) {
                        $provider = $query_providers->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($provider as $value) {
                            $provider_name = $value['comercial_name'];
                        }
                        $all_done = true;
                    } else {
                        $all_done = false;
                    }

                    if ($all_done) {
                        $query = "INSERT INTO products VALUES(null,'$name',$price,'$image_name','$provider_name',$store_price,$quantity,'$status')";
                        $query_prepare = $con->prepare($query);
                        if($query_prepare->execute()){
                            print_r('done');
                        }
                    }
                    break;
                case 'editProduct':
                    include 'conexion.php'; //si la pongo por fuera, no funciona :(
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
                    //proveedor
                    $provider='';$provider_name='';
                    $query_providers = $con->prepare('SELECT comercial_name FROM providers WHERE id='.$procedence_store);
                    if ($query_providers->execute()) {
                        $provider = $query_providers->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($provider as $value) {
                            $provider_name = $value['comercial_name'];
                        }
                        $all_done = true;
                    } else {
                        $all_done = false;
                    }
                    $image_name = null;
                    if ($image) {
                        $image_name = $image['name'];
                        if ($image_name != null) {
                            //almacenar la imagen en un directorio
                            $directory = 'images/';
                            move_uploaded_file($image['tmp_name'],$directory.$image_name);
                            $query = "UPDATE products SET name = '$name', price = $price, image = '$image_name', procedence_store = '$provider_name', store_price = $store_price, quantity=$quantity, status = '$status' WHERE id = $id";
                        } else {
                            $query = "UPDATE products SET name = '$name', price = $price, procedence_store = '$provider_name', store_price = $store_price, quantity=$quantity, status = '$status' WHERE id =  $id";
                        }
                    }
                    // $query = "INSERT INTO products VALUES(null,'$name',$price,'$image_name','$procedence_store',$store_price,$quantity,'$status')";
                    if ($all_done) {
                        $query_prepare = $con->prepare($query);
                        if($query_prepare->execute()){
                            echo 'done';
                        }
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
        include 'conexion.php';
        $query = "SELECT * FROM products where id = $id";
        $query_prepare = $con->prepare($query);
        if($query_prepare->execute()){
            $row = $query_prepare->fetchAll(PDO::FETCH_ASSOC);
            foreach ($row as $producto) {
                $provedor_name='';$company_name=$producto['procedence_store'];
                $query_id_proveedor = $con->prepare("SELECT id FROM providers WHERE comercial_name='$company_name'");
                // var_dump($query_id_proveedor);
                if ($query_id_proveedor->execute()) {
                    $provedor = $query_id_proveedor->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($provedor as $value) {
                        $provedor_name = $value['id'];
                    }
                }
                return array($producto, $provedor_name);
            }
        }
    }
    public function getProductToDelete(){
        include 'conexion.php';
        $id="";
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $_GET['id'];
        }
        $query_prepare = $con->prepare("SELECT id, name, image FROM products where id=$id");
        if($query_prepare->execute()){
            $row = $query_prepare->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($row);
        }
    }
    public function filterProducts(){
        include 'conexion.php';
        if ($_POST) {
            $filter_per = $_POST['filter'];
            $filter_type = $_POST['filter_per'];
            $quantity = $_POST['quantity_filter'];
            $errores = false;
            $results = false;

            if ($filter_per == 'quantity') {
                if (!empty($quantity) && (int)$quantity >= 0) {
                    if ($filter_type != '') {
                        $query = "SELECT * FROM products WHERE $filter_per $filter_type $quantity";
                    } else {
                        $query = "SELECT * FROM products  ORDER BY name ASC";
                    }
                } else {
                    $query = "SELECT * FROM products  ORDER BY name ASC";
                }
            }else if ($filter_per == 'price') {
                if ((float)$filter_type > 0) {
                    $query = "SELECT * FROM products WHERE store_price <= ".(float)$filter_type;
                } else {
                    $query = "SELECT * FROM products  ORDER BY name ASC";
                }
            } else {
                $query = "SELECT * FROM products WHERE $filter_per LIKE '%$filter_type%' ORDER BY name ASC";
            }
            var_dump($query);
            $query_products_filter = $con->prepare($query);
            if($query_products_filter->execute()){
                $sale_products = $query_products_filter->fetchAll(PDO::FETCH_ASSOC);
                if (count($sale_products)) {
                    foreach ($sale_products as $index => $product) {
                        echo "<tr>";
                        echo "    <td>".($index + 1)."</td>";
                        if ($product['image'] == '') {
                            echo "    <td><img height='50px' src='https://images.unsplash.com/photo-1567039430063-2459256c6f05?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=1267&q=80' class='card-img-top' alt='Eunicodin'></td>";
                        } else {
                            echo "    <td><img height='50px' src='images/".$product['image']."' class='card-img-top' alt='Eunicodin'></td>";
                        }
                        echo "    <td>".$product['name']."</td>";
                        echo "    <td>$ ".$product['store_price']."</td>";
                        echo "    <td>$ ".$product['price']."</td>";
                        echo "    <td>".$product['procedence_store']."</td>";
                        echo "    <td>".$product['quantity']."</td>";
                        if($product['status'] == 'full'){
                        echo "    <td>Completo</td>";
                        } else if($product['status'] == 'warning'){
                        echo "    <td>Por agotarse</td>";
                        } else {
                        echo "    <td>Agotado</td>";
                        }
                        echo "    <td>";
                        echo "        <a href='?page=products&action=newProduct&parameter=".$product['id']."' class='btn btn-primary'><i class='fas fa-edit'></i></a>";
                        echo "        <a type='button' href='javascript:void(0)' data-bs-toggle='modal' data-bs-target='#deleteProductModal' class='deleteData btn btn-danger' onclick='eliminarProducto(".$product['id'].")'><i class='fas fa-trash-alt'></i></a>";
                        echo "    </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr>";
                    echo "    <td colspan='11' id='noCoincidencias'>No se encontraron coincidencias</td>";
                    echo "</tr>";
                }
            } else {
                $errores = true;
            }

            if (!$errores) {
                if ($results) {
                    print_r('No se encontraron coincidencias');
                }
            } else {
                print_r('Algo salio mal');
            }
        }
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
                case 'newClient':
                    include 'conexion.php'; //si la pongo por fuera, no funciona :(
                    $name = $_POST['name'];
                    $email = $_POST['email'] != '' ? $_POST['email'] : '';
                    $phone = $_POST['phone'] != '' ? $_POST['phone'] : null;
                    $bank_reference = $_POST['bank_reference'] != '' ? $_POST['bank_reference'] : '';
                    $query="";$no_exist_client = false;

                    //verificar que el cliente no exista en la tabla clientes
                    $query_comprobe_clients = $con->prepare("SELECT name FROM clients WHERE name LIKE '%$name%'");
                    if($query_comprobe_clients->execute()){
                        $exist = $query_comprobe_clients->fetchAll(PDO::FETCH_ASSOC);
                        if (count($exist) > 0) {
                            $no_exist_client = true;
                        }
                    }
                    if ($no_exist_client == false) {
                        if (isset($_POST["checkCredit"]) && $_POST["checkCredit"] == 0){
                            $credit_limit = $_POST['credit_limit'];
                            $credit_days = $_POST['credit_days'];
                            $credito = 'Aprobado';
                            if (is_null($phone)) {
                                $query = 'INSERT INTO clients VALUES(null,"'.$name.'","'.$email.'",null,"'.$credito.'",'.$credit_limit.','.$credit_days.',"'.$bank_reference.'")';
                            } else {
                                $query = 'INSERT INTO clients VALUES(null,"'.$name.'","'.$email.'",'.$phone.',"'.$credito.'",'.$credit_limit.','.$credit_days.',"'.$bank_reference.'")';
                            }
                            // INSERT INTO clients VALUES(null,'margarita','example@example.com',15467984,0,200,2,1234567890)
                        } else{
                            if (is_null($phone)) {
                                $query = 'INSERT INTO clients VALUES(null,"'.$name.'","'.$email.'",null,"Rechazado",null,null,"'.$bank_reference.'")';
                            } else {
                                $query = 'INSERT INTO clients VALUES(null,"'.$name.'","'.$email.'",'.$phone.',"Rechazado",null,null,"'.$bank_reference.'")';
                            }
                            // $query = 'INSERT INTO clients VALUES(null,'$name','$email','$phone',null,null,null,'$bank_reference')';
                        }
                        var_dump($query);
                        $query_prepare = $con->prepare($query);
                        if($query_prepare->execute()){
                            echo 'done';
                        }
                    }
                    break;
                case 'editClient':
                    include 'conexion.php'; //si la pongo por fuera, no funciona :(
                    $name = $_POST['name'];
                    $email = $_POST['email'] != '' ? $_POST['email'] : '';
                    $phone = $_POST['phone'] != '' ? $_POST['phone'] : null;
                    $bank_reference = $_POST['bank_reference'] != '' ? $_POST['bank_reference'] : '';
                    $query="";$no_exist_client = false;$no_modified_yet=false;

                    $edit_same_client = $con->prepare("SELECT name FROM clients WHERE id = ".$id);
                    if($edit_same_client->execute()){
                        $not_modified = $edit_same_client->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($not_modified as $value) {
                            if ($name === $value['name']) {
                                $no_modified_yet = true;
                            }
                        }
                    }
                    if ($no_modified_yet == false) {
                        $query_comprobe_clients = $con->prepare("SELECT name FROM clients WHERE name = '$name'");
                        if($query_comprobe_clients->execute()){
                            $exist = $query_comprobe_clients->fetchAll(PDO::FETCH_ASSOC);
                            if (count($exist) > 0) {
                                $no_exist_client = true;
                            }
                        }
                    }
                    if ($no_exist_client == false) {
                        if (isset($_POST["checkCredit"]) && $_POST["checkCredit"] == 0){
                            $credit_limit = $_POST['credit_limit'];
                            $credit_days = $_POST['credit_days'];
                            $credito = 'Aprobado';
                            if (is_null($phone)) {
                                $query = 'UPDATE clients SET name = "'.$name.'", email = "'.$email.'", approved_credit = "'.$credito.'", credit_limit = '.$credit_limit.', credit_days = '.$credit_days.', bank_reference = "'.$bank_reference.'" WHERE id = '.$id;
                            } else {
                                $query = 'UPDATE clients SET name = "'.$name.'", email = "'.$email.'", phone = '.$phone.', approved_credit = "'.$credito.'", credit_limit = '.$credit_limit.', credit_days = '.$credit_days.', bank_reference = "'.$bank_reference.'" WHERE id = '.$id;
                            }
                            // INSERT INTO clients VALUES(null,'margarita','example@example.com',15467984,0,200,2,1234567890)
                        } else{
                            if (is_null($phone)) {
                                $query = 'UPDATE clients SET name = "'.$name.'", email = "'.$email.'",bank_reference = "'.$bank_reference.'" WHERE id = '.$id;
                            } else {
                                $query = 'UPDATE clients SET name = "'.$name.'", email = "'.$email.'",phone = '.$phone.', bank_reference = "'.$bank_reference.'" WHERE id = '.$id;
                            }
                            // $query = 'INSERT INTO clients VALUES(null,'$name','$email','$phone',null,null,null,'$bank_reference')';
                        }
                        $query_prepare = $con->prepare($query);
                        if($query_prepare->execute()){
                            echo 'done';
                        }
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
        include 'conexion.php';
        $id="";
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $_GET['id'];
        }
        $query_prepare = $con->prepare("SELECT c.id, count(s.id_client) AS 'totalCompras', c.name, c.credit_limit FROM clients c INNER JOIN sales s ON s.id_client = $id WHERE c.id = $id");
        if($query_prepare->execute()){
            $row = $query_prepare->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($row);
        }        
    }
    public function filterClients(){
        include 'conexion.php';
        if ($_POST) {
            $filter_per = $_POST['filter'];
            $filter_type = $_POST['filter_per'];
            $errores = false;
            $results = false;

            if ($filter_per == 'approved_credit') {
                if ($filter_type == '0') {
                    $filter_type = 'Aprobado';
                } else if ($filter_type == '1') {
                    $filter_type = 'Rechazado';
                }
            }

            $query = "SELECT * FROM clients WHERE $filter_per LIKE '%$filter_type%'";
            var_dump($query);
            $query_clients_filter = $con->prepare($query);
            if($query_clients_filter->execute()){
                $clients_data = $query_clients_filter->fetchAll(PDO::FETCH_ASSOC);
                if (count($clients_data)) {
                    foreach ($clients_data as $index => $client) {
                        echo "<tr>";
                        echo "    <td>".($index + 1)."</td>";
                        echo "    <td>".$client['name']."</td>";
                        echo "    <td>".$client['email']."</td>";
                        echo "    <td>".$client['phone']."</td>";
                        echo "    <td>".$client['approved_credit']."</td>";
                        echo "    <td>".$client['credit_limit']."</td>";
                        echo "    <td>".$client['credit_days']."</td>";
                        echo "    <td>".$client['bank_reference']."</td>";
                        echo "    <td>";
                        echo "        <a href='?page=clients&action=newclient&parameter=".$client['id']."' class='btn btn-primary'><i class='fas fa-edit'></i></a>";
                        echo "        <a type='button' href='javascript:void(0)' data-bs-toggle='modal' data-bs-target='#deleteModalClients' data-element='".$client['id']."' class='deleteClientData btn btn-danger'><i class='fas fa-trash-alt'></i></a>";
                        echo "    </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr>";
                    echo "    <td colspan='9' id='noCoincidencias'>No se encontraron coincidencias</td>";
                    echo "</tr>";
                }
            } else {
                $errores = true;
            }

            if (!$errores) {
                if ($results) {
                    print_r('No se encontraron coincidencias');
                }
            } else {
                print_r('Algo salio mal');
            }
        }
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
                case 'newProvider':
                    include 'conexion.php'; //si la pongo por fuera, no funciona :(
                    $rfc = $_POST['rfc'] != '' ? $_POST['rfc'] : '';
                    $comercial_name = $_POST['comercial_name'] != '' ? $_POST['comercial_name'] : '';
                    $type = $_POST['type'] != '' ? $_POST['type'] : '';
                    $phone = $_POST['phone'] != '' ? $_POST['phone'] : null;
                    $street = $_POST['street'] != '' ? $_POST['street'] : '' ;
                    $suburb = $_POST['suburb'] != '' ? $_POST['suburb'] : '';
                    $number = $_POST['number'] != '' ? $_POST['number'] : '';
                    $postal_code = $_POST['postal_code'] != '' ? $_POST['postal_code'] : 00000;
                    $tag = $_POST['tag'] != '' ? $_POST['tag'] : '';

                    if (is_null($phone)) {
                        $query = 'INSERT INTO providers VALUES(null,"'.$rfc.'","'.$comercial_name.'","'.$type.'",null,"'.$street.'","'.$suburb.'","'.$number.'",'.$postal_code.',"'.$tag.'")';
                    } else {
                        $query = 'INSERT INTO providers VALUES(null,"'.$rfc.'","'.$comercial_name.'","'.$type.'",'.$phone.',"'.$street.'","'.$suburb.'","'.$number.'",'.$postal_code.',"'.$tag.'")';
                    }
                    var_dump($query);
                    $query_prepare = $con->prepare($query);
                    if($query_prepare->execute()){
                        echo 'done';
                    }
                    break;
                case 'editProvider':
                    $rfc = $_POST['rfc'] != '' ? $_POST['rfc'] : '';
                    $comercial_name = $_POST['comercial_name'] != '' ? $_POST['comercial_name'] : '';
                    $type = $_POST['type'] != '' ? $_POST['type'] : '';
                    $phone = $_POST['phone'] != '' ? $_POST['phone'] : null;
                    $street = $_POST['street'] != '' ? $_POST['street'] : '' ;
                    $suburb = $_POST['suburb'] != '' ? $_POST['suburb'] : '';
                    $number = $_POST['number'] != '' ? $_POST['number'] : '';
                    $postal_code = $_POST['postal_code'] != '' ? $_POST['postal_code'] : 00000;
                    $tag = $_POST['tag'] != '' ? $_POST['tag'] : '';

                    if (is_null($phone)) {
                        $query = "UPDATE providers SET RFC='$rfc',comercial_name='$comercial_name',type='$type',street='$street',suburb='$suburb',number='$number',postal_code=$postal_code,tag='$tag' WHERE id = $id";
                    } else {
                        $query = "UPDATE providers SET RFC='$rfc',comercial_name='$comercial_name',type='$type',phone=$phone, street='$street',suburb='$suburb',number='$number',postal_code=$postal_code,tag='$tag' WHERE id = $id";
                    }
                    // $query="INSERT INTO providers VALUES(null,'$rfc','$comercial_name','$type',$phone,'$street','$suburb',$number,$postal_code,'$tag')";
                    // $query="UPDATE providers SET RFC='$rfc',comercial_name='$comercial_name',type='$type',phone=$phone,street='$street',suburb='$suburb',number=$number,postal_code=$postal_code,tag='$tag' WHERE id = $id";
                    include 'conexion.php'; //si la pongo por fuera, no funciona :(
                    $query_prepare = $con->prepare($query);
                    if($query_prepare->execute()){
                        echo 'done';
                    }
                    break;
                case 'deleteProvider':
                    include 'conexion.php'; //si la pongo por fuera, no funciona :(
                    $query = "DELETE FROM providers WHERE id = $id";
                    var_dump($query);
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
        $query = "SELECT pr.id, count(p.id) AS 'totalProductsProvider', pr.comercial_name FROM providers pr INNER JOIN products p ON p.procedence_store = pr.comercial_name WHERE pr.id = $id";
        include 'conexion.php';
        $query_prepare = $con->prepare($query);
        if($query_prepare->execute()){
            $row = $query_prepare->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($row);
        }
    }
    public function filterProvider(){
        include 'conexion.php';
        if ($_POST) {
            $filter_per = $_POST['filter'];
            $filter_type = $_POST['filter_per'];
            $errores = false;
            $results = false;
        
            $query = "SELECT * FROM providers WHERE $filter_per LIKE '%$filter_type%'";
            var_dump($query);
            $query_providers_filter = $con->prepare($query);
            if($query_providers_filter->execute()){
                $providers_data = $query_providers_filter->fetchAll(PDO::FETCH_ASSOC);
                if (count($providers_data)) {
                    foreach ($providers_data as $index => $providers) {
                        $codigo_postal = $providers['postal_code'] != 0 ? $providers['postal_code']: '<span class="no_disponible">No disponible</span>';
                        echo "<tr>";
                        echo "    <td>".($index + 1)."</td>";
                        echo "    <td>".$providers['RFC']."</td>";
                        echo "    <td>".$providers['comercial_name']."</td>";
                        echo "    <td>".$providers['type']."</td>";
                        echo "    <td>".$providers['phone']."</td>";
                        echo "    <td>".$providers['street']."</td>";
                        echo "    <td>".$providers['suburb']."</td>";
                        echo "    <td>".$providers['number']."</td>";
                        echo "    <td>".$codigo_postal."</td>";
                        echo "    <td>".$providers['tag']."</td>";
                        echo "    <td style='width:150px;'>";
                        echo "        <a href='?page=providers&action=newProvider&parameter=".$providers['id']."' class='btn btn-primary'><i class='fas fa-edit'></i></a>";
                        echo "        <a type='button' data-bs-toggle='modal' data-bs-target='#deleteModalProvider' class='deleteProviderData btn btn-danger' data-element='".$providers['id']."'><i class='fas fa-trash-alt'></i></a>";
                        echo "    </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr>";
                    echo "    <td colspan='11' id='noCoincidencias'>No se encontraron coincidencias</td>";
                    echo "</tr>";
                }
            } else {
                $errores = true;
            }

            if (!$errores) {
                if ($results) {
                    print_r('No se encontraron coincidencias');
                }
            } else {
                print_r('Algo salio mal');
            }
        }
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
                'Precio' => (float)$value['store_price'],
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
            // Actaualiza el credito en el cliente en caso de que la venta sea a credito
            switch ($action) {
                case 'editSale':
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
                                $query = "UPDATE clients SET credit_limit = ".(float)$credit." WHERE id = ".$cliente;
                                $query_prepare = $con->prepare($query);
                                $query_prepare->execute();
                            }
                        }
                        $query_sale = $con->prepare("UPDATE sales SET id_client = ".$cliente.", pay_method = '".$status."', subtotal = ".(float)$subtotal.", total = ".(float)$total." WHERE id = ".$id_sale."");
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
                case 'deleteSale':
                    if (isset($_POST['id']) && !empty($_POST['id'])) {
                        $id = $_POST['id'];
                    }
                    include 'conexion.php'; //si la pongo por fuera, no funciona :(
                    $query_prepare = $con->prepare("DELETE FROM sales WHERE id = $id");
                    if($query_prepare->execute()){
                        $flag = true;
                    }
                    $query_products_sale = $con->prepare("DELETE FROM sales_products WHERE sale_id = $id");
                    if($query_products_sale->execute()){
                        $flag = true;
                    }

                    if ($flag) {
                        print_r('done');
                    }
                    break;
                default:
                    // echo 'Esta haciendo una nueva venta';
                    if ($_POST['tipoVenta'] == 'credito') {
                        $query = "UPDATE clients SET credit_limit = ".$_POST['credit']." WHERE id = ".$_POST['client'];
                        // var_dump($query);
                        $query_prepare = $con->prepare($query);
                        if($query_prepare->execute()){
                            $flag = true;
                        }
                    }

                    if ($_POST['abonoACredito'] > 0) {
                        $query_client_prepare = $con->prepare("SELECT credit_limit FROM clients WHERE id = ".$_POST['client']."");
                        $query_client_prepare->execute();
                        $client = $query_client_prepare->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($client as $value) {
                            $query = "UPDATE clients SET credit_limit = ".(float)$value['credit_limit'] + (float)$_POST['abonoACredito']." WHERE id = ".$_POST['client'];
                            // var_dump($query);
                            $query_prepare = $con->prepare($query);
                            if($query_prepare->execute()){
                                $flag = true;
                            }
                        }
                    }
                    // Inserta los datos de la venta en la tabla de la venta
                    $cantidad_cliente = 0;$query_create_debtor = '';
                    if ($_POST['payFromClient'] > 0) {
                        $cantidad_cliente = $_POST['payFromClient'];
                    } else if($_POST['payFromClient'] <= 0 && $_POST['status'] == 'Pendiente') {
                        $exist_debtro = $con->prepare("SELECT id_client, total_debt, abonos FROM debtors WHERE id_client = ".$_POST['client']);
                        if($exist_debtro->execute()){
                            $exist = $exist_debtro->fetchAll(PDO::FETCH_ASSOC);
                            if (count($exist) <= 0) { //no hay deudores con ese id de cliente, procede a crear un nuevo deudor
                                $query_debt_client = $con->prepare("SELECT sum(total) AS 'TotalDebtClient' FROM sales WHERE id_client = ".$_POST['client']." AND status = 'Pendiente'");
                                if ($query_debt_client->execute()) {
                                    $total_debt_client = $query_debt_client->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($total_debt_client as $value) {
                                        var_dump($value);
                                        $nueva_deuda = (float)$value['TotalDebtClient'] + (float)$_POST['total'];
                                        $query_create_debtor = "INSERT INTO debtors VALUES(NULL, ".$_POST['client'].", ".$nueva_deuda.",".$nueva_deuda.")";
                                        var_dump("INSERT INTO debtors VALUES(NULL, ".$_POST['client'].", ".$nueva_deuda.",".$nueva_deuda.")");
                                    }
                                }
                            } else {//ya existe el cliente en la tabla
                                foreach ($exist as $value) {
                                    $query_create_debtor = "UPDATE debtors SET total_debt = ".(float)$value['total_debt'] + (float)$_POST['total'].", restant_debt = ".(float)$value['total_debt'] + (float)$_POST['total'] - (float)$value['abonos']." WHERE id = ".$_POST['client'];
                                }
                            }
                        }
                    }
                    // var_dump($query_create_debtor);
                    $query_table_debtor = $con->prepare($query_create_debtor);
                    if ($query_table_debtor->execute()) {
                        $flag = true;
                    }

                    $query_sale = 'INSERT INTO sales VALUES(null,"'.$_POST['folio'].'","'.$_POST['tipoVenta'].'",'.$_POST['client'].','.(float)$_POST['subtotal'].','.(float)$_POST['total'].','.(float)$cantidad_cliente.',"'.$_POST['status'].'",CURRENT_TIME())';
                    // var_dump($query_sale);
                    $query_prepare_sale = $con->prepare($query_sale);
                    if($query_prepare_sale->execute()){
                        $flag = true;
                    }
                    
                    // $query_payments = $con->prepare("INSERT INTO payments VALUES(NULL, ".$_POST['client'].",'ventas',".(float)$_POST['total'].",null,CURRENT_TIME(),'".$_POST['tipoVenta']."')");
                    // if($query_payments->execute()){
                    //     $flag = true;
                    // }
                    // Para insertar los productos de la venta
                        // Traer el id de la venta con el folio que esta en proceso
                            $query_folio_sale = $con->prepare("SELECT id FROM sales WHERE folio = ".$_POST['folio']);
                            // var_dump($query_folio_sale);
                            $query_folio_sale->execute();
                            $folio_sale = $query_folio_sale->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($folio_sale as $folio) {
                                $id = $folio;
                            }
                            // var_dump($id);
        
                    $products = json_decode($_POST['products'],true); //el parametro true vuelve array al objeto json
                    // var_dump($products);
                    foreach ($products as $value) {
                        // var_dump("INSERT INTO sales_products VALUES(null,".$id['id'].",".$value['id'].",".$value['Cantidad'].")");
                        $query_product = $con->prepare("INSERT INTO sales_products VALUES(null,".$id['id'].",".$value['id'].",".$value['Cantidad'].")");
                        if($query_product->execute()){
                            $flag = true;
                        }
        
                        //actualizar cantidad en stock de cada producto
                        // var_dump("UPDATE products SET quantity = ".($value['Disponibles'] - $value['Cantidad'])." WHERE id = ".$value['id']."");
                        $query_product_prepare = $con->prepare("UPDATE products SET quantity = ".($value['Disponibles'] - $value['Cantidad'])." WHERE id = ".$value['id']."");
                        if($query_product_prepare->execute()){
                            $flag = true;
                        }
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
        
        $query_sale = $con->prepare("SELECT s.id, s.folio, s.pay_method, c.name AS 'cliente', s.subtotal, s.total, s.pay_from_client, s.status, s.fecha FROM sales s INNER JOIN clients c ON c.id = s.id_client");
        $query_sale->execute();
        $sales = $query_sale->fetchAll(PDO::FETCH_ASSOC);

        // Llenar array de folios y tr
        if (count($sales) > 0) {
            foreach ($sales as $index => $sale) {
                if (!in_array($sale['folio'], $folio_array)){
                    array_push($folio_array, $sale['folio']);
                    echo "<tr>";
                    echo "    <td>".($index + 1)."</td>";
                    echo "    <td>".$sale['folio']."</td>";
                    echo "    <td>".$sale['pay_method']."</td>";
                    echo "    <td>".$sale['cliente']."</td>";
                    echo "    <td>".(float)$sale['subtotal']."</td>";
                    echo "    <td>".(float)$sale['total']."</td>";
                    echo "    <td>".(float)$sale['pay_from_client']."</td>";
                    echo "    <td>".$sale['status']."</td>";
                    echo "    <td><a class='expand' data-sale='".$sale['id']."'>Ver Productos</a></td>";
                    echo "    <td>".$sale['fecha']."</td>";
                    echo "    <td>";
                    echo "      <a href='?page=sales&action=newSale&parameter=".$sale['id']."' class='btn btn-primary'><i class='fas fa-eye'></i></a>";
                    echo "      <a type='button' id='deleteSaleData' onclick='eliminarVenta(".$sale['id'].")' data-bs-toggle='modal' data-bs-target='#deleteModal' class='btn btn-danger'><i class='fas fa-trash-alt'></i></a>";
                    echo "    </td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "    <td colspan='11'>";
                    echo "        <div class='table-expandible' id='".$sale['id']."'>";
                    echo "            <table class='table table-hover'>";
                    echo "                <thead class='table-dark'>";
                    echo "                    <tr>";
                    echo "                        <th>Producto</th>";
                    echo "                        <th>Cantidad</th>";
                    echo "                    </tr>";
                    echo "                </thead>";
                    echo "                <tbody>";
                    foreach ($products_array as $value) {
                        if ($value['0'] == $sale['id']) {
                            echo "              <tr rowspan='11'>";
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
        } else {
            echo "<tr>";
            echo "    <td colspan='11' style='padding:0;margin:0'><div style='margin:0;' class='alert alert-primary col-md-12' role='alert'>Aún no cuentas con ventas</div></td>";
            echo "</tr>";
        }
    }
    public function getSaleToEdit($id){
        include 'conexion.php';
        $query_prepare_product = $con->prepare("SELECT sp.sale_id, sp.id_product as 'id', p.name as 'producto', p.store_price as 'precio', sp.quantity as 'cantidad', p.quantity as 'disponibles', (sp.quantity * p.store_price) as 'total' FROM sales_products sp INNER JOIN products p ON p.id = sp.id_product WHERE sp.sale_id = $id");
        $query_prepare_product->execute();
        $products = $query_prepare_product->fetchAll(PDO::FETCH_ASSOC);

        $array_productos = [];
        foreach ($products as $index => $row){
            array_push($array_productos, ['index'=>($index+1), 'sale_id'=>$row['sale_id'], 'id_product'=>$row['id'], 'nombre'=>$row['producto'], 'precio'=>(float)$row['precio'], 'cantidad'=>$row['cantidad'], 'disponibles'=>$row['disponibles'], 'total'=>(float)$row['total']]);
        }
        $query_prepare = $con->prepare("SELECT * FROM sales WHERE id = $id");
        $query_prepare->execute();
        $sale = $query_prepare->fetchAll(PDO::FETCH_ASSOC);

        foreach ($sale as $row) {
            return array($row, $array_productos); 
        }
    }
    public function getSaleToDelete(){
        include 'conexion.php';
        $id="";
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $_GET['id'];
        }
        $query = "SELECT id, folio FROM sales where id= $id";
        $query_prepare = $con->prepare($query);
        $query_prepare->execute();
        $row = $query_prepare->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($row);
        // var_dump($id);
        echo json_encode($row);
    }
    public function salesFilter(){
        include 'conexion.php';
        if ($_POST) {
            $filter_per = $_POST['filter'];
            $filter_type = $_POST['filter_per'];
            $errores = false;
            $results = false;
            $folio_array = [];
            $products_array = [];
            $query_prod = "SELECT sp.sale_id, p.name as 'producto', sp.quantity as 'cantidad' FROM sales_products sp INNER JOIN products p ON p.id = sp.id_product LEFT JOIN sales s ON s.id = sp.sale_id WHERE s.$filter_per like '%$filter_type%'";
            if ($filter_per === 'cliente') {
                $filter_per = 'name';
                $query_prod = "SELECT sp.sale_id, p.name as 'producto', sp.quantity as 'cantidad' FROM sales_products sp  INNER JOIN products p ON p.id = sp.id_product  LEFT JOIN sales s ON s.id = sp.sale_id  LEFT JOIN clients c ON c.id = s.id_client WHERE c.$filter_per like '%$filter_type%'";
                $filter_per = 'cliente';
            }
            $query_products = $con->prepare($query_prod);
            if($query_products->execute()){
                $sale_products = $query_products->fetchAll(PDO::FETCH_ASSOC);
                // Llenar array de productos
                foreach ($sale_products as $product) {
                    array_push($products_array, [$product['sale_id'], $product['producto'], $product['cantidad']]);
                }
            } else {
                $errores = true;
            }

            $query_prod = "SELECT s.id, s.folio, s.pay_method, c.name AS 'cliente', s.subtotal, s.total, s.pay_from_client, s.status, s.fecha FROM sales s INNER JOIN clients c ON c.id = s.id_client WHERE s.$filter_per LIKE '%$filter_type%'";
            if ($filter_per === 'cliente') {
                $filter_per = 'name';
                $query_prod = "SELECT s.id, s.folio, s.pay_method, c.name AS 'cliente', s.subtotal, s.total, s.pay_from_client, s.status, s.fecha FROM sales s INNER JOIN clients c ON c.id = s.id_client WHERE c.$filter_per LIKE '%$filter_type%'";
                $filter_per = 'cliente';
            }
            $query_sale = $con->prepare($query_prod);
            if($query_sale->execute()){
                $sales = $query_sale->fetchAll(PDO::FETCH_ASSOC);                    
                if (count($sales) > 0) {
                    // Llenar array de folios y tr
                    foreach ($sales as $index => $sale) {
                        if (!in_array($sale['folio'], $folio_array)){
                            array_push($folio_array, $sale['folio']);
                            echo "<tr>";
                            echo "    <td>".($index + 1)."</td>";
                            echo "    <td>".$sale['folio']."</td>";
                            echo "    <td>".$sale['pay_method']."</td>";
                            echo "    <td>".$sale['cliente']."</td>";
                            echo "    <td>".$sale['subtotal']."</td>";
                            echo "    <td>".$sale['total']."</td>";
                            echo "    <td>".$sale['pay_from_client']."</td>";
                            echo "    <td>".$sale['status']."</td>";
                            echo "    <td><a class='expand' data-sale='".$sale['id']."'>Ver Productos</a></td>";
                            echo "    <td>".$sale['fecha']."</td>";
                            echo "    <td>";
                            echo "      <a href='?page=sales&action=newSale&parameter=".$sale['id']."' class='btn btn-primary'><i class='fas fa-eye'></i></a>";
                            echo "      <a type='button' id='deleteSaleData' onclick='eliminarVenta(".$sale['id'].")' data-bs-toggle='modal' data-bs-target='#deleteModal' class='btn btn-danger'><i class='fas fa-trash-alt'></i></a>";
                            echo "    </td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "    <td colspan='11'>";
                            echo "        <div class='table-expandible' id='".$sale['id']."'>";
                            echo "            <table class='table table-hover'>";
                            echo "                <thead class='table-dark'>";
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
                } else {
                    echo "<tr>";
                    echo "    <td colspan='11' id='noCoincidencias'>No se encontraron coincidencias</td>";
                    echo "</tr>";
                }
            } else {
                $errores = true;
            }
        } else {
            $errores = true;
        }

        if (!$errores) {
            if ($results) {
                print_r('No se encontraron coincidencias');
            }
        } else {
            print_r('Algo salio mal');
        }
    }
    
}

class debtors {
    public function index(){
        require_once 'view/debtors.php';
    }
    public function showDebtors(){
        include 'conexion.php';
        $folio_array = [];
        $sales_array = [];
        
        $query_sales_debtors = $con->prepare("SELECT c.id, c.name, sum(s.total) as 'totalDeudaClient', d.restant_debt, d.abonos FROM sales s INNER JOIN clients c ON c.id = s.id_client RIGHT JOIN debtors d ON d.id_client = c.id where s.status = 'Pendiente' GROUP BY s.id_client");
        if($query_sales_debtors->execute()){
            $sales_debtors = $query_sales_debtors->fetchAll(PDO::FETCH_ASSOC);
            if (count($sales_debtors) > 0) {
                foreach ($sales_debtors as $index => $debtor) {
                    $sales_array = [];
                    $statusDeuda = '';

                    $query_sales_from_debtor = $con->prepare("SELECT id, folio, subtotal, total, fecha FROM sales WHERE id_client = ".$debtor['id']." AND status = 'Pendiente'");
                    if($query_sales_from_debtor->execute()){
                        $sales_debtors_table = $query_sales_from_debtor->fetchAll(PDO::FETCH_ASSOC);
                        // Llenar array de ventas pertenecientes a los deudores
                        foreach ($sales_debtors_table as $debtors) {
                            array_push($sales_array, [$debtors['id'], $debtors['folio'], $debtors['subtotal'], $debtors['total'], $debtors['fecha']]);
                        }
                    }
                
                    if ((float)$debtor['totalDeudaClient'] > 0) {
                        $statusDeuda = 'Pendiente';
                    } else {
                        $statusDeuda = 'Pagada';
                    }
                    echo "<tr>";
                    echo "    <td>".($index + 1)."</td>";
                    echo "    <td>".$debtor['name']."</td>";
                    echo "    <td>$".(float)$debtor['totalDeudaClient']."</td>";
                    echo "    <td>$".(float)$debtor['restant_debt']."</td>";
                    echo "    <td>$".(float)$debtor['abonos']."</td>";
                    echo "    <td>".$statusDeuda."</td>";
                    echo "    <td><a class='expand-sales' data-debtor='".$debtor['id']."'>Ver Ventas</a></td>";
                    echo "    <td>";
                    echo "      <a href='?page=payments&action=newPayment' class='btn btn-primary'>Abonar</a>";
                    echo "    </td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "    <td colspan='7'>";
                    echo "        <div class='table-expandible' id='".$debtor['id']."'>";
                    echo "            <table class='table table-hover'>";
                    echo "                <thead class='table-dark'>";
                    echo "                    <tr>";
                    echo "                        <th>No.</th>";
                    echo "                        <th>Folio</th>";
                    echo "                        <th>Subtotal</th>";
                    echo "                        <th>Total</th>";
                    echo "                        <th>Fecha</th>";
                    echo "                    </tr>";
                    echo "                </thead>";
                    echo "                <tbody>";
                    foreach ($sales_array as $index => $value) {
                        echo "                <tr rowspan='11'>";
                        echo "                    <td>".($index + 1)."</td>";
                        echo "                    <td><a href='?page=sales&action=newSale&parameter=".$value[0]."'>".$value[1]."</a></td>";
                        echo "                    <td>".$value[2]."</td>";
                        echo "                    <td>".$value[3]."</td>";
                        echo "                    <td>".$value[4]."</td>";
                        echo "                </tr>";
                    }
                    echo "                </tbody>";
                    echo "            </table>";
                    echo "        </div>";
                    echo "    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr>";
                echo "    <td colspan='9' style='padding:0;margin:0'><div style='margin:0;' class='alert alert-primary col-md-12' role='alert'>Aún no cuentas con deudores</div></td>";
                echo "</tr>";
            }
        }

        // Llenar array de folios y tr
    }
}

class payments {
    public function index(){
        $payments = $this->showPayments();
        require_once 'view/payments.php';
    }
    public function newPayment(){
        $clients = new clients();
        $payments_clients = $clients->getClients();

        require_once 'view/new-payment.php';
    }
    public function selectDataDebtors() {
        include 'conexion.php';
        $id = '';
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $id = $_POST['id'];
        }
        $query_debt = $con->prepare("SELECT total_debt, restant_debt, abonos from debtors where id = ".$id);
        if($query_debt->execute()){
            $row = $query_debt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($row);
        }
    }
    public function showPayments(){
        include 'conexion.php';
        $query = $con->prepare("SELECT c.name, p.total, p.abono, p.pay_method ,p.concepto, p.date FROM payments p INNER JOIN clients c ON c.id = p.id_client");
        if($query->execute()){
            $query_show = $query->fetchAll(PDO::FETCH_ASSOC);
            return $query_show;
        }
    }
    public function savePayment(){
        include 'conexion.php';
        if ($_POST) {
            var_dump($_POST);
            $flag = false; $flag_sale = false;
            $cliente_id = $_POST['client'];
            $concepto = $_POST['concepto'];
            $abono = !empty($_POST['abono']) ? $_POST['abono'] : 0;
            $deuda_actual = $_POST['deuda_actual'];
            $pay_method = $_POST['pay_method'];
            $restante = $_POST['restant'];
            $restante_updated = ((float)$restante - (float)$abono) <= 0 ? 0 : ((float)$restante - (float)$abono);

            var_dump("INSERT INTO payments VALUES(null, ".$cliente_id.",".$deuda_actual.",".$abono.",'".$pay_method."','".$concepto."', CURRENT_TIME())");
            $query_inset_payment = $con->prepare("INSERT INTO payments VALUES(null, ".$cliente_id.",".$deuda_actual.",".$abono.",'".$pay_method."','".$concepto."', CURRENT_TIME())");
            if($query_inset_payment->execute()){
                if ($restante - $abono <= 0) {
                    var_dump("SELECT abonos FROM debtors WHERE id_client = ".$cliente_id);
                    $total_abono_debtor = $con->prepare("SELECT abonos FROM debtors WHERE id_client = ".$cliente_id);
                    if($total_abono_debtor->execute()){
                        foreach ($total_abono_debtor as $value) {
                            var_dump("UPDATE debtors SET restant_debt = ".$restante_updated.", abonos = ".((float)$value['abonos'] + (float)$abono).", status = 'Pagada'  WHERE id_client = ".$cliente_id);
                            $query_update_dubters = $con->prepare("UPDATE debtors SET restant_debt = ".$restante_updated.", abonos = ".((float)$value['abonos'] + (float)$abono).", status = 'Pagada'  WHERE id_client = ".$cliente_id);
                            if($query_update_dubters->execute()) {
                                $flag = true;
                            }
                            var_dump("UPDATE sales SET status = 'Pagada' WHERE id_client = ".$cliente_id);
                            $query_update_sales = $con->prepare("UPDATE sales SET status = 'Pagada' WHERE id_client = ".$cliente_id);
                            if($query_update_sales->execute()){
                                $flag_sale = true;
                            }
                        }
                    }
                } else {
                    var_dump("SELECT abonos FROM debtors WHERE id_client = ".$cliente_id);
                    $total_abono_debtor = $con->prepare("SELECT abonos FROM debtors WHERE id_client = ".$cliente_id);
                    if($total_abono_debtor->execute()){
                        foreach ($total_abono_debtor as $value) {
                            var_dump("UPDATE debtors SET restant_debt = ".$restante_updated.", abonos = ".((float)$value['abonos'] + (float)$abono)." WHERE id_client = ".$cliente_id);
                            $query_update_dubters = $con->prepare("UPDATE debtors SET restant_debt = ".$restante_updated.", abonos = ".((float)$value['abonos'] + (float)$abono)." WHERE id_client = ".$cliente_id);
                            if($query_update_dubters->execute()) {
                                $flag = true;
                            }
                        }
                    }
                }
            }

            if (($flag && $flag_sale) || $flag) {
                print_r('done');
            }
        }
    }
    public function filterPayments(){
        include 'conexion.php';
        if ($_POST) {
            $filter_per = $_POST['filter'];
            $filter_type = $_POST['filter_per'];
            $errores = false;
            $results = false;

            $query = "SELECT c.name, p.total, p.abono, p.pay_method ,p.concepto, p.date FROM payments p INNER JOIN clients c ON c.id = p.id_client WHERE $filter_per LIKE '%$filter_type%'";
            $query_payments_filter = $con->prepare($query);
            if($query_payments_filter->execute()){
                $payments_data = $query_payments_filter->fetchAll(PDO::FETCH_ASSOC);
                if (count($payments_data)) {
                    foreach ($payments_data as $index => $payment) {
                        echo "<tr>";
                        echo "    <td>".($index + 1)."</td>";
                        echo "    <td>".$payment['name']."</td>";
                        echo "    <td>".$payment['total']."</td>";
                        echo "    <td>".$payment['abono']."</td>";
                        echo "    <td>".$payment['pay_method']."</td>";
                        echo "    <td>".$payment['concepto']."</td>";
                        echo "    <td>".$payment['date']."</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr>";
                    echo "    <td colspan='8' id='noCoincidencias'>No se encontraron coincidencias</td>";
                    echo "</tr>";
                }
            } else {
                $errores = true;
            }

            if (!$errores) {
                if ($results) {
                    print_r('No se encontraron coincidencias');
                }
            } else {
                print_r('Algo salio mal');
            }
        }
    }
}
?>