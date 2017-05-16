<?php
    require "../connection_settings.php";

    if (!$connection->connect_error)
    {
        $sensor_id = $_REQUEST["id"];

        $query_result = $connection->query("SELECT * FROM binnacle_sensors WHERE ID_sensor=$sensor_id ORDER BY ID DESC LIMIT 1;");

        if ($query_result->num_rows > 0)
        {
            $data = $query_result->fetch_assoc();
            $xml = "";
            $xml = "<data>";
            $xml .= "<idsensor>" . $data["ID_sensor"] . "</idsensor>";
            $xml .= "<temperature>" . (string)$data["temperature"] . "</temperature>";
            $xml .= "<lightlevel>" . (string)$data["light_level"] . "</lightlevel>";
            $xml .= "<date>" . (string)$data["date"] . "</date>";
            $xml .= "<time>" . (string)$data["time"] . "</time>";
            $xml .= "</data>";

            echo $xml;
        }
        else
        {
            echo "noresults";
        }

        $connection->close();
    }
?>
