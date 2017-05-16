<?php
    function if_session_not_set($index, $redirect_page)
    {
        if (!isset($_SESSION[$index]))
        {
            header("Location: " . $redirect_page);
            exit();
        }
    }

    function if_session_set($index, $redirect_page)
    {
        if (isset($_SESSION[$index]))
        {
            header("Location: " . $redirect_page);
            exit();
        }
    }
?>
