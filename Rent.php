<?php
include "Data/Slaves.php";

echo "<a href='index.php'> Главная </a>";

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});


$id = $_GET['id'];

foreach ($slavesData as $slave) {

    echo (new Slave(...$slave))->renderOne($id);
}
