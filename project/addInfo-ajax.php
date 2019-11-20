<?php

// DEBUg
function dbg($str){
  print("<pre>");
  print_r($str);
  print("</pre>");
}

$sheetID = $_POST['sheet'];
echo $sheetID;
$fileName = $_POST['fileName'];

echo "$fileName";

// COMPOSER
require 'D:\xampp\htdocs\excel/vendor/autoload.php';

// PHPSPREADSHEET
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// READER SETTINGS
$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
$reader->setReadDataOnly(true);
$reader->setLoadAllSheets();

// LOADING THE SPREADSHEET
$spreadsheet = $reader->load($fileName);
$worksheet = $spreadsheet->getActiveSheet();
$sheetCount = $spreadsheet -> getSheetCount();
// echo "<div class = 'container-fluid'>
//       <div class = 'sheetCount col-12'>
//       Sheet count: ".$sheetCount.PHP_EOL. "</div>";
$sheetNames = $spreadsheet -> getSheetNames();

// SQLi SETTINGS
$conn = mysqli_connect('localhost', 'root', '');
if (!$conn){
    die('Could not connect: ' . mysql_error());
}

$db_selected = mysqli_select_db($conn, 'excel');
if (!$db_selected){
    die ("Can't use table 'excel' : " . mysqli_error($conn));
};
// ARRAYS & VARIABLES NEEDED FOR SQLi LOOPING
$ids = [];
$names = [];
$ages = [];
$validator = 0;

// LOOP FUNCTION GETTING CELL DATA FROM ALL SHEETS
for ($count = 0; $count < $sheetCount; $count++){
  $highestRow = $worksheet->getHighestRow();
  $highestRow += 1;
  $highestColumn = $worksheet->getHighestColumn();
  $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
  $worksheet = $spreadsheet -> getSheet($count);

  // EMPTYING THE ARRAYS
  $ids = array();
  $names = array();
  $ages = array();

  for ($row = 2; $row <= $highestRow; ++$row) { // START FROM ROW 2 TO AVOID TEMPLATE
    for ($col = 1; $col <= $highestColumnIndex; ++$col) { // START FROM COLUMN A
      $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
      // SORTING INTO CORRECT ARRAYS
      if ($col == 1){
        if ($value != NULL){
          $ids[] = $value;
        }
      }
      if ($col == 2){
        if ($value != NULL){
          $names[] = $value;
        }
      }
      if ($col == 3){
        if ($value != NULL){
          $ages[] = $value;
        }
      }

      if(sizeof($ids) > 0 && sizeof($names) > 0 && sizeof($ages) > 0){
        $validator = 1;
      }
    }
  }

  // SHEET CHANGE HAPPENS HERE

  // INSERTING SHEET NAME & GETTING SHEET ID
  $sql = "INSERT INTO sheetsData (sheetName)
          VALUES ('$sheetNames[$count]')";
  $result = mysqli_query($conn, $sql);

  $sheetID = mysqli_insert_id($conn);

  // INSERTING GATHERED CELL DATA INTO SQLi
  $countSQL = 0;
  $sheetNumber = $count+1;
  foreach ($ids as $id){
    $id = intval($id);

    $name = $names[$countSQL];

    $age = $ages[$countSQL];
    $age = intval($age);

    $sql = "INSERT INTO sheets (companyId, `name`, age , sheetNumber, sheetId)
            VALUES ($id, '$name', $age, $sheetNumber, $sheetID)";
    $result = mysqli_query($conn, $sql); //or die(mysqli_error($conn));

    $countSQL += 1;
  }
}
?>
