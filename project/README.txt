ExDb PROJECT 1.0 

This is a project which utilizes mainly backend web development progamming ( PHP ) and database manipulation. The idea is the following - you upload a excel file ( which follows a pre-set template, which can be found in the 'reader.xlsx' file ) and it gets uploaded to a database.

To access it - go to project folder - the files inside the main repo are from composer and the data needed for PHPspreadsheet. The main file is called "index.htm", and the main php file is "upload.php", acompanied by "addInfo-ajax.php" & "pagination-ajax". All is explained underneath.

At the moment, it's set up like this - 1 database called 'excel' and 2 tables - 'sheets' and 'sheetsdata'. 

SHEETS table

id - auto incremented
name - from the excel sheet under NAME
age - from the excel sheet under AGE
companyId - from the excel sheet under ID
sheetnumber - as in first, second, third sheet from the excel file; information from 'sheetsdata table'
sheetId - what is the id of the sheet; information from 'sheetsdata table' 

SHEETSDATA table 

id - auto incremented 
sheetName - name of the sheet, from which the data was taken

INDEX.HTM

A very basic and bland page, which is used to upload the excel file ( which goes to uploads directory ), redirects after you press the only button to upload.php.

UPLOAD.PHP

First, the file that is uploaded is ran through a check to see if it fits the criteria. The uploading criteria and system in general is very basic, not a lot of restrictions have beeen added at this stage. After this has passed, composer is utilized to start PHPOffice/PHPspreadsheet library, and a few settings are passed through in order for the program to run properly. Connection to database follows after, if failed the program will not run and a restart will be required.


Upload.php is basically a quick loop which uses the other files, which are utilized with AJAX to make the application more handy, which generates the tables that are already added into the database. The first time you run it should error, as at the moment when you press the big blue button, saying "Upload $fileName to database" it does not refresh/ add the items to the page, but don't worry! Refreshing the page should show the uploaded sheets :). 

And at the end, when the <script> tag begins, comes the AJAX!. "pagination-ajax.php" is used twice - explained in a bit, since it's connected to the functionality of it. The variables sent are sheet we are looking at, since we want to change the correct sheet, and the page we're on. At the moment, the default generation of items on a page is 5 -- it can be changed in "pagination-ajax.php". The second AJAX connected to the file is to see which index we are on ( EXPLAINED IN A BIT ).

And the final part is the "addInfo-ajax.php", which sends the sheet and filename to the file, so it's added to the database - simple :).

PAGINATION-AJAX.PHP 

A basic way to have a pagination function, because if we upload very big sheets, we can't have all of the tables show and spill into a very, very long and unpleasant list - done for user friendliness. If no $page variable has been set, which relates to the page we are on, it is automatically set to 1, as indexing 1 for the first one, not like an array =D.

ADDINFO-AJAX.PHP

A basic way to upload the data into into the database. Follows the strict [sadly :(, will make it more flexible!] formation of the  file and uploads them to the correct places inside the database.

This is pretty much it, I'll try to update it and make it more friendly to the eye and add some more flexible functionality. 