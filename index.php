<?php
include_once( 'config.php' );
include_once( 'base-init.php' );
include_once( 'functions.php' );

zed1_debug(__FILE__,__LINE__);
//create_db();
$message = '';
/* import one of the files */
$massage .= import_police_file( '2013-06-avon-and-somerset-outcomes.csv');






/* the output below */
?><html>
<head>
  <title>MadLab Map</title>
</head>
<body>
<h1>MadLab Map</h1>
<p>Nothing here yet...</p>
</body>
</html>