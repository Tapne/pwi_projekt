<?php
    if(!isset($_SESSION['login'])) {
        session_start();
        include("fun.php");
    }
    if(!log_in()) {
        exit();
    } else {
        if(grade($_SESSION['login']) != 'Admin' && grade($_SESSION['login']) != 'Mod') {
            exit();
        }
    }
?>
<main class="container-fluid">
    <div class="row justify-content-around">
        <div class="col-9 kat">
            <!-- drugi rzad -->
            <div class="col post">
                <div class="card">
                    <form method="post">
                  <div class="card-body">
                      <?php
                        if(!isset($_GET['id'])) {
                            $_GET['id'] = 0;
                        }
                        include("connect.php");
                        $get_post = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
                        $get_post->bindParam(':id', $_GET['id'], PDO::PARAM_INT, 10);
                        $get_post->execute();
                        $get_post_w = $get_post->fetchAll();
                      
                      $login_user = $pdo->prepare("SELECT login FROM users where id = :id");
                        $login_user->bindParam(':id', $get_post_w[0]['login_user'], PDO::PARAM_STR, 10);
                        $login_user->execute();
                        $login_user_w = $login_user->fetchAll();
                      
                        if(grade($_SESSION['login']) != 'Admin') {
                            if($login_user_w[0]['login'] != $_SESSION['login']) {
                                exit();
                            }
                        }
                      
                        echo '<div class="card-title">
                            <div class="input-group">';
                        echo '<input type="text" name="title" class="form-control" value="'.$get_post_w[0]['title'].'"> </div></div>';
                        echo '<h6 class="card-subtitle mb-2 text-muted">'.$get_post_w[0]['date_add'].' | ~';
                        echo $login_user_w[0]['login'];
                          echo ' | [';
                        $cat_g = $pdo->prepare("SELECT name FROM categories WHERE id = :id");
                        $cat_g->bindParam(':id', $get_post_w[0]['cat_id'], PDO::PARAM_INT, 10);
                        $cat_g->execute();
                        $category_g = $cat_g->fetchAll();
                        echo $category_g[0]['name'];
                        if($get_post_w[0]['subcat'] != null) {
                            echo "/";
                            echo $get_post_w[0]['subcat'];
                        }
                        echo ']</h6><hr><div class="card-text"><div class="form-group">';
                        echo '<textarea class="form-control" rows="5" id="comment" name="value">'.$get_post_w[0]['value'].'</textarea>';
                        echo '</div>';
                      ?>                     
                    </div>
                      <div class="read_more">
                            <input type="submit" class="btn btn-warning" value="Edytuj" name="edit">
                            <input type="submit" class="btn btn-danger" value="Usuń" name="del">
                      </div>
                    </form>
                    <?php
                        if(isset($_POST['del'])) {
                           $delete1 = $pdo->prepare("DELETE FROM posts WHERE id = :id");
                           $delete1->bindParam(':id', $_GET['id'], PDO::PARAM_STR, 10);
                            $delete1->execute();
                            echo '<div class="card text-white bg-success">
                                <div class="card-body">
                                <p class="card-text"><b>Post usunięty!</b></p></div></div>';
                        }
                        if(isset($_POST['edit'])) {
                            $edit1 = $pdo->prepare("UPDATE posts SET title = :title, value = :value WHERE id = :id");
                            $edit1->bindParam(':title', $_POST['title'], PDO::PARAM_STR,15);
                            $edit1->bindParam(':value', $_POST['value'], PDO::PARAM_STR, 5000);
                            $edit1->bindParam(':id', $_GET['id'], PDO::PARAM_STR, 10);
                            $edit1->execute();
                            echo '<div class="card text-white bg-success">
                                <div class="card-body">
                                <p class="card-text"><b>Post zaktualizowany!</b></p></div></div>';
                        }
                    
                    ?>
                  </div>
                </div>
            </div>         
        </div>
        <div class="col-3 kat">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Kategorie</h5>
                            <div class="card-text">
                                <ul>
                                    <?php
                                        include("connect.php");
                                        $query = $pdo->prepare("SELECT * FROM categories");
                                        $query->execute();
                                        $result = $query -> fetchAll();
                                        foreach($result as $row) {
                                            if($row['name'] != 'USUNIETE') {
                                                echo '<li><i class="fas fa-angle-double-right"></i>';
                                                echo $row['name'];
                                                if($row['subcategories'] != NULL) {
                                                        echo '<ul>';
                                                            foreach(explode(',', $row['subcategories']) as $subcat) {
                                                                echo '<li><i class="fas fa-angle-right"></i>';
                                                                echo $subcat;
                                                                echo '</li>';
                                                            }
                                                        echo '</ul>';
                                                }
                                                echo '</li>';
                                            }
                                        }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</main>
