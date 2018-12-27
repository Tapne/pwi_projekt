<main class="container-fluid">
    <div class="row justify-content-around">
        <div class="col-5 kat">
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
        <div class="col-5 kat">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Ilość postów w kategoriach</h5>
                    <div class="card-text">
                        <table class="table">
                          <thead class="thead-dark">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nazwa</th>
                                <th scope="col">Rodzic</th>
                                <th scope="col">Ilość postów</th>

                            </tr>
                          </thead>
                          <tbody>
                              <?php
                                include("connect.php");
                                $allcat = $pdo->prepare("SELECT DISTINCT * FROM categories");
                                $allcat->execute();
                                $cate = $allcat -> fetchAll();
                                $number = 1;
                                foreach($cate as $cat) {
                                    echo '<tr><th scope="row">'.$number.'</th>';
                                    echo '<td>'.$cat['name'].'</td>';
                                    echo '<td class="text-muted"></td>';
                                    echo '<td>';
                                    $ilosc_sub = $pdo->prepare("SELECT * FROM posts WHERE cat_id = :cat");
                                    $ilosc_sub->bindParam(':cat', $cat['id'], PDO::PARAM_INT, 20);
                                    $ilosc_sub->execute();
                                    $ilosc_sub_w = $ilosc_sub->rowCount();
                                    echo $ilosc_sub_w;
                                        
                                    echo' <i class="fas fa-file-alt"></i></td>';
                                    $number++;
                                }
                                foreach($cate as $cat) {
                                    if($cat['subcategories'] != null) {
                                        if (strpos($cat['subcategories'], ',') == true) {
                                            foreach(explode(',', $cat['subcategories']) as $subcat1) {
                                                echo '<tr><th scope="row">'.$number.'</th>';
                                                echo '<td>'.$subcat1.'</td>';
                                                echo '<td class="text-muted">'.$cat['name'].'</td>';
                                                echo '<td>';
                                                $ilosc_subcat = $pdo->prepare("SELECT * FROM posts WHERE subcat = :subcat");
                                                $ilosc_subcat->bindParam(':subcat', $subcat1, PDO::PARAM_STR, 20);
                                                $ilosc_subcat->execute();
                                                $ilosc_subcat_w = $ilosc_subcat->rowCount();
                                                echo $ilosc_subcat_w;
                                                echo'  <i class="fas fa-file-alt"></i></td>';
                                                $number++;
                                            }
                                        } else {
                                           echo '<tr><th scope="row">'.$number.'</th>';
                                            echo '<td>'.$cat['subcategories'].'</td>';
                                            echo '<td class="text-muted">'.$cat['name'].'</td>';
                                            echo '<td>';
                                            $ilosc_sub = $pdo->prepare("SELECT * FROM posts WHERE id = :cat");
                                            $ilosc_sub->bindParam(':cat', $cat['id'], PDO::PARAM_INT, 20);
                                            $ilosc_sub->execute();
                                            $ilosc_sub_w = $ilosc_sub->rowCount();
                                            echo $ilosc_sub_w;
                                            echo' <i class="fas fa-file-alt"></i></td>';
                                            $number++; 
                                        }
                                    }
                                }
                            ?>
                          </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>