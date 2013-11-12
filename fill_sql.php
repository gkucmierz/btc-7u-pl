<?php

set_time_limit(0);

include('db_conf.php');

$chars = 'qwertyuiopasdfghjklzxcvbnm' . '0123456789' . 'QWERTYUIOPASDFGHJKLZXCVBNM';
$chars .= '!@#$%^&*()_+=-';
$chars .= '`~[]{}\\|;:\'",.<>/?';
$chars .= "\t ";
$chars .= '¡¢£¤¥¦§¨©ª«¬­®¯°±²³´µ¶·¸¹º»¼½¾¿ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõö÷øùúûüýþÿ';


$len = strlen($chars);
$passLen = 3;


for ($i = 0; $i < $len; ++$i) {
    // $numI = $i * $len;
    for ($j = 0; $j < $len; ++$j) {
        // $numJ = ($numI + $j) * $len;
        for ($k = 0; $k < $len; ++$k) {
            // $numK = $numJ + $k;
            $pass = $chars[$i] . $chars[$j] . $chars[$k];

            $sql = 'INSERT INTO passes (pass) VALUES (:pass)';
            $q = $db->prepare($sql);
            $q->execute(array(
                // ':id' => $numK,
                ':pass' => $pass
            ));

            // echo $numK . "\t" . $pass . "\n";
        }
    }
}

echo 'done' + "\n";
echo pow(strlen($chars), 3);