<?php require_once("inc/functions.php");
      require_once("inc/database.php");

    $sql = "SELECT DISTINCT bk.title AS Title, YEAR(bk.date_released) AS Year, bk.price AS Price, 
                cat.name AS Category, aut.name AS Author 
                FROM books bk

                    JOIN categories cat 
                    ON cat.id = bk.category 
                    
                    JOIN books_covers bk_co
                    ON bk_co.book_id = bk.id
                    
                    JOIN covers co
                    ON co.id = bk_co.cover_id
                    
                    JOIN books_authors bk_aut
                    ON bk_aut.book_id = bk.id
                    
                    JOIN authors aut
                    ON aut.id = bk_aut.author_id
                    
                    JOIN books_languages bk_lan
                    ON bk_lan.book_id = bk.id
                    
                    JOIN languages lan
                    ON lan.id = bk_lan.lang_id
                    
                    JOIN books_locations bk_loc
                    ON bk_loc.book_id = bk.id
                    
                    JOIN locations loc
                    ON loc.id = bk_loc.location_id
                ";

mysqli_select_db($conndb, $dbname);
$result = mysqli_query($conndb, $sql) or die(mysqli_error($conndb));
$rows = mysqli_fetch_assoc($result);

$tot_rows = mysqli_num_rows($result);

if(isset($_POST['btn'])){
    $title = $_POST['Title'];
    $category = $_POST['Category'];
    $author = $_POST['Author'];
    $year = $_POST['Year'];
    $price = $_POST['Price'];
}

if(isset($_GET['srch_for'])){
    $locations = array();
    $getters = array();
    $queries = array();
    
    foreach($_GET as $key => $value){
        $temp = is_array($value) ? $value : trim($value);
        if(!empty($temp)){
            list($key) = explode("-", $key);
            if($key == 'srch_locations'){
                array_push($location, $value);
            }
            if(!in_array($key, $getters)){
                $getters[$key] = $value;
            }
        }
    }
    if(!empty($locations)){
        $loc_query = implode("-", $locations);
    }

    foreach($getters as $key => $value){
        ${$key} = $value;
        switch ($key) {
            case 'srch_for':
                array_push($queries, "(bk.title LIKE '%srch_for%' || bk.description LIKE '%srch_for%' || bk.isbn LIKE '%srch_for%')");
                break;
            case 'srch_category':
                array_push($queries, "bk.category = $srch_category");
                break;
            case 'srch_cover':
                array_push($queries, "bk_co.cover_id = $srch_cover");
                break;
            case 'srch_author':
                array_push($queries, "bk_aut.author_id = $srch_author");
                break;
            case 'srch_language':
                array_push($queries, "bk_lan.lang_id = $srch_language");
                break;
            case 'srch_year':
                array_push($queries, "YEAR(bk.date_released) = $srch_year");
                break;
            case 'srch_location':
                array_push($queries, "bk_loc.location_id IN ($loc_query)");
                break;                
        }
    }

    if(!empty($queries)){
        $sql .= " WHERE ";
        $i = 1;
        foreach($queries as $query){
            if($i < count($queries)){
                $sql .= $query." OR "; 
            }else{
                $sql .= $query;
            }
            $i++;
        }
    }

    $sql .= " ORDER BY bk.title ASC";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="styles/core.css"></link>
</head>
<body>
    <div id="out">
        <div id="ph">
            <div id="phin">
                <h1>Advanced Search Form</h1>
                <h3>Your own search platforms</h3>
            </div>
        </div>
        <div id="wr">
        <div id="hd"></div>
            <div id="cnt">
                <h2>Search For the book</h2>
                <div id="br"><hr></div>
                <form id="search_form" name="search_form" action="" method="GET">
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <th scope="row" style="background: lightgray;">Search For: </th>
                            <td><input type="text" name="srch_for" id="search_for" class="f_fld" value="<?php getSticky(1, 'srch_for'); ?> "/></td>
                            <th style="background: lightgray;"><label for="srch_category">Category:</label></th>
                            <td><?php get_category(); ?></td>
                        </tr>
                        <tr>
                            <th scope="row" style="background: lightgray;"><label for="srch_cover">Cover Type:</label></th>
                            <td><?php get_cover(); ?></td>
                            <td style="background: lightgray;"><label for="srch_author">Author:</label></td>
                            <td><?php get_author(); ?></td>
                        </tr>
                        <tr>
                            <th scope="row" style="background: lightgray;"><label for="srch_language">Language:</label></th>
                            <td><?php get_language(); ?></td>
                            <td style="background: lightgray;"><label for="srch_year">Year:</label></td>
                            <td><?php get_year(); ?></td>
                        </tr>
                        <tr>
                            <th scope="row" style="background: lightgray;">Available in:</th>
                            <td colspan="4"><?php get_location(); ?></td>
                        </tr>
                    </table>
                        <tr>
                            <td colspan="4"><label for="btn" class="sbm fl_r">
                                <input type="submit" id="btn" name="btn" value="Search" />
                            </label></td>
                        </tr>
                </form>
                <?php if($tot_rows > 0){   ?> 
                <table border="0" cellspacing="0" cellpadding="0" class="tbl_repeat">
                    <tr>
                        <th scope="col">Book Title</th>
                        <th scope="col" class="col_15">Category</th>
                        <th scope="col" class="col_15">Author</th>
                        <th scope="col" class="col_10 al_r">Year</th>
                        <th scope="col" class="col_10 al_r">Price</th>
                    </tr>
                    <?php do{ ?>
                    <tr>
                        <td><?php echo $rows['Title']; ?></td>
                        <td><?php echo $rows['Category']; ?></td>
                        <td><?php echo $rows['Author']; ?></td>
                        <td><?php echo $rows['Year']; ?></td>
                        <td><?php echo $rows['Price']; ?></td>
                    </tr>
                    <?php }while($rows = mysqli_fetch_assoc($result)) ?>
                </table>
                <?php  }else{ ?>
                    <?php if(!empty($queries)) {
                        echo "<p>There are no queries available in this search Form</p>";
                    } else{
                           echo "<p>There are currently no records Avaible.</p>";
                        }
                    }
                    ?>  
            </div>
        </div>
    </div>

    <div id="ft">
        <div id="flin">
            <p>Copyright @2021 </p>
        </div>
    </div>
</body>
</html>
<?php mysqli_free_result($result); ?>