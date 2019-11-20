<!DOCTYPE HTML>
<html lang="bg">
<head>
<title>ExDb 1.0</title>
<meta charset="utf-8">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class = "container-fluid">
  <div class = "header col-9">
    <img class = "logo" src = "logo.png">
    <div class = "title col-6"> ExDb PROJECT 1.0 </div>
  </div>
</div>

<?php
$fileName=basename($_FILES["fileToUpload"]["name"]);
$target_dir = "uploads/";
$target_file = $target_dir . $fileName;
$uploadOk = 1;
$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = pathinfo($_FILES["fileToUpload"]["name"]);
    if($check["extension"] == "xlsx") {
        $uploadOk = 1;
    } else {
        echo "File is not an excel file.";
        $uploadOk = 0;
    }
}
// Allow certain file formats
if($fileType != "xlsx"){
    echo "Sorry, only excel files allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo " Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (copy($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo " The file <b>". $fileName . "</b> has been uploaded.";
    } else {
        echo " Sorry, there was an error uploading your file.";
    }
}

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

// SQLi SETTINGS
$conn = mysqli_connect('localhost', 'root', '');
if (!$conn){
    die('Could not connect: ' . mysql_error());
}

$db_selected = mysqli_select_db($conn, 'excel');
if (!$db_selected){
    die ("Can't use table 'excel' : " . mysqli_error($conn));
};

// DEBUg
function dbg($str){
  print("<pre>");
  print_r($str);
  print("</pre>");
}

$sql = "SELECT * FROM sheetsdata";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)){
   $sheets[]=$row;
}

$sheetCount = sizeof($sheets);
for ($count = 0; $count < $sheetCount; $count++){
$sheetID = $sheets[$count]['id'];
  echo '
  <div class = "container-fluid">
    <div class = "tableTitle col-12"> Visual representation of ' .$sheets[$count]['sheetName']. ' </div>

  <div class = "clearfix"> </div>

  <div class = "container-fluid">
    <div class = "row">
      <div class = "id col-4"> COMPANY ID </div>
      <div class = "name col-4"> NAME </div>
      <div class = "age col-4"> AGE </div>
    </div>
  </div>
  </div>
  ';
  echo "<div class = 'tableContainer'> </div>";
  echo '<button type="button" class="btnShowPage btn btn-success" data-sheet="'.$sheetID.'">Show table</button>';

  echo "<div style=color:white>.</div>";
}
?>
<script>
// PAGINATON AJAX
function getNewPage(index){
  $(".tableContainer:eq("+index+") .btnPage").bind('click', function(event){
    var page = $(this).data("page");
    var sheet = $(this).data("sheet");
    $.ajax({
      type:"POST",
      url:"pagination-ajax.php",
      data:"sheet="+sheet+"&page="+page,
      success: function(data){
        $(".tableContainer:eq("+index+")").empty();
        $(".tableContainer:eq("+index+")").append(data);
        console.log(sheet)
        getNewPage(index)
      }
    })
  })
}
$(document).ready(function(){
  $(".btnShowPage").click(function(event){
    var index = $(".btnShowPage").index(this);
    var sheet = $(this).data("sheet");
    $.ajax({
      type:"POST",
      url:"pagination-ajax.php",
      data:"sheet="+sheet,
      success: function(html){
        $(".tableContainer:eq("+index+")").empty();
        $(".tableContainer:eq("+index+")").append(html);
        getNewPage(index);
      }
    });
  });
});

// SQL ADD TO DATABASE AJAX
$(document).ready(function(){
  $(".btnAddInfo").click(function(event){
    var sheet = $(this).data('sheet');
    var fileName = '<?php echo $fileName; ?>';
    $.ajax({
      type:"POST",
      url:"addInfo-ajax.php",
      data:"sheet="+sheet+"&fileName="+fileName,
      success: function(sql){
        console.log(sql);
      }
    })
  })
})

</script>
<?php
echo '<button type="button" class= "btnAddInfo btn btn-info"> Add <b>'. $fileName. ' </b>to database </button>';
?>


</body>
</html>
