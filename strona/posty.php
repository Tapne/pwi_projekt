<main class="container-fluid">
    <div class="row justify-content-around">
        <div class="col-12 kat">
            <article class="col post">
                <form method="post">
                    <div class="input-group">

                            <select class="form-control" id="inputGroupSelect01" name="categ">
                            <?php
                                include("connect.php");
                                $query = $pdo->prepare("SELECT * FROM categories");
                                $query->execute();
                                $result = $query -> fetchAll();
                                foreach($result as $row) {
                                    if($row['name'] != 'USUNIETE') {
                                        echo '<option value="';
                                        echo $row['id'];
                                        echo '">';
                                        echo $row['name'];
                                        echo '</option>';
                                        if($row['subcategories'] != NULL) {
                                            foreach(explode(',', $row['subcategories']) as $subcat) {
                                                echo '<option value="';
                                                echo $row['id'];
                                                echo ',';
                                                echo $subcat;
                                                echo '">---';
                                                echo $subcat;
                                                echo '</option>';
                                            }
                                        }
                                    }
                                }
                            ?>
                            </select>
                            <input type="submit" class="btn btn-primary" value="Pokaż" name="show">
                        </div>
                    </form>
                
            </article>
            <!-- post -->
            <?php
            include("connect.php");
            if(!isset($_GET['page'])) {
                $_GET['page'] = 1;
            }
            if(!isset($_GET['category'])) {
                $_GET['category'] = '';
            }
            $offset = $_GET['page']*10-10;
            $limit = 10;
            if(isset($_POST['show'])) {
                $tmp = $_POST['categ'];
                $tmp1 = $_GET['view'];
                header("Location: ?view=$tmp1&category=$tmp");
                exit;
            }
            if(strpos($_GET['category'], ",") == true) {
                $category = explode(",", $_GET['category']);
                $posts = $pdo->prepare("SELECT * FROM posts WHERE cat_id = :catid AND subcat = :subcat ORDER BY id DESC LIMIT :limit OFFSET :offset");
                $posts->bindParam(':catid', $category[0], PDO::PARAM_INT, 10);
                $posts->bindParam(':subcat', $category[1], PDO::PARAM_STR, 20);
            } else {
                $posts = $pdo->prepare("SELECT * FROM posts WHERE cat_id = :catid ORDER BY id DESC LIMIT :limit OFFSET :offset");
                $posts->bindParam(':catid', $_GET['category'], PDO::PARAM_INT, 10);
            }
            $posts->bindParam(':limit', $limit, PDO::PARAM_INT, 20);
            $posts->bindParam(':offset', $offset, PDO::PARAM_INT, 20);
            $posts->execute();
            $wynik = $posts->fetchAll();
            foreach($wynik as $post) {
                echo '<article class="col post">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">';
                echo $post['title'];
                echo '</h5><form method="POST">
                    <h6 class="card-subtitle mb-2 text-muted">';
                echo $post['date_add'];
                echo ' | ~';
                $user_id = $pdo->prepare("SELECT login FROM users where id = :id");
                $user_id->bindParam(':id', $post['login_user'], PDO::PARAM_STR, 10);
                $user_id->execute();
                $login_user = $user_id->fetchAll();
                echo $login_user[0]['login'];
                if(log_in()==true) {
                    if(grade($_SESSION['login']) == 'Admin' && $login_user[0]['login'] != "BANNED" && $login_user[0]['login'] != $_SESSION['login']) {
                        echo ' <input type="submit" class="btn btn-danger btn-sm" value="Usuń" name="'.$post['login_user'].'">';
                        $tmp = $post['login_user'];
                        if(isset($_POST["$tmp"])) {
                            $shuffled = hash('sha256', str_shuffle("QWERTYUIOPASDFGHJKLZXCVBNM"));
                            $delete1 = $pdo->prepare("UPDATE users set login = 'BANNED', password = :pass WHERE id = :id");
                            $delete1->bindParam(':id', $post['login_user'], PDO::PARAM_STR, 10);
                            $delete1->bindParam(':pass', $shuffled, PDO::PARAM_STR, 64);
                            $delete1->execute();
                        }
                    }
                }
                echo ' | [';
                $cat_g = $pdo->prepare("SELECT name FROM categories WHERE id = :id");
                $cat_g->bindParam(':id', $post['cat_id'], PDO::PARAM_STR, 10);
                $cat_g->execute();
                $category_g = $cat_g->fetchAll();
                echo $category_g[0]['name'];
                if($post['subcat'] != null) {
                    echo "/";
                    echo $post['subcat'];
                }
                echo ']</h6></form>';
                if(log_in()==true) {
                    if(grade($_SESSION['login']) == 'Admin') {
                        echo '<a class="btn btn-info btn-sm" href="?view=edit_post&id='.$post['id'].'" role="button">Edytuj</a>';
                    } elseif($login_user[0]['login'] == $_SESSION['login']) {
                        echo '<a class="btn btn-info btn-sm" href="?view=edit_post&id='.$post['id'].'" role="button">Edytuj</a>';
                    }
                }
                echo'<hr>
                    <p class="card-text">';
                echo substr($post['value'], 0, 200);
                echo '... - kontynuacja w poście </p>
                      <div class="read_more">
                          <a href="?view=post&id=';
                    echo $post['id'];
                    echo '" class="card-link">Czytaj więcej</a>
                      </div>
                  </div>
                </div>
            </article>';
            }
            
            ?>
            
        </div>
        <nav aria-label="Page navigation example">
          <ul class="pagination">
            <?php
                if(!isset($_GET['page'])) {
                $_GET['page'] = 1;
                }
                if(!empty($_GET['category'])) {
                    include("connect.php");
                    if(strpos($_GET['category'], ",") == true) {
                        $category = explode(",", $_GET['category']);
                        $stron = $pdo->prepare("SELECT * FROM posts WHERE cat_id = :catid AND subcat = :subcat");
                        $stron->bindParam(':catid', $category[0], PDO::PARAM_INT, 10);
                        $stron->bindParam(':subcat', $category[1], PDO::PARAM_STR, 20);
                    } else  {
                        $stron = $pdo->prepare("SELECT * FROM posts WHERE cat_id = :catid ");
                        $stron->bindParam(':catid', $_GET['category'], PDO::PARAM_INT, 10);
                    }
                    $stron->execute();
                    $strony = $stron->rowCount();
                    $stronki = round($strony/10, 0);
                    if($_GET['page'] >= 2) {
                        $tmp = $_GET['page']-1;
                        $tmpp = $_GET['category'];
                        echo '<li class="page-item"><a class="page-link" href="?view=posts&category='.$tmpp.'&page='.$tmp.'">Poprzednia</a></li>';
                    }
                    for($i = 1; $i <= $stronki; $i++) {
                        $tmpp = $_GET['category'];
                        echo '<li class="page-item"><a class="page-link" href="?view=posts&category='.$tmpp.'&page='.$i.'">'.$i.'</a></li>';
                    }
                    if($_GET['page'] < $stronki) {
                        $tmp = $_GET['page']+1;
                        $tmpp = $_GET['category'];
                        echo '<li class="page-item"><a class="page-link" href="?view=posts&category='.$tmpp.'&page='.$tmp.'">Następna</a></li>';
                    }
                }
            ?>
          </ul>
        </nav>
    </div>
</main>