<?php
// 1. Разбиение и объединение ФИО
function getFullnameFromParts($surname, $name, $patronomyc){

    return $surname . ' ' . $name . ' ' .  $patronomyc;
}



function getPartsFromFullname($str_name)  {
    $value_name = explode(' ', $str_name);
    $new_arr = array_combine(['surname','name', 'patronomyc'], $value_name);
    return $new_arr;
};

//2.Сокращение ФИО
function getShortName ($str_name) {
    $tmp = getPartsFromFullname($str_name);
    return $tmp['name'] . ' ' . mb_substr($tmp['surname'], 0, 1) . '.';
}

//3.Функция определения пола по ФИО
function getGenderFromName ($str_name) {
    $gender = 0;
    $value_name = getPartsFromFullname($str_name);
//////////   patronomyc
    $tmp = mb_substr($value_name['patronomyc'], -3);
	$tmp = ($tmp == 'вна') ? $tmp : mb_substr($tmp, -2);
	
	
    switch($tmp){
        case 'вна': 
            $gender--;
            break;
        case 'ич': 
            $gender++;
            break;   
    }

//////////   name
    $tmp = mb_substr($value_name['name'], -1);
    switch($tmp){
        case 'а': 
        case 'н': 
            $gender--;
            break;
        case 'й': 
            $gender++;
            break;
    } 

 //////////   surname
    $tmp = mb_substr($value_name['surname'], -2);
    $tmp = ($tmp == 'ва') ? $tmp : mb_substr($tmp, -1);
	
    switch($tmp){
        case 'ва': 
            $gender--;
            break;
        case 'в': 
            $gender++;
            break;   
    }
    
    return  $gender <=> 0;
}

//4. Определение возрастно-полового состава
function getGenderDescription ($person_list) {

    $getGender = function($value) {
        return getGenderFromName($value['fullname']);
    };
    
    $gender = array_map($getGender, $person_list);

    $cnt = count($gender);
    
    
    $men = array_filter($gender, function($elem_m) {
        return $elem_m == 1;
    });
    
    $women = array_filter($gender, function($elem_v) {
        return $elem_v == -1;
    });


    $other = $cnt - count($men) - count($women);
    
    $men = round(count ($men) / $cnt * 100, 1);
    $women = round(count($women) / $cnt * 100, 1);
    $other = round($other / $cnt * 100, 1);
    
    $res = <<<HEREDOCLETTER
Гендерный состав аудитории:
-----------------
Мужчины - $men%
Женщины - $women%
Не удалось определить - $other%
HEREDOCLETTER;
    
    return  $res;
}


function  getPerfectPartner ($surname, $name, $patronomyc, $example_persons_array) {
    $fio = mb_convert_case(getFullnameFromParts($surname, $name, $patronomyc), MB_CASE_TITLE_SIMPLE);
    $gender = getGenderFromName($fio);
  
do {
    $rand_num = mt_rand(0, count($example_persons_array) - 1);
    $new_person = $example_persons_array[$rand_num]['fullname'];
    $new_person_gender = getGenderFromName($new_person); 
    
}   while ($gender + $new_person_gender != 0);

    $value_num = number_format(rand(5000, 10000) / 100, 2);
    
    
    $short1 = getShortName($fio);
    $short2 = getShortName($new_person);

    $res = <<<HEREDOCLETTER
$short1 + $short2 = 
♡ Идеально на $value_num% ♡
HEREDOCLETTER;
    return  $res;
}


?>
