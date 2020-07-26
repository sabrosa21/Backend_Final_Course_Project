<?php
//--- Close and destroy the user session and reset the cookie session
session_start();
session_unset();
session_destroy();
session_write_close();
setcookie(session_name(), '', 0, '/');

header('Location: signin.php');
