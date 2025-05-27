<?php
session_name("delivery_session");
session_start();
session_destroy();
header("Location: login_delivery_boy.php");
exit;
