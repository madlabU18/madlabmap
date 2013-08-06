<?php
// assumes a db handle in $dbh

// Create table
$sql="
CREATE TABLE map_data(
  id bigint(20) unsigned NOT NULL auto_increment,
  data_type CHAR(30),
  crime_id char(100),
  event_date date,
  longitude float,
  latitude float,
  location char(100),
  outcome_type,

  primary_key(id),
  index data_type(data_type),
  index longitude(longitude),
  index latitude(latitude)
	)";

// Execute query
if (mysqli_query( $dbh, $sql ) )  {
  echo "Table persons created successfully";
} else {
	echo "Error creating table: " . mysqli_error($dbh);
}
