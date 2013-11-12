<?php

// file_put_contents('output', rand(1,10));

error_reporting(0);
// $_POST['data'] = base64_encode('asd;0;asd;');
if (!isset($_POST['data'])) {
    // echo "blad: podaj dane";
    exit;
}

file_put_contents('output', base64_decode($_POST['data']));

$lines = explode("\n", base64_decode($_POST['data']));
$_POST = array();

chdir('../');

$endRes = '';

foreach ($lines as $line) {
    $cells = explode(';', $line);
    $author = base64_decode($cells[0]);
    $res = intval($cells[1]);
    $pass = base64_decode($cells[2]);
    if ($res) {
        $priv = $cells[3];
    }

    if (strlen($pass) !== 3){
        continue;
    }

    $_POST = array(
        'result' => array(
            'author' => $author,
            'pass' => $pass,
            'res' => $res
        ),
        'queue' => 100
    );

    // file_put_contents('output', print_r($_POST, true));

    if ($res) {
        $_POST['result']['priv'] = $priv;
    }

    // print_r($_POST);

    ob_start();
    include('post.php');
    $json = ob_get_clean();
    $res = json_decode($json, JSON_FORCE_OBJECT);
    $endRes = $res['work']['left'] . "\n" . $res['work']['max'];

}

echo $endRes;

file_put_contents('output', $endRes);

// send_result.php

// base64(autor);0;base64(pass);
// base64(autor);1;base64(pass);privkey


// czyli np.:

// b32hbrh223r=;0;fnj2n3=;
// b32hbrh223r=;0;fnj2n3=;
// b32hbrh223r=;0;fnj2n3=;
// b32hbrh223r=;0;fnj2n3=;
// b32hbrh223r=;1;fnj2n3=;dsnkjfnksjdnfkjsdf