<?php
$connection = pg_connect("host=localhost dbname=aud_dist_php user=dj_pg_admin password=123456");

$result = pg_query($connection,"SELECT * FROM lecturer");

while ($row = pg_fetch_row($result)) {
    echo "id: $row[0] |  ФИО: $row[2] $row[1] $row[3]\n";
}
