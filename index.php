<?php
    session_start();
    include("strona/fun.php");
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php
       include("strona/font&bt#head.html");   
    ?>
    <link rel="stylesheet" type="text/css" href="style.css" />

    <title>
        <?php
        if(empty($_GET['view'])) {
            $title = 'home';
        } else {
            $title = $_GET['view'];
        }
            switch($title) {
            case 'home':
                echo 'Strona główna';
                break;
            case 'register':
                echo 'Rejestracja';
                break;
            case 'categories':
                echo 'Kategorie';
                break;
            case 'posts':
                echo 'Posty';
                break;
            case 'contact':
                echo 'Kontakt';
                break;
            case 'option_acc':
                echo 'Opcje konta';
                break;
            case 'post':
                echo 'Artykuł';
                break;
            case 'add_cat':
                echo 'Dodaj kategorię';
                break;
            case 'add_post':
                echo 'Dodaj post';
                break;
            case 'edit_post':
                echo 'Edycja posta';
                break;
            default:
                echo 'Strona główna';
                break;
            }
        ?>
    </title>
  </head>
  <body>
    <?php
        include("strona/nav.php");
    ?>
    <header class="jumbotron jumbotron-fluid">
      <div class="container">
        <h1 class="display-4">Tekst motywacyjny</h1>
        <p class="lead">Komentarz motywacyjny do tekstu wyżej</p>
      </div>
    </header>
    <?php
        if(empty($_GET['view'])) {
            $www = 'home';
        } else {
            $www = $_GET['view'];
        }
        switch($www) {
            case 'home':
               include("strona/indeks.php");
                break;
            case 'register':
                include("strona/rejestracja.php");
                break;
            case 'categories':
                include("strona/kategorie.php");
                break;
            case 'posts':
                include("strona/posty.php");
                break;
            case 'contact':
                include("strona/kontakt.html");
                break;
            case 'post':
                include("strona/post.php");
                break;
            case 'edit_post':
                include("strona/edycja_posta.php");
                break;
            case 'add_cat':
                include("strona/dodaj_kategorie.php");
                break;
            case 'option_acc':
                if(log_in()) {
                    include("strona/opcje_konta.php");
                } else {
                    include("strona/indeks.php");
                }
                break;
            case 'add_post':
                if(log_in()) {
                    if(grade($_SESSION['login']) == 'Admin' || grade($_SESSION['login']) == 'Mod') {
                        include("strona/add_post.php");
                    } else {
                        include("strona/indeks.php");
                    }
                } else {
                    include("strona/indeks.php");
                }
                break;
            default:
                include("strona/indeks.php");
                break;
        }
        
        include("strona/footer.html");
        include("strona/btsc.html");
    ?> 
  </body>
</html>