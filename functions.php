<?php
include_once( 'createdb.php' );

// assumes a db handle in $dbh

function create_db() {
	global $dbh;
	
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
if (mysql_query( $sql, $dbh ) )  {
  echo "Table created successfully";
} else {
  echo "Error creating table: " . mysql_error($dbh);
}

}

static function process_mal_files() {
  global $wpdb;

  //zed1_debug();
  $upload_dir = wp_upload_dir();
  $srcfilename = trailingslashit( $upload_dir['basedir'] ) . trailingslashit( self::get_option( 'mal_upload_folder' ) ) . CFTP_MAL_MAL_FILENAME;
  $dstfilename = trailingslashit( $upload_dir['basedir'] ) . trailingslashit( self::get_option( 'mal_processed_folder' ) ) . CFTP_MAL_MAL_FILENAME;

  //zed1_debug( "Looking for $srcfilename" );

  $ret = self::process_file( $srcfilename, array( __CLASS__, 'process_mal_row' ) );

  //zed1_debug("return from process_file", $ret);

  self::generate_join_records( $ret['new_ids'], 'house' );

  // move this file out of the way
  zed1_debug( "Moving to $dstfilename" );

  $t = rename( $srcfilename, $dstfilename);

  zed1_debug( "Move " . ( $t ? 'succeeded.' : 'failed.' ) );

  zed1_debug( $ret );
  return $ret;
} // end process_mal_files

static function process_file( $filepath, $callback = '', $skipfirst = false ) {
  $err_response = '';
  $all_ret = array();
  $all_ids = array();
  $new_ids = array();
  $count = 0;
  $row = 0;

  $fp = fopen( $filepath, 'r' );
  if ( $fp !== false ) {
	  while ( ($data = fgetcsv( $fp, 0, ',' ) ) !== FALSE ) {
		  $row++;
		  //zed1_debug( "row $row =", $data );

		  if ( ( 1 == $row ) && ( 'Y' == $skipfirst ) )
			  continue;

		  if ( is_callable( $callback ) ) {
			  $ret = call_user_func( $callback, $data, $row );

			  if ( isset( $ret['error'] ) ) {
				  $err_response .= '<p>' . $ret['error'] . '</p>';
			  } else if ( isset( $ret['db_error'] ) ) {
				  $err_response .= '<p>' . $ret['db_error'] . '</p>';
			  } else if ( isset( $ret['warning'] ) ) {
			  // ignore
			  } else if ( isset( $ret['ok'] ) ) {
				  $all_ids[] = $ret['id'];
				  if ( isset( $ret['new'] ) )
					  $new_ids[] = $ret['new'];
				  ++$count;
			  }
			  $all_ret[] = $ret;
		  }
	  }
	  fclose( $fp );
  }

  $retval = array();
  $retval['error']   = $err_response;
  $retval['count']   = $count;
  $retval['log']	   = $all_ret;
  $retval['ids']	   = $all_ids;
  $retval['new_ids'] = $new_ids;
  unset( $all_ret );
  unset( $all_ids );
  //error_log( "process_file returning " . var_export( $retval, true ) );
  return $retval;
} // end process_file

static function process_mal_row( $row, $line ) {
  global $wpdb;

  //zed1_debug( $row );
  $ret = array();
  //validation.
  if ( count( $row ) == 15 ) {
  //update or insert?
	  $update = false;
	  if ( $wpdb->get_var( $wpdb->prepare( 'SELECT UPRN FROM ' . $wpdb->prefix . CFTP_MAL_HOUSEHOLD_TABLE_NAME . ' WHERE UPRN = %d', intval( $row[ 0] ) ) ) ) {
		  $update = true;
	  }
	  $data						  = array();
	  $data['UPRN']				  = trim( $row[ 0] );
	  $data['RM_UDPRN']			  = intval( $row[ 1] );
	  $data['AdministrativeCounty'] = trim( $row[ 2] );
	  $data['postcode']			  = trim( $row[ 3] );
	  $data['Xcoord']				  = floatval( $row[ 4] );
	  $data['Ycoord']				  = floatval( $row[ 5] );
	  $data['dist_to_nearest_mast'] = floatval( $row[ 6] );
	  $data['bldg_class']			  = trim( $row[ 7] );
	  $data['Mast_id']			  = trim( $row[ 8] );
	  $data['pixel_id']			  = trim( $row[ 9] );
	  $data['Rollout_YYMM']		  = trim( $row[10] );
	  $data['SDI_affected']		  = intval( $row[11] );
	  $data['DIA_affected']		  = intval( $row[12] );
	  $data['Treat_As_DIA']		  = intval( $row[13] );
	  $data['TV_Region']			  = trim( $row[14] );

	  if ( $update )
		  $res = $wpdb->replace( $wpdb->prefix . CFTP_MAL_HOUSEHOLD_TABLE_NAME, $data, self::get_mal_format() );
	  else
		  $res = $wpdb->insert( $wpdb->prefix . CFTP_MAL_HOUSEHOLD_TABLE_NAME, $data, self::get_mal_format() );
		  //zed1_debug("result:", $res);

	  if ( false === $res ) {
		  if ( $wp_error ) {
			  $ret = array( 'db_error' => "Could not insert/update MAL record from line $line into the database: " . $wpdb->last_error );
		  } else {
			  $ret = array( 'db_error' => "Unknown DB error with line $line: "  . $wpdb->last_error );
		  }
	  } else {
		  if ( $update )
			  $ret = array( 'ok' => "Processed line $line. UPRN={$data['UPRN']}", 'id' => $data['UPRN'] );
		  else
			  $ret = array( 'ok' => "Processed line $line. UPRN={$data['UPRN']}", 'id' => $data['UPRN'], 'new' => $data['UPRN'] );
	  }
  } else {
	  if ( count( $row ) == 0 )
		  $ret = array( 'warning' => "Skipping empty line $line. " );
	  else
		  $ret = array( 'error' => "Not enough data in line $line. " . count( $row ) . " values detected." );
  }
  //zed1_debug("result:", $ret);
  return $ret;
} // end process_mal_row
