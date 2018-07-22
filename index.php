<?php
/**
 * Created by PhpStorm.
 * User: jhd235
 * Date: 7/22/18
 * Time: 4:43 AM
 */

echo "<table style='border: solid 1px black;'>";
echo "<tr><th>Id</th><th>Firstname</th><th>Lastname</th></tr>";

class TableRows extends RecursiveIteratorIterator {
	function __construct($it) {
		parent::__construct($it, self::LEAVES_ONLY);
	}

	function current() {
		return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
	}

	function beginChildren() {
		echo "<tr>";
	}

	function endChildren() {
		echo "</tr>" . "\n";
	}
}

$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "injection";
if ($_GET['r'] == 'get') {
	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $conn->prepare("SELECT userid, username, age FROM user");
		$stmt->execute();

		// set the resulting array to associative
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		foreach (new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k => $v) {
			echo $v;
		}
	} catch (PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
	$conn = null;
}
if ($_GET['r']=='create') {
	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO user (username, age)
    VALUES ('John', 25)";
		// use exec() because no results are returned
		$conn->exec($sql);
		echo "New record created successfully";
	} catch (PDOException $e) {
		echo $sql . "<br>" . $e->getMessage();
	}

	$conn = null;
}
echo "</table>";