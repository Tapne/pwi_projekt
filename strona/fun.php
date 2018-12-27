<?php
//sprawdzanie czy ktoś zalogowany
function log_in() {
    if(!empty($_SESSION['login']) && !empty($_SESSION['password']) && !empty($_SESSION['email'])) {
        return true;
    } else {
        return false;
    }
}

//sprawdzanie administracji i moderacji
function grade($login) {
    include("connect.php");
    $zapytanie1 = $pdo->prepare("SELECT a.login, a.id, b.login_user, b.id_rank, c.name FROM users a, ranks_in_users b, ranks c where a.id = b.login_user AND login = :login_grade AND c.id = b.id_rank");
    $zapytanie1->bindParam(':login_grade', $login, PDO::PARAM_STR, 10);
    $zapytanie1->execute();
    $wynik0 = $zapytanie1->fetchAll();
    if($wynik0[0]['name'] == 'Admin') {
        return 'Admin';
    } elseif($wynik0[0]['name'] == 'Mod') {
        return 'Mod';
    } else {
        return false;
    }
}



?>