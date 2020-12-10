<?php
require 'getName.php';

$string = "Иванов Иван Иванович";
print_r( getPartsFromFullname($string));
echo getFullnameFromParts('Иванов', 'Иван', 'Иванович');

?>