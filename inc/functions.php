<?php
require_once('database.php');
    
function get_language(){
    global $dbname;
    global $conndb;

    $sql = "SELECT * FROM languages ORDER BY name ASC";
    mysqli_select_db($conndb, $dbname);
    $result = mysqli_query($conndb, $sql) or die(mysqli_error($conndb));
    $rows = mysqli_fetch_assoc($result);
    
    $tot_rows = mysqli_num_rows($result);
    if($tot_rows > 0){
        echo "<select name=\"srch_language\" id=\"srch_language\"\n>";
        echo "<option value=\"\">Any Language&hellip;</option>";
        do{
            echo "<option value=\"".$rows['id']."\"";
            getSticky(2, 'srch_language', $rows['id']);
            echo ">".$rows['name']."</option>";
        }while($rows = mysqli_fetch_assoc($result));
        echo "</select>";
    }

    mysqli_free_result($result);
}

function get_category(){
    global $dbname;
    global $conndb;

    $sql = "SELECT * FROM categories ORDER BY name ASC";
    mysqli_select_db($conndb, $dbname);
    $result = mysqli_query($conndb, $sql) or die(mysqli_error($conndb));
    $rows = mysqli_fetch_assoc($result);
    
    $tot_rows = mysqli_num_rows($result);
    if($tot_rows > 0){
        echo "<select name=\"srch_category\" id=\"srch_category\"\n>";
        echo "<option value=\"\">Any Category&hellip;</option>";
        do{
            echo "<option value=\"".$rows['id']."\"";
            getSticky(2, 'srch_category', $rows['id']);
            echo ">".$rows['name']."</option>";
        }while($rows = mysqli_fetch_assoc($result));
        echo "</select>";
    }

    mysqli_free_result($result);
}

function get_author(){
    global $dbname;
    global $conndb;

    $sql = "SELECT * FROM authors ORDER BY name ASC";
    mysqli_select_db($conndb, $dbname);
    $result = mysqli_query($conndb, $sql) or die(mysqli_error($conndb));
    $rows = mysqli_fetch_assoc($result);
    
    $tot_rows = mysqli_num_rows($result);
    if($tot_rows > 0){
        echo "<select name=\"srch_author\" id=\"srch_author\"\n>";
        echo "<option value=\"\">Any Author&hellip;</option>";
        do{
            echo "<option value=\"".$rows['id']."\"";
            getSticky(2, 'srch_author', $rows['id']);
            echo ">".$rows['name']."</option>";
        }while($rows = mysqli_fetch_assoc($result));
        echo "</select>";
    }

    mysqli_free_result($result);
}

function get_year(){
    global $dbname;
    global $conndb;

    $sql = "SELECT DISTINCT YEAR(date_released) AS year FROM books ORDER BY date_released ASC";
    mysqli_select_db($conndb, $dbname);
    $result = mysqli_query($conndb, $sql) or die(mysqli_error($conndb));
    $rows = mysqli_fetch_assoc($result);
    
    $tot_rows = mysqli_num_rows($result);
    if($tot_rows > 0){
        echo "<select name=\"srch_year\" id=\"srch_year\"\n>";
        echo "<option value=\"\">Any Year&hellip;</option>";
        do{
            echo "<option value=\"".$rows['year']."\"";
            getSticky(2, 'srch_year', $rows['year']);
            echo ">".$rows['year']."</option>";
        }while($rows = mysqli_fetch_assoc($result));
        echo "</select>";
    }

    mysqli_free_result($result);
}

function get_cover(){
    global $dbname;
    global $conndb;

    $sql = "SELECT * FROM covers ORDER BY name ASC";
    mysqli_select_db($conndb, $dbname);
    $result = mysqli_query($conndb, $sql) or die(mysqli_error($conndb));
    $rows = mysqli_fetch_assoc($result);
    
    $tot_rows = mysqli_num_rows($result);
    if($tot_rows > 0){
        echo "<label for=\"srch_cover-0\">";
        echo  "<input type=\"radio\" name=\"srch_cover\" id=\"srch_cover-0\" value=\"0\"/>\nAny</label>";
        do{
            echo "<label for=\"srch_cover-".$rows['id']."\">";
            echo "<input type=\"radio\" name=\"srch_cover\" id=\"srch_cover-".$rows['id']."\" value=\"".$rows['name']."\"";
            getSticky(4, 'srch_cover', $rows['id']);
            echo "/>\n".$rows['name']."</label>";
        }while($rows = mysqli_fetch_assoc($result));
        echo "</select>";
    }

    mysqli_free_result($result);
}

function get_location(){
    global $dbname;
    global $conndb;

    $sql = "SELECT * FROM locations ORDER BY name ASC";
    mysqli_select_db($conndb, $dbname);
    $result = mysqli_query($conndb, $sql) or die(mysqli_error($conndb));
    $rows = mysqli_fetch_assoc($result);
    
    $tot_rows = mysqli_num_rows($result);
    if($tot_rows > 0){
        echo "<ul class=\"chkbx\">"; echo "</ul>";
        do{
            echo "<li>";
            echo "<input type=\"checkbox\" name=\"srch_location-".$rows['id']."\" id=\"srch_location-".$rows['id']."\" values=\"".$rows['id']."\"";
            getSticky(3, "srch_localtion-".$rows['id'], $rows['id']);
            echo "/><label for=\"srch_location-".$rows['id']."\">".$rows['name']."</label>";
            echo "</li>";
        }while($rows = mysqli_fetch_assoc($result));
        echo "</ul>";
    }

    mysqli_free_result($result);
}

function getSticky($case, $par, $value="", $initial=""){
    switch ($case) {
        case 1:    //text field
            if(isset($_GET[$par]) && $_GET[$par] != ""){
                echo stripcslashes($_GET[$par]);
            }
            break;

        case 2:     //select field
            if(isset($_GET[$par]) && $_GET[$par] == $value){
                echo "selected = \"selected\"";
            }
            break;
        
        case 3:     //checkbox group
            if(isset($_GET[$par]) && $_GET[$par] != ""){
                echo "checked = \"checked\"";
            }
            break;
        
        case 4:     //radio buttons
            if(isset($_GET[$par]) && $_GET[$par] == $value){
                echo "checked = \"checked\"";
            }else{
                if($initial != ""){
                    echo "checked = \"checked\"";
                }
            }
            break;
    }
}



?>