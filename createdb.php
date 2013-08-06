<?php
// assumes a db handle in $dbh

// Create table
$sql="
CREATE TABLE map_data(
  id bigint(20) unsigned NOT NULL auto_increment,
  data_type CHAR(30),
  crime_id CHAR(100),
  event_date DATE,
  longitude FLOAT,
  latitude FLOAT,
  location CHAR(100),
  outcome_type CHAR(100),
  PRIMARY KEY (id),
  KEY data_type (data_type),
  KEY longitude (longitude),
  KEY latitude (latitude) )";
	
// Execute query
if (mysql_query( $dbh, $sql ) )  {
  echo "Table persons created successfully";
} else {
	echo "Error creating table: " . mysql_error($dbh);
}
