<?php
include_once( 'config.php' );
include_once( 'base-init.php' );
include_once( 'functions.php' );

zed1_debug(__FILE__,__LINE__);
//create_db();
$message = '';
/* import one of the files */
//$message .= import_police_file( '2013-06-avon-and-somerset-outcomes.csv');






/* the output below */
?><html>
<head>
  <title>MadLab Map</title>
  <script src="jquery-1.10.2.min.js" type="text/javascript"></script>
  <script src="madlabmap.js" type="text/javascript"></script>
  <link rel="stylesheet" href="madlabmap.css?v=1">
	
</head>
<body>
<h1>MadLab Map</h1>

<!--

layout

--------------------------------------------
| menu               | map                 |
| choices            |  or                 |
| etc                | table of data       |
|                    |                     |
|                    |                     |
|                    |                     |
|                    |                     |
|                    |                     |
|                    |                     |
|                    |                     |
|                    |                     |
|                    |                     |
|                    |                     |
|                    |                     |
|                    |                     |
|                    |                     |
--------------------------------------------


-->

<div id="wrap">


  <div id="menu">

	<ul id="nav">
	  <li><a href="#">Test 1</li>
	  <li><a class="content-click" rel="gb-human-development.html" href="gb-human-development.html">GB Human Development</a></li>
	</ul>

  </div><!-- /menu -->



  <div id="content">

	<p>select an option,...</p>
  </div><!-- /content -->

</div><!-- /wrap -->

</body>
</html>