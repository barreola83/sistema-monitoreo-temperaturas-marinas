<?php
    require "../connection_settings.php";

    if (!$connection->connect_error)
    {
        $sensor_id = $_REQUEST["id"];

        $query_result = $connection->query("SELECT refresh_time FROM info_sensors WHERE ID=$sensor_id;");

        if ($query_result->num_rows > 0)
        {
            $refresh_time = $query_result->fetch_assoc();
            echo $refresh_time["refresh_time"];
        }

        $connection->close();
    }
?>
