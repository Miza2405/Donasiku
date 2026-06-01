<?php
$db = new mysqli('localhost', 'root', '', 'db_donasiku');
if ($db->connect_error) {
    echo 'CONNECT_ERR: ' . $db->connect_error . "\n";
    exit(1);
}
$countRes = $db->query('SELECT COUNT(*) as cnt FROM programs');
if (!$countRes) {
    echo 'COUNT_QUERY_ERR: ' . $db->error . "\n";
    exit(1);
}
$count = $countRes->fetch_assoc()['cnt'];
echo "COUNT: $count\n";
$res = $db->query('SELECT id, title FROM programs ORDER BY id ASC LIMIT 50');
if (!$res) {
    echo 'QUERY_ERR: ' . $db->error . "\n";
    exit(1);
}
while ($row = $res->fetch_assoc()) {
    echo $row['id'] . ': ' . $row['title'] . "\n";
}
$db->close();
