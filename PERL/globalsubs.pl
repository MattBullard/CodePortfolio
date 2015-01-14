#!c:/perl/bin/perl
############################################################################
sub PrintTime
############################################################################
{
    ($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime( );
    print "Time = " . $hour . ":" . $min . ":" . $sec . "\n";
}
############################################################################
sub PrintHeader
############################################################################
{
    printf "\n\n";
    printf "**************************************************************\n";
    printf "                     Database Filler\n";
    printf "                    (c)2011-$year mdb\n";
    printf "\n                    Script for: " . $tableName . ".pl\n\n";
    printf "                    Programmed By mdb\n";
    printf "**************************************************************\n\n";
}
############################################################################
sub emptyTable
############################################################################
{
    my $dBaseName = $_[ 0 ];
    $query1 = qq{ TRUNCATE $dBaseName };
    if ( $DEBUG ) { printf "$query1 \n"; }
    my $sql1 = $mySqlDB1->prepare( $query1 );
    $sql1->execute;
}

############################################################################
sub fillTable    # 07/11/2012
############################################################################
{
    my $incomingFileName = $_[ 0 ];
    my $doNotEmpty = $_[ 1 ];
    my $fileSize = -s $FileName;  # size of incoming XLS file
    
    # This only works as long as there is a column in the table for each column in the excel file.
    # Extra columns in the excel file it will not go into the table.
    # Extra columns in the table will be filled with an empty string.
    
    # Changes made to this function affect all scripting files!!!
    
    printf "Selected MySQL Schema: " . $schemaName . " \n";
    printf "Selected MySQL Table: " . $tableName . " \n";
    printf "Reading Excel File. (" . commify($fileSize) . " bytes) \n";
    
    my $parser   = Spreadsheet::ParseExcel->new();
    my $workbook = $parser->Parse( $FileName );
    my $sheetcnt = $workbook->{SheetCount};
    my $cellpointer = 0;
    my @valuearray;
    my $strWordsLen;
    my $dcount = 0;
    my $shtCounter = 1;
    
    if ($doNotEmpty != 1) {
    	printf "Emptying old table data \n";
	    emptyTable( $tableName );
    }
	
	if ( !defined $workbook ) { die $parser->error(), ".\n"; }
  
	for my $worksheet ( $workbook->worksheets() ) { 
        my $currRow = 0;
        my $perComplete = 0;
      
        my ( $row_min, $row_max ) = $worksheet->row_range();
        my ( $col_min, $col_max ) = $worksheet->col_range();
              
        $dcount = $row_max; # how many rows are we looking at here?
        $row_min++; # skip the first row.

		for my $row ( $row_min .. $row_max ) {
			
        	for ($ct = 0; $ct<$strWordsLen; $ct++) { printf "\b"; } 
                $perComplete = ceil(($currRow/$dcount)* 100);
                $strWords = "Writing row " . commify($currRow) . " out of " . commify($dcount) . " rows to the table.  (" . $perComplete . "%% of sheet " . $shtCounter . " complete out of " . $sheetcnt . " sheets)";
                $strWordsLen = length($strWords); 
                $currRec++; printf $strWords . "";
                
                for my $col ( $col_min .. $col_max ) {
                    my $cell = $worksheet->get_cell( $row, $col );
                    next unless $cell;
                    $valuearray[$cellpointer] = cleanField( $cell->value() );  # may need to clear off leading spaces.
                    $cellpointer++;
                }
                
                $cellpointer = 0;
                $currRow ++;
                
                # need to get all column names and thier data type from selected table and create the INSERT statement dynamically.
                my @tableColumnNames;
                my @tableDataTypes;
                my $tableColumnCount = 0;
                $query = qq{ select column_name, data_type from information_schema.columns where table_name = '$tableName' AND table_schema = '$schemaName' order by ordinal_position };
                $dbh = $mySqlDB1->prepare($query);
                $dbh->execute();
                while (@data = $dbh->fetchrow_array()) { 
                $tableColumnNames[$tableColumnCount] = $data[0]; # contains the names of each column, saved to an array
                $tableDataTypes[$tableColumnCount] = $data[1]; # contains the data_type of each column, saved to an array
                $tableColumnCount++; 
			} 
			my $qtyOfColumns = scalar(@tableColumnNames); #get the length of the array that holds the column names

            # create the sql statment
            $query1 = qq{ INSERT INTO $tableName ( };
            for my $x ( 1 .. $qtyOfColumns-1 ) { $query1 .= qq{ `$tableColumnNames[$x]`, }	}
            $query1 = substr ($query1, 0, (length($query1) - 2));  #chop off the last two spaces to get rid of the extra comma
            $query1 .= qq{ ) VALUES ( };
            for my $x ( 1 .. $qtyOfColumns-1 ) {
                my $tmpVar = $valuearray[$x-1];
                if (length($tmpVar) == 0) { # this is used to ensure that if a field is left blank, it will not mess up the cycle.
                     if ($tableDataTypes[$x] eq 'int') { $tmpVar = '0'; }
                     if ($tableDataTypes[$x] eq 'float') { $tmpVar = '0'; }
                     if ($tableDataTypes[$x] eq 'date') { $tmpVar = '2033-12-12'; }
                     if ($tableDataTypes[$x] eq 'datetime') { $tmpVar = '2033-12-12'; }
                }
                $query1 .= qq{'$tmpVar', }
			};
			$query1 = substr ($query1, 0, (length($query1) - 2));  #chop off the last two spaces to get rid of the extra comma
			$query1 .= qq{ )};
					
            if ( $DEBUG ) { printf "$query1 \n"; }
            my $sql = $mySqlDB1->prepare( $query1 ) || die "Could not prepare SQL statement:$query1";
            $sql->execute || die "Could not execute SQL statement:$query1";
            
		}
        print "\n";
        $shtCounter ++;			
	}	
    printf "\n\n";
    return 1;
} # fill table
############################################################################
sub commify
############################################################################
{
    # this will take a number and add commas to it
    # for example: 12345678 becomes: 12,345,678
    
    my $text = reverse $_[0];
    $text =~ s/(\d\d\d)(?=\d)(?!\d*\.)/$1,/g;
    return scalar reverse $text
}
############################################################################
sub cleanField    #07/17/2012
############################################################################
{
    my $field = $_[ 0 ];
    $field =~ s/^\s+//; # trim leading spaces
    $field =~ s/\s+$//; # trim trailing spaces
    $field =~ s/\'//g; # get rid of apostrophes
    $field =~ s/\"//g; # get rid of quotes
    $field =~ s/\r//g; # get rid of return characters
    $field =~ s/\n//g; # get rid of new line characters
    $field =~ s/\^/\n/g; # for this purpose, replace tildes with new lines
    return $field;
} ##cleanField
############################################################################
sub chopOutDate
############################################################################
{
    my $data = $_[ 0 ]; #it may be blank;
    
    if (length($data) > 0) {
	    my @date_data = split(' ', $data);
	    return $date_data[0];
    } else {
    	return "2033-12-12";
    }
} #chopOutDate
############################################################################
sub chopOutNumber
############################################################################
{
    my $data = $_[ 0 ]; #it may be blank;
    
    if (length($data) > 0) {
	    my @date_data = split(' ', $data);
    	return $date_data[0];
    } else {
	    return 0;
    }
} #chopOutNumber
############################################################################
sub chopOutDateTime
############################################################################
{
    my $data = $_[ 0 ]; #it may be blank;
    
    if (length($data) > 0) {
        my @date_data = split(' ', $data);  # expecting 11-20-2012  2:29:00 PM
        $year = $date_data[0]; #print $year . "\n";
        $time = $date_data[1];
        $r = $year . " " . $time;
        #print $r . "\n";
        return $r;
    } else {
	    return "2033-12-12 00:00:00";
    }
} #chopOutDateTime

1
