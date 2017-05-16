<?php
    require "connection_settings.php";
    require "sessions.php";

    // Iniciamos una nueva sesión para el usuario
    session_start();

    if_session_set("email", "index.html");

    // Variables para el monitoreo de errores
    $error_email = null;
    $error_password = null;
    $error_connection = null;

    // Si el usuario envió los datos por el formulario
    if (isset($_POST["submit"]))
    {
        // Verificamos que el campo de correo electrónico o el de la contraseña no sean vacíos
        $error_email = (!$_POST["email"]) ? "Ingrese un correo electrónico" : null;
        $error_password = (!$_POST["password"]) ? "Ingrese una contraseña" : null;

        // Si ninguno de los dos campos son vacíos entonces procedemos a verificar al usuario
        if (!$error_email && !$error_password)
        {
            // Verificamos si no hubo algun error al conectarse al servidor
            if (!$connection->connect_error)
            {
                // Obtenemos el correo electrónico y la contraseña
                $email = mysqli_real_escape_string($connection, $_POST["email"]);
                $password = sha1(mysqli_real_escape_string($connection, $_POST["password"]));

                // Realizamos la consulta a la base de datos
                $query_result = $connection->query("SELECT * FROM users WHERE email='$email' AND password='$password';");

                if ($query_result->num_rows > 0)
                {
                    $user_info = $query_result->fetch_assoc();
                    $_SESSION["email"] = $user_info["email"];
                    $connection->close();
                    header("Location: ../pages/index.html");
                    exit();
                }
                else
                {
                    $error_connection = "Correo electrónico o contraseña incorrectos";
                }

                $connection->close();
            }
        }
    }
?>
