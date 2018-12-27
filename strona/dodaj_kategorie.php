<main class="container-fluid">
    <div class="row justify-content-around">
        <div class="col-9 kat">
            <!-- komorka -->
            <div class="col post">
                <div class="card">
                  <div class="card-body">
                      <div class="card-title">
                      <form method="post">
                        <div class="input-group">
                          <input type="text" class="form-control" placeholder="Nazwa kategorii" aria-label="Recipient's username" aria-describedby="basic-addon2" name="val_cat">
                          <div class="input-group-append">
                            <input class="btn btn-outline-secondary" type="submit" value="Dodaj kategorię" name="add_cat">
                          </div>
                        </div>
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
                                        }
                                    }
                                ?>
                                </select>
                              </div>
                                <input type="text" class="form-control" placeholder="Nazwa podkategorii" name="val_subcat">
                                <div class="input-group-append">
                                    <input class="btn btn-outline-secondary" type="submit" value="Dodaj podkategorię" name="add_subcat">
                                </div>
                            </div>
                          </form>
                          <?php                               
                            if(isset($_POST['add_cat'])) {
                                if(!empty($_POST['val_cat'])) {
                                    include("connect.php");
                                    $add_cat = $pdo->prepare("INSERT INTO categories VALUES ('', :name, '')");
                                    $add_cat->bindParam(':name', $_POST['val_cat'], PDO::PARAM_STR, 15);
                                    $add_cat->execute();
                                    echo '<div class="card text-white bg-success">
                                    <div class="card-body">
                                    <p class="card-text"><b>Pomyślnie dodano kategorię!</b></p></div></div>'; 
                                } else {
                                   echo '<div class="card text-white bg-danger">
                                    <div class="card-body">
                                    <p class="card-text"><b>Wypełnij wszystkie pola!</b></p></div></div>'; 
                                }
                            }
                          
                          if(isset($_POST['add_subcat'])) {
                              if(!empty($_POST['val_subcat'])) {
                                  include("connect.php");
                                  $get_sub = $pdo->prepare("SELECT subcategories from categories where id = :id");
                                  $get_sub->bindParam(':id', $_POST['categ'], PDO::PARAM_STR, 10);
                                  $get_sub->execute();
                                  $get_sub_w = $get_sub->fetchAll();
                                  
                                  if($get_sub_w[0]['subcategories'] == NULL) {
                                      $sca = $_POST['val_subcat'];
                                  } else {
                                      $sca = $get_sub_w[0]['subcategories'].','.$_POST['val_subcat'];
                                  }
                                  
                                  $add_subcat = $pdo->prepare("UPDATE categories set subcategories = :sub where id = :id");
                                  $add_subcat->bindParam(':sub', $sca, PDO::PARAM_STR,100);
                                  $add_subcat->bindParam(':id', $_POST['categ'], PDO::PARAM_STR,10);
                                  $add_subcat->execute();
                                  echo '<div class="card text-white bg-success">
                                    <div class="card-body">
                                    <p class="card-text"><b>Pomyślnie dodano podkategorię!</b></p></div></div>';
                              } else {
                                  echo '<div class="card text-white bg-danger">
                                    <div class="card-body">
                                    <p class="card-text"><b>Wypełnij wszystkie pola!</b></p></div></div>'; 
                              }
                          }
                          
                          ?>
                        </div>
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

