<?php
// DEBUg
function dbg($str){
  print("<pre>");
  print_r($str);
  print("</pre>");
}

// SQLi SETTINGS
$conn = mysqli_connect('localhost', 'root', '');
if (!$conn){
    die('Could not connect: ' . mysql_error());
}

$db_selected = mysqli_select_db($conn, 'excel');
if (!$db_selected){
    die ("Can't use table 'excel' : " . mysqli_error($conn));
}

$sheetID = $_REQUEST['sheet'];
$sheetID = intval($sheetID);

// TOTAL NUMBER OF RESULTS
$sql = "SELECT * FROM sheets
        WHERE sheetId = $sheetID";
$result = mysqli_query($conn, $sql);
$resultNumber = mysqli_num_rows($result);


// RESULTS ON 1 PAGE
$itemsOnPage = 5;
$totalPages = ceil($resultNumber/$itemsOnPage);

// TRACK PAGE NUMBER OF USER
if (!isset($_POST['page'])){
  $page = 1;
}
else{
  $page = $_POST['page'];
}
  //echo "sheet ID: ".$sheetID;

// determine the sql LIMIT starting number for the results on the displaying page
$firstResult = ($page-1)*$itemsOnPage;
$sql="SELECT * FROM sheets
      WHERE sheetId = $sheetID
      LIMIT $firstResult , $itemsOnPage";

$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

$countTable = 0;
while($resultDumped = mysqli_fetch_assoc($result)) {
  for($row = 1; $row <= sizeof($itemsOnPage); $row++){
    for ($col = 1; $col <= sizeof($itemsOnPage); $col++){
      echo "<div class = 'idExcel col-4'>".$resultDumped["companyId"]."</div>";
      echo "<div class = 'nameExcel col-4'>".$resultDumped["name"]."</div>";
      echo "<div class = 'ageExcel col-4'>".$resultDumped["age"]."</div>";
      echo "<div class = 'clearfix'> </div>";
      $countTable += 1;
    }
  }
}

// LINKS TO OTHER PAGES
for ($page=1;$page<=$totalPages;$page++) {
  echo '<button type="button" class="btn btnPage btn-primary"" data-sheet="'.$sheetID.'" data-page="' . $page . '">' . $page . '</button>';
}

?>
