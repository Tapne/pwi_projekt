<main class="container-fluid">
    <div class="row justify-content-around">
        <div class="col-9 kat">
            <form class="logow" method="post">
                <div class="form-group">
                    <label for="login">Nazwa użytkownika</label>
                    <input type="text" class="form-control" id="login" placeholder="login" maxlength="10" name="login">
                    <small id="emailHelp" class="form-text text-muted">Maksymalnie 10 znaków</small>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Hasło</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="hasło" maxlength="20" name="pass">
                    <small id="emailHelp" class="form-text text-muted">Maksymalnie 20 znaków</small>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword2">Powtórz hasło</label>
                    <input type="password" class="form-control" id="exampleInputPassword2" placeholder="hasło" maxlength="20" name="pass2">
                    <small id="emailHelp" class="form-text text-muted">Maksymalnie 20 znaków</small>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">E-mail</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Adres e-mail" maxlength="30" name="email">
                    <small id="emailHelp" class="form-text text-muted">Maksymalnie 30 znaków</small>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-info" value="Zarejestruj" name="zarejestruj">
                </div>
                <?php
                    if(isset($_POST['zarejestruj'])) {
                        if($_POST['pass'] == $_POST['pass2'] && !empty($_POST['pass']) && !empty($_POST['pass2']) && !empty(['email']) && !empty($_POST['login'])) {
                            include("connect.php");
                            $result = $pdo->prepare("SELECT login, email FROM users WHERE login = :login OR email = :email");
                            $result->bindParam(':login', $_POST['login'], PDO::PARAM_STR, 10);
                            $result->bindParam(':email', $_POST['email'], PDO::PARAM_STR, 30);
                            $result->execute(); 
                            $number_of_rows = $result->rowCount();
                            if($number_of_rows < 1) {
                                $data = date("Y-m-d H:i:s");
                                $sth = $pdo->prepare("INSERT INTO users VALUES ('', :login, :password, :email, :data)");
                                $hash = hash('sha256', $_POST['pass']);
                                $sth->bindParam(':password', $hash, PDO::PARAM_STR, 64);
                                $sth->bindParam(':data', $data, PDO::PARAM_STR, 20);
                                $sth->bindParam(':login', $_POST['login'], PDO::PARAM_STR, 10);
                                $sth->bindParam(':email', $_POST['email'], PDO::PARAM_STR, 30);
                                $sth->execute();
                                $sth1 = $pdo->prepare("SELECT id FROM users where login = :login");
                                $sth1->bindParam(':login', $_POST['login'], PDO::PARAM_STR, 10);
                                $sth1->execute();
                                $sthtmp = $sth1->fetchAll();
                                $sth2 = $pdo->prepare("INSERT INTO ranks_in_users VALUES ('', :login_id, 1)");
                                $sth2->bindParam(':login_id', $sthtmp[0]['id'], PDO::PARAM_STR, 6);
                                $sth2->execute();
                                echo ' <div class="card text-white bg-success">
                        <div class="card-body">
                        <p class="card-text"><b>Zarejestrowano pomyślnie!</b></p></div></div>';
                            } else {
                               echo '<div class="card text-white bg-danger">
                            <div class="card-body">
                            <p class="card-text"><b>Taki użytkownik istnieje lub adres email istnieje!</b></p></div></div>';  
                            }
                            
                        } else {
                          echo '<div class="card text-white bg-danger">
                            <div class="card-body">
                            <p class="card-text"><b>Wypełnij wszystkie pola lub wpisz takie same hasła!</b></p></div></div>';  
                        }
                    }
                ?>
            </form>
        </div>
    </div>
</main>