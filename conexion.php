<?php
/* El PDO es diferente, pero tengo que dominarlo porque es lo nuevo */
try {
    $con = new PDO('mysql:host=localhost;dbname=practica_lau', 'root', '');
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>