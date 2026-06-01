<?php
$db = new mysqli('localhost', 'root', '', 'db_donasiku');
if ($db->connect_error) {
    echo 'CONNECT_ERR: ' . $db->connect_error . "\n";
    exit(1);
}
$result = $db->query("DELETE FROM programs WHERE title = 'when yah'");
if ($result === false) {
    echo 'DELETE_ERR: ' . $db->error . "\n";
    exit(1);
}
echo 'Deleted rows: ' . $db->affected_rows . "\n";
$db->close();
