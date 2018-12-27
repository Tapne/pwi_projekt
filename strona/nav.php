<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <a class="navbar-brand" href="?view=home"><i class="fas fa-home"></i></a>

  <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
      <li class="nav-item active">
        <a class="nav-link" href="?view=home">Strona główna <span class="sr-only">(current)</span></a>
      </li>
        <li class="nav-item">
            <?php
                if(!log_in()) {
                    echo '<a class="nav-link" href="?view=register">Rejestracja</a>';
                } else {
                    echo '<a class="nav-link" href="?view=option_acc">Opcje konta</a>';
                }
            ?>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="?view=categories">Kategorie</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="?view=posts">Posty</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="?view=contact">Kontakt</a>
        </li>   
        <?php
        if(log_in()) {
            if(grade($_SESSION['login']) == 'Admin' || grade($_SESSION['login']) == 'Mod') {
                echo '<li class="nav-item"><a class="nav-link" href="?view=add_post">Dodaj post</a></li>';
            }
        }
        ?>
    </ul>
    <?php
      if(!log_in()) {
         echo '<form class="form-inline my-2 my-lg-0" method="post">
                <input class="form-control mr-sm-2" type="text" placeholder="Login" name="login_log" maxlength="10">
                <input class="form-control mr-sm-2" type="password" placeholder="Hasło" name="pass_log" maxlength="20">
                <input class="btn btn-outline-success my-2 my-sm-0" type="submit" value="Zaloguj" name="zaloguj_log">
            </form>'; 
      } else {
          echo '<form class="form-inline my-2 my-lg-0" method="POST">
                    <input class="btn btn-outline-danger my-2 my-sm-0" type="submit" value="Wyloguj" name="logout">
                </form>';
      }
    ?>
    
  </div>
</nav>

<?php
    if(isset($_POST['zaloguj_log'])) {
        if(!empty($_POST['pass_log']) && !empty($_POST['login_log'])) {
            include("connect.php");
            $zapytanie = $pdo->prepare("SELECT * FROM users where login = :login_log AND password = :password_log");
            $zapytanie->bindParam(':login_log', $_POST['login_log'], PDO::PARAM_STR, 10);
            $hash1 = $hash = hash('sha256', $_POST['pass_log']);
            $zapytanie->bindParam(':password_log', $hash1, PDO::PARAM_STR, 64);
            $zapytanie->execute();
            $wynik = $zapytanie->rowCount();
            if($wynik == 1) {
                $dane = $zapytanie->fetchAll();
                $_SESSION['login'] = $dane[0]['login'];
                $_SESSION['password'] = $dane[0]['password'];
                $_SESSION['email'] = $dane[0]['email'];
                header("Refresh:1");
            } else {
                echo '<div class="card text-white bg-danger"><div class="card-body text-center"><p class="card-text"><b>Nazwa użytkownika lub hasło są nieprawidłowe.</b></p></div></div>';
            }
        } else {
            echo '<div class="card text-white bg-danger"><div class="card-body text-center"><p class="card-text"><b>Wypełnij pola logowania!</b></p></div></div>';
        }
    }

    if(isset($_POST['logout'])) {
        session_destroy();
        header("Refresh:1");
    }
?>