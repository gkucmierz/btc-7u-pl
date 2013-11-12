<?php

// http://wklej.to/VZBk2

error_reporting(0);
include('../db_conf.php');

// $_POST['result'] = array(
//     'pass' => '000',
//     'res' => 0
// );

// save result
if (isset($_POST['result'])) {
    saveResult($db, $_POST['result']);
}

$max_work = pow(96, 3);
$left_work = countLeftWork($db);


// make resp obj
$resp = array(
    'work' => array(
        'left' => $left_work,
        'max' => $max_work
    )
);
if (!isset($_POST['queue']) || $_POST['queue'] <= 1) {
    // else add passes to $res

    $work = array();
    foreach (getRandomWork($db, mt_rand(10, 15)) as $row) {
        $work[] = $row['pass'];
    }

    $resp['concat'] = $work;
}

if (isset($_POST['get_privkeys']) && $_POST['get_privkeys'] === 'e85cuq5zdm3lcp47') {

    $privkeys = array();
    foreach (getPrivkeys($db) as $row) {
        $privkeys[] = $row['priv'];
    }
    $resp['privkeys'] = $privkeys;
}

echo json_encode($resp);


//------------------------------------------------------------//

function getPrivkeys($db) {
    $sql = 'SELECT `priv` FROM `authors`';
    $q = $db->prepare($sql);
    $q->execute();
    return $q->fetchAll(PDO::FETCH_ASSOC);
}

function getRandomWork($db, $num) {
    $left_work = countLeftWork($db);
    $sql = 'SELECT `pass` FROM `passes` WHERE `success` = 0 LIMIT :start, :num';
    $q = $db->prepare($sql);
    $q->bindValue(':start', mt_rand(0, max($left_work-$num, 0)), PDO::PARAM_INT);
    $q->bindValue(':num', (int) $num, PDO::PARAM_INT);
    $q->execute();
    return $q->fetchAll(PDO::FETCH_ASSOC);
}

function countLeftWork($db) {
    $sql = 'SELECT success, COUNT(success) count FROM passes WHERE success = 0';
    $q = $db->prepare($sql);
    $q->execute();
    $res = $q->fetch();
    return (int) $res['count'];
}

function saveResult($db, $result) {
    $sql = 'UPDATE `passes` SET `success`=`success`+:diff WHERE `pass` = :pass';
    $q = $db->prepare($sql);
    $q->execute(array(
        ':diff' => ($result['res'] ? +1 : -1),
        ':pass' => $result['pass']
    ));


    addWorkerOrIncrement($db, $result['author']);

    if ($result['res']) {
        saveAuthor($db, $result);
    }
}

function addWorkerOrIncrement($db, $author) {
    $sql = 'INSERT INTO workers (author, passes) VALUES (:author, 1) ON DUPLICATE KEY UPDATE passes = passes + 1';
    $q = $db->prepare($sql);
    $q->execute(array(
        ':author' => $author
    ));
}

function saveAuthor($db, $result) {
    $sql = 'INSERT INTO `authors`(`author`, `pass`, `priv`) VALUES (:author, :pass, :priv)';
    $q = $db->prepare($sql);
    $q->execute(array(
        ':author' => $result['author'],
        ':pass' => $result['pass'],
        ':priv' => $result['priv']
    ));

    mail('gkucmierz@gmail.com', 'btc', 'pass: '.$result['pass']."\n".'author: '.$result['author']."\n".'priv: '.$result['priv']);
}

// Array
// (
//     [result] => Array
//         (
//             [author] => 
//             [pass] => pass443
//             [res] => 0
//         )

//     [queue] => 2
// )


// $chars = array(
//     'qwertyuiopasdfghjklzxcvbnm',
//     '0123456789',
//     'QWERTYUIOPASDFGHJKLZXCVBNM'
// );
// $sets = array(
//     $chars[0],
//     $chars[0] . $chars[1],
//     $chars[0] . $chars[1] . $chars[2]
// );