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
                          <div class="card-title">
                                <div class="input-group">
                                  <div class="input-group-prepend">
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
                                  </div>
                                      <input type="text" class="form-control" placeholder="Tytuł" name="title">
                                </div>
                          </div>
                          <hr>
                        <div class="card-text">
                            <div class="form-group">
                              <textarea class="form-control" rows="5" id="comment" name="var"></textarea>
                            </div>
                        </div>
                          <div class="read_more">
                                <input type="submit" class="btn btn-success" value="Dodaj" name="add_post">
                          </div>
                      </div>
                        
                        
                  </form>
                </div>
                <?php
                        if(isset($_POST['add_post'])) {
                            if(!empty($_POST['var']) && !empty($_POST['title'])) {
                                include("connect.php");
                                $result = $pdo->prepare("SELECT id FROM users WHERE login = :login");
                                $result->bindParam(':login', $_SESSION['login'], PDO::PARAM_STR, 10);
                                $result->execute(); 
                                $id = $result->fetchAll();


                                $today = date("Y-m-d H:i:s"); 
                                $query = $pdo->prepare("INSERT INTO posts VALUES ('', :date, :login, :cat, :subcat, :val, :title)");
                                $query->bindParam(':date', $today, PDO::PARAM_STR, 20);
                                $query->bindParam(':login', $id[0]['id'], PDO::PARAM_STR, 10);
                                if(strpos($_POST['categ'], ",") == true) {
                                    $category = explode(",", $_POST['categ']);
                                    $query->bindParam(':cat', $category[0], PDO::PARAM_STR, 20);
                                    $query->bindParam(':subcat', $category[1], PDO::PARAM_STR, 20);
                                } else {
                                    $tmp = '';
                                    $query->bindParam(':cat', $_POST['categ'], PDO::PARAM_STR, 20);
                                    $query->bindParam(':subcat', $tmp, PDO::PARAM_STR, 20);
                                }
                                $query->bindParam(':val', $_POST['var'], PDO::PARAM_STR, 5000);
                                $query->bindParam(':title', $_POST['title'], PDO::PARAM_STR, 20);
                                $query->execute();
                                echo '<div class="card text-white bg-success">
                                <div class="card-body">
                                <p class="card-text"><b>Post dodano pomyślnie!</b></p></div></div>';
                            } else {
                                echo '<div class="card text-white bg-danger">
                                <div class="card-body">
                                <p class="card-text"><b>Wypełnij wszystkie pola!</b></p></div></div>';
                            }
                        }
                    ?>
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
    </div>
</main>

