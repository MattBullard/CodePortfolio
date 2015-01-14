#!c:/perl/bin/perl

require './support/globals.pl';                               # needed variables and subroutines
require './support/connections.pl';                           # needed subroutines

$DEBUG = 0; # 0 = off, 1 = on

$tableName = "co";                            # the name of the mySql table to fill:  

$csvfile = $folderLocation . "co.csv";        #the externial input data file
$outfile = "out_".$tableName.".csv";          #the file name for the outputted data

#intially this comes from the first line of the CSV, but may need to be change to fit into database.
$columnNames = "type,co_num,cust_num,cust_seq,cust_po";

&PrintHeader();     # to show the name of the file being run in the console
&uploadWithCSV();	  # the upload function for this specific file type.
