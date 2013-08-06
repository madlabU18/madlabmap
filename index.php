<?php
define('DB_NAME', 'max_db');

/** MySQL database username */
define('DB_USER', 'max_user');

/** MySQL database password */
define('DB_PASSWORD', 'max_db_YRS_2013');

/** MySQL hostname */
define('DB_HOST', 'localhost');

$dbh = mysql_connect( DB_HOST, DB_USER, DB_PASSWORD );
if ( !$dbh ) {
	print( "error connecting to the database" );
}

  
  