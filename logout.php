<?php
// logout.php

session_start();
session_destroy();
header("Location: admin.php?success=" . urlencode("Вы успешно вышли из системы."));
exit;
