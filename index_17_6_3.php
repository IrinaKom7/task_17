<?php
require 'getName.php';

$str1 = "Шматко Антонина Сергеевна";
$str2 = "Шварцнегер Арнольд Густавович";

echo $str1 . getGenderFromName($str1) . PHP_EOL ;
echo $str2 . getGenderFromName($str2);

?>