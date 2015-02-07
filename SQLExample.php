<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="robots" content="noindex, nofollow" />
<title>Jiten's Weddings</title>
<style type="text/css">  <!-- css for the page -->

.center {
	text-align:center;
}

body,td,th {
	color: black; 
}

.larger {
	font-size:1.8em;
}

table {	
	font-size:1.8em;
	text-align:center;
	width:70%;
	margin-left:auto;
	margin-right:auto;
	text-color:#FFD6FF;
	border-style:outset;
	border-width:0.2em;
	border-color:#FF5C5C;
	background-color:#FFD6FF;
	opacity: 0.9;
}
</style>
</head>

<body>
<?php
require_once 'MDB2.php';
include "/diska/www/include/coa123-13-connect.php"; //to provide $username1,$password1

$host='co-project.lboro.ac.uk';
$dbName='coa123wdb';
$dsn = "mysql://$username1:$password1@$host/$dbName"; 

$db =& MDB2::connect($dsn); 
if(PEAR::isError($db)){ 
    die($db->getMessage());
}

$date=$_GET['date'];
$partysize=$_GET['partySize'];
$cateringGrade=$_GET['cateringGrade'];

$db->setFetchMode(MDB2_FETCHMODE_ASSOC);
$sql = "SELECT DISTINCT name, weekend_price, weekday_price, capacity, grade   /* finds available venues  */
 FROM venue, venue_booking, catering
 WHERE venue.venue_id = venue_booking.venue_id 
 AND venue.venue_id = catering.venue_id
 AND venue.venue_id NOT IN (SELECT DISTINCT venue_id FROM venue_booking WHERE date_booked = date('".$date."')) 
 AND venue.capacity >=".$partysize."
 AND catering.grade >=".$cateringGrade;
			
$res =& $db->query($sql);

if(PEAR::isError($res)){
    die($res->getMessage());
}

echo "<p class='results'> Results</p>";

echo "<table class='sql' border='1'>
	<tr>
		<th> Name</th> 	
		<th> Weekend price </th> 
		<th> Weekday price </th>
		<th> Capacity </th>
		<th> Catering grade </th>
	</tr>";

while ($tblrow = $res->fetchRow()) {

    echo " <tr>
				<td> ".$tblrow['name']."</td>
				<td> ".$tblrow['weekend_price']."</td>
				<td> ".$tblrow['weekday_price']."</td> 
				<td> ".$tblrow['capacity']."</td>
				<td> ".$tblrow['grade']."</td>
		   </tr>";
}
echo '</table>';
?>
</html>
