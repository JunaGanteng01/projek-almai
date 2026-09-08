<?php
$mysqli = @new mysqli("localhost", "root", "", "almai");
if ($mysqli->connect_error) {
    die("Connect Error: " . $mysqli->connect_error);
}
$result = $mysqli->query("SHOW TABLES");
if (!$result) {
    die("Query Error: " . $mysqli->error);
}
echo "<h3>Tables in 'almai':</h3><ul>";
$count = 0;
while ($row = $result->fetch_row()) {
    echo "<li>" . htmlspecialchars($row[0]) . "</li>";
    $count++;
}
echo "</ul>";
echo "<p>Total: $count tables.</p>";
