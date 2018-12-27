<main class="container-fluid">
    <div class="row justify-content-around">
        <div class="col-9 kat">
            <?php
            include("connect.php");
            if(!isset($_GET['page'])) {
                $_GET['page'] = 1;
            }
            $offset = $_GET['page']*10-10;
            $limit = 10;
            
            $posts = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT :limit OFFSET :offset");
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
                echo '</h5>
                    <form method="POST"><h6 class="card-subtitle mb-2 text-muted">';
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
        <div class="col-3 kat">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Kategorie
                            <?php
                                if(log_in()==true) {
                                    if(grade($_SESSION['login']) == 'Admin') {
                                        echo ' <a class="btn btn-warning btn-sm" href="?view=add_cat" role="button">Dodaj</a>';
                                    }
                                }
                            ?>
                            </h5>
                            <div class="card-text">
                                <form method="post">
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
                                                    if(log_in()==true) {
                                                        if(grade($_SESSION['login']) == 'Admin' && $row['name'] != 'USUNIETE') {
                                                            $tmp = $row['id'];
                                                            echo ' <input type="submit" class="btn btn-danger btn-sm" value="Usuń" name="cat_'.$tmp.'">';
                                                            if(isset($_POST["cat_$tmp"])) {
                                                                $x = $pdo->prepare("UPDATE categories SET name = 'USUNIETE', subcategories = 'USUNIETE' WHERE id = :id ");
                                                                $x->bindParam(':id', $tmp, PDO::PARAM_STR, 10);
                                                                $x->execute();

                                                                $xtmp = 'USUNIETE';
                                                                $xx = $pdo->prepare("UPDATE posts SET subcat=:subcat where cat_id=:id");
                                                                $xx->bindParam(':subcat', $xtmp, PDO::PARAM_STR, 100);
                                                                $xx->bindParam(':id', $tmp, PDO::PARAM_STR, 10);
                                                                $xx->execute();
                                                            }
                                                        }
                                                    }
                                                    if($row['subcategories'] != NULL) {
                                                        echo '<ul>';
                                                            foreach(explode(',', $row['subcategories']) as $subcat) {
                                                                echo '<li><i class="fas fa-angle-right"></i>';
                                                                echo $subcat;
                                                                    if(log_in()==true) {
                                                                        if(grade($_SESSION['login']) == 'Admin') {
                                                                            echo ' <input type="submit" class="btn btn-danger btn-sm" value="Usuń" name="subcat_'.$subcat.'">';
                                                                            if(isset($_POST["subcat_$subcat"])) {
                                                                                $x1 = $pdo->prepare("UPDATE categories set subcategories = :subcat where id = :id");
                                                                                $x1->bindParam(':id', $tmp, PDO::PARAM_STR, 10);
                                                                                $new = str_replace($subcat, 'USUNIETE', $row['subcategories']);
                                                                                $x1->bindParam(':subcat', $new, PDO::PARAM_STR, 100);
                                                                                $x1->execute();

                                                                                $x2 = $pdo->prepare("UPDATE posts SET subcat=:subcat where subcat=:o_subcat");
                                                                                $xtmp = 'USUNIETE';
                                                                                $x2->bindParam(':subcat', $xtmp, PDO::PARAM_STR, 100);
                                                                                $x2->bindParam(':o_subcat', $subcat, PDO::PARAM_STR, 100);
                                                                                $x2->execute();
                                                                            }
                                                                        }
                                                                    }
                                                                echo '</li>';
                                                            }
                                                        echo '</ul>';
                                                    }
                                                    echo '</li>';
                                                }
                                            }
                                        ?>
                                    </ul>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <nav aria-label="Page navigation example">
          <ul class="pagination">
            <?php
                if(!isset($_GET['page'])) {
                $_GET['page'] = 1;
                }

                include("connect.php");
                $stron = $pdo->query("SELECT * from posts");
                $strony = $stron->rowCount();
                $stronki = round($strony/10, 0);
                if($_GET['page'] >= 2) {
                    $tmp = $_GET['page']-1;
                    echo '<li class="page-item"><a class="page-link" href="?page='.$tmp.'">Poprzednia</a></li>';
                }
                for($i = 1; $i <= $stronki; $i++) {
                    echo '<li class="page-item"><a class="page-link" href="?page='.$i.'">'.$i.'</a></li>';
                }
                if($_GET['page'] < $stronki) {
                    $tmp = $_GET['page']+1;
                    echo '<li class="page-item"><a class="page-link" href="?page='.$tmp.'">Następna</a></li>';
                }
            ?>
          </ul>
        </nav>
    </div>

</main>