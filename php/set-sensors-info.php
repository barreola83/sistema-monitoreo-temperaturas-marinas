<?php
    function set_sensors_info_dropdown_menu()
    {
        require 'connection_settings.php';

        if (!$connection->connect_error)
        {
            $query_result = $connection->query("SELECT * FROM info_sensors;");

            if ($query_result->num_rows > 0)
            {
                $num_rows = $query_result->num_rows;

                while ($row = $query_result->fetch_assoc())
                {
                    if ($row["state"] == 0)
                    {
                        $state_sensor = "Desconectado";
                        $progress_bar_type = "progress-bar-danger";
                    }
                    else
                    {
                        $state_sensor = "En línea";
                        $progress_bar_type = "progress-bar-success";
                    }

                    echo
                    '
                    <li>
                        <a href="">
                            <div>
                                <p>
                                    <strong>' . $row["name"] . '</strong>
                                    <span class="pull-right text-muted"> ' . $state_sensor . '</span>
                                </p>
                                <div class="progress progress-striped active">
                                    <div class="progress-bar ' . $progress_bar_type . '" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                        <span class="sr-only"></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    ';

                    if ($num_rows > 1)
                    {
                        echo '<li class="divider"></li>';
                        $num_rows--;
                    }
                }
            }

            $connection->close();
        }
    }

    function set_sensors_info_with_panels()
    {
        require "connection_settings.php";

        if (!$connection->connect_error)
        {
            $query_result = $connection->query("SELECT * FROM info_sensors;");

            if ($query_result->num_rows > 0)
            {
                while ($row = $query_result->fetch_assoc())
                {
                    if ($row["state"] == 0)
                    {
                        $state_sensor = "Desconectado";
                        $panel_color = "panel-red";
                    }
                    else
                    {
                        $state_sensor = "En línea";
                        $panel_color = "panel-green";
                    }

                    echo
                    '
                    <div class="col-lg-3 col-md-6">
                        <div id="sensor-'. $row["ID"] . '" class="panel ' . $panel_color . '">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-tasks fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">' . $row["name"] . '</div>
                                        <div id="sensor-state-' . $row["ID"] . '">' . $state_sensor . '</div>
                                    </div>
                                </div>
                            </div>
                            <a href="">
                                <div class="panel-footer">
                                    <span class="pull-left">Ver detalles</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    ';
                }
            }

            $connection->close();
        }
    }

    function set_sensors_info_dropdown_button()
    {
        require "connection_settings.php";

        if (!$connection->connect_error)
        {
            $query_result = $connection->query("SELECT * FROM info_sensors;");

            if ($query_result->num_rows > 0)
            {
                echo
                '
                <button id ="selectButton" type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                    Seleccione el sensor <span class="caret"></span>
                </button>
                <ul class="dropdown-menu pull-right" role="menu">
                ';

                while ($row = $query_result->fetch_assoc())
                {
                    if ($row["state"] == 1)
                    {
                        echo '<li value="' . $row["ID"] . '" onclick="selectSensor(this.value)"><a>' . $row["name"] . '</a></li>';
                    }
                }

                echo '</ul>';
            }
            else
            {
                echo
                '
                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                    Sensores no disponibles
                    <span class="caret"></span>
                </button>
                ';
            }
        }
    }
?>
