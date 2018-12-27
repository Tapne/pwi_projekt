<main class="container-fluid">
    <div class="row justify-content-around">
        <div class="col-10 kat">
            <!-- post -->
            <?php
                if(!isset($_GET['id'])) {
                    header("Location: index.php");
                }
                include("connect.php");
                $get_post = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
                $get_post->bindParam(':id', $_GET['id'], PDO::PARAM_INT, 10);
                $get_post->execute();
                $val_post = $get_post->fetchAll();
                echo '<article class="col post">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">'.$val_post[0]['title'].'</h5>
                    <h6 class="card-subtitle mb-2 text-muted">'.$val_post[0]['date_add'].' | ~';
                $user_id = $pdo->prepare("SELECT login FROM users where id = :id");
                $user_id->bindParam(':id', $val_post[0]['login_user'], PDO::PARAM_STR, 10);
                $user_id->execute();
                $login_user = $user_id->fetchAll();
                echo $login_user[0]['login'].' | [';
                $cat_g = $pdo->prepare("SELECT name FROM categories WHERE id = :id");
                $cat_g->bindParam(':id', $val_post[0]['cat_id'], PDO::PARAM_STR, 10);
                $cat_g->execute();
                $category_g = $cat_g->fetchAll();
                echo $category_g[0]['name'];
                if($val_post[0]['subcat'] != null) {
                    echo '/'.$val_post[0]['subcat'];
                }
                echo ']</h6>
                      <hr>
                    <p class="card-text">'.$val_post[0]['value'].'</p>
                  </div>
                </div>
            </article>';
            
            ?>
            <article class="col post">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-comments"></i> Komentarze</h5>
                    
                        <?php
                            ob_start();
                            $comments = $pdo->prepare("SELECT * FROM comments WHERE post_id = :id");
                            $comments->bindParam(':id', $_GET['id'], PDO::PARAM_INT, 10);
                            $comments->execute();
                            $c_comments = $comments->rowCount();
                            $all_comments = $comments->fetchAll();
                            echo '<h6 class="card-subtitle mb-2 text-muted"> Łącznie '.$c_comments.' komentarzy</h6>';
                            echo '<div class="card-text">';
                            foreach($all_comments as $comment) {
                                $id=$comment['login_user'];
                                $get_login = $pdo->query("SELECT login FROM users WHERE id =$id");
                                $get_login_w = $get_login->fetchAll();
                                echo '<hr>
                                    <div class="row">
                                        <div class="col-2">
                                            <b>'.$get_login_w[0]['login'].'</b> napisał<br><small class="card-subtitle mb-2 text-muted">'.$comment['date_add'].'</small>
                                        </div>
                                        <div class="col-10">'.$comment['value'].'</div>
                                    </div>';
                            }
                            
                            if(log_in()) {
                                echo '<hr>
                                <div class="row">
                                    <div class="col">
                                        <form method="POST">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="Treść komentarza" aria-describedby="basic-addon2" name="val_comm">
                                                <div class="input-group-append">
                                                    <input class="btn btn-outline-secondary" type="submit" value="Skomentuj" name="add_comm">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>';
                                if(isset($_POST['add_comm'])) {
                                    if(!empty($_POST['val_comm'])) {
                                        $get_id_user = $pdo->prepare("SELECT id FROM users WHERE login = :login");
                                        $get_id_user->bindParam(':login', $_SESSION['login'], PDO::PARAM_STR, 10);
                                        $get_id_user->execute();
                                        $get_id_user_w = $get_id_user->fetchAll();
                                        
                                        $add_post = $pdo->prepare("INSERT INTO comments VALUES ('', :post_id, :user_id, :val, :date)");
                                        $add_post->bindParam(':post_id', $_GET['id'], PDO::PARAM_INT, 10);
                                        $add_post->bindParam(':user_id', $get_id_user_w[0]['id'], PDO::PARAM_INT, 10);
                                        $add_post->bindParam(':val', $_POST['val_comm'], PDO::PARAM_STR, 100);
                                        $today = date("Y-m-d H:i:s"); 
                                        $add_post->bindParam(':date', $today, PDO::PARAM_STR, 20);
                                        $add_post->execute();
                                        header("Refresh:0");
                                        ob_end_flush();
                                        
                                    } else {
                                        echo '<div class="card text-white bg-danger">
                                <div class="card-body">
                                <p class="card-text"><b>Uzupełnij treść komentarza!</b></p></div></div>';
                                    }
                                }
                            }
                        echo '</div>';
                        ?>
                  </div>
                </div>
            </article>
            
            
        </div>
    </div>
</main>
