<?php 
$tweets= file("database.txt");
$ayrac="#>@";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Kaydedilen Tweetler</title>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
</head>
<body>
<table>
	<tr>
		<th>#</th>
		<th>tweet id</th>
		<th>user name</th>
		<th>tweet</th>
		<th style="width: 90px;">tweet create date</th>
		<th style="width: 90px;">tweet save date</th>
		<th style="width: 250px;">not</th>
	</tr>
	<?php foreach ($tweets as $tweet) { $tweetParticle = explode($ayrac, $tweet); ?>
	<tr>
		<td><?=$tweetParticle[0];?></td>
		<td><?=$tweetParticle[4];?></td>
		<td><?=$tweetParticle[2];?></td>
		<td><?=$tweetParticle[7];?></td>
		<td><?=$tweetParticle[5];?></td>
		<td><?=$tweetParticle[6];?></td>
		<td><?=$tweetParticle[8];?></td>
	</tr>
	<?php } ?>
</table>
</body>
</html>