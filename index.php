<?php
include_once( 'config.php' );
include_once( 'base-init.php' );
include_once( 'functions.php' );

zed1_debug(__FILE__,__LINE__);
//create_db(); // create table
//create_db2(); // create table 2
$message = '';
/* import one of the files */
//$message .= import_police_file( 'new/Crime occurences2010-2013/2013-06/2013-06-avon-and-somerset-street.csv');
//$message .= import_schools_file( 'new/Primary2005_csv.csv');
//$message .= import_fe_file( 'new/Learners_Local_Authority_Distict_1112.csv');





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
	  <li><a class="content-click" href="HTML/GBR-Human-Development.html">GB Human Development</a></li>
	  <li><a class="content-click" href="HTML/GDP-comparison-data.html">GB Comparison Data</a></li>
	  <li><a class="content-click" href="HTML/Post-education-earnings.html">Post Education Earnings</a></li>
	</ul>

  </div><!-- /menu -->



  <div id="content">

	<p>select an option,...</p>
  </div><!-- /content -->

</div><!-- /wrap -->

</body>
</html>