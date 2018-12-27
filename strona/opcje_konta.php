<?php
    if(!isset($_SESSION['login'])) {
        session_start();
        include("fun.php");
    }
    if(!log_in()) {
        exit();
    }
?>
<main class="container-fluid">
    <div class="row justify-content-around">
        <div class="col-9 kat conf">
            <h1>Zmiana hasła</h1>
            <form class="logow" method="post">
                <div class="form-group">
                    <label for="exampleInputPassword1">Stare hasło</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="hasło" maxlength="20" name="old_pass">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword2">Nowe hasło</label>
                    <input type="password" class="form-control" id="exampleInputPassword2" placeholder="hasło" maxlength="20" name="new_pass">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword3">Powtórz hasło</label>
                    <input type="password" class="form-control" id="exampleInputPassword3" placeholder="hasło" maxlength="20" name="new_pass2">
                </div>
                <input type="submit" class="btn btn-warning" value="Zmień hasło" name="edit_pass">
                <?php
                    if(isset($_POST['edit_pass'])) {
                        if(!empty($_POST['old_pass']) && !empty($_POST['new_pass']) && !empty($_POST['new_pass2']) && $_POST['new_pass'] == $_POST['new_pass2']) {
                            $n_hash = hash('sha256', $_POST['new_pass']);
                            $o_hash = hash('sha256', $_POST['old_pass']);
                            include("connect.php");
                            $result = $pdo->prepare("SELECT password FROM users WHERE password = :o_pass AND login = :login");
                            $result->bindParam(':o_pass', $o_hash, PDO::PARAM_STR, 64);
                            $result->bindParam(':login', $_SESSION['login'], PDO::PARAM_STR, 10);
                            $result->execute(); 
                            $number_of_rows = $result->rowCount();
                            if($number_of_rows == 1) {
                                $result1 = $pdo->prepare("UPDATE users SET password=:pass WHERE login=:login") ;
                                $result1->bindParam(':pass', $n_hash, PDO::PARAM_STR, 64);
                                $result1->bindParam(':login', $_SESSION['login'], PDO::PARAM_STR, 64);
                                $result1->execute();
                                echo '<div class="card text-white bg-success"><div class="card-body"><p class="card-text"><b>Hasło zmienione pomyślnie.</b></p></div></div>';
                            } else {
                                echo '<div class="card text-white bg-danger"><div class="card-body"><p class="card-text"><b>Niepoprawne stare hasło.</b></p></div></div>';
                            }
                            
                        } else {
                            echo '<div class="card text-white bg-danger"><div class="card-body"><p class="card-text"><b>Wypełnij wszystkie pola lub wpisz takie same hasła!</b></p></div></div>';
                        }
                    }
                ?>
            </form>

        </div>
    </div>
</main>

