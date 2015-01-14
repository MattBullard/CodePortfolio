#!c:/perl/bin/perl

# open local mySQL DB

$schemaName = "datafile";

$mySqlDB = DBI->connect('dbi:mysql:data:nb1_sql:3306;mysql_connect_timeout=75', 'root', 'sql') || die "Can't open MySQL DB  $DBI::errstr\n";
$mySqlDB->{PrintError}=1;
$mySqlDB->{RaiseError}=1;
