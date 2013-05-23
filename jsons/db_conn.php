<?php
 // Database Constants
    define("DB_SERVER", "localhost");
    define("DB_USER", "root");
    define("DB_PASS", "");
    define("DB_NAME", "snippet_db");

    $connection = @mysql_connect(DB_SERVER, DB_USER, DB_PASS) OR die ('Database connection failed: ' . mysql_error() );
    $db_select = @mysql_select_db(DB_NAME, $connection) OR die ("Database selection failed: " . mysql_error());
