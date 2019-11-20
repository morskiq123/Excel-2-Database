ExDb 1.0 - .xlsx to database web application

This is my first personal project, based on PHP, AJAX and a little bit of front-end utilization so it doesn't look bare-bones x). As the title suggests - it uses a specific Excel template[file is called TEMPLATE](works only with .xlsx, which means Excel 2007 and later) which has been included in the project.

HOW TO USE

First you will need to set up your databases. I personally used XAMPP for server hosting and database and Navicat to navigate easily through the database. You will need one database with the name "excel" (I've commented where you can change them if you wish) with two tables (again - commented) "sheets" with the rows id, name, age, sheetNumber, sheetId, companyId. 

ID is auto-incremented, name, age & companyId are from the Excel table. sheetNumber is from which sheet from the Excel file it(the sheet) was taken from. sheetId is taken from "sheetsdata" - the ID to be more specific --> explained below.

"sheetsdata" contains ID(auto-incremented) and sheetName. The ID is which sheet in a row it is. 

Now that this is done, you can begin using the application. 

You begin from index.html, where you'll be prompted to upload an excel file, a check is in place to see if you do actually upload the correct format (.XLSX). Then you will be redirected to upload.php. Your file will be ran through a check (first lines of code from upload.php) to confirm that there is no problem. If all is good and your file is uploaded, then you will be shown a blue/teal button saying "Add $fileName.xlsx to database". Clicking it will add the sheets and the corresponding information ONCE (doesn't matter if you click it more than once) to the database. Reload the page and you should be "Visual representation of $sheetName". Clicking "Show table" should bring up a table with the first 5 listings of the sheet. Each page corresponds to 5 listings.

This is pretty much it for now. It will be updated. I shall put up a to-do list in the near future to detail what else needs to be done! :) Hope you find it useful!!!   