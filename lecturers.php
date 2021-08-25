<br>
<h1>Преподаватели</h1>

<?php

$connection = pg_connect("host=localhost dbname=aud_dist_php user=dj_pg_admin password=123456");

$result = pg_query($connection,"SELECT * FROM lecturer");

$url = explode(',', getenv('REQUEST_URI'));
$prepod = pg_fetch_assoc($result)[$url[count($url)-1]];

echo "ФИО: $prepod[surname] $prepod[first_name] $prepod[patronymic]<br>";

//while ($row = pg_fetch_assoc($result)) {
//    echo "ФИО: $row[surname] $row[first_name] $row[patronymic]<br>";
//}
