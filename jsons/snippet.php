<?php

// if the 'term' variable is not sent with the request, exit
if ( !isset($_REQUEST['term']) )
    exit;

require_once ('db_conn.php'); //db connection

// query the database table for zip codes that match 'term'
$query = 'Select name, description FROM  snippet where name = "'. mysql_real_escape_string($_REQUEST['term']) .'" ';
$rs = mysql_query($query, $connection);

// loop through each zipcode returned and format the response for jQuery

$data = array();

if ( $rs && mysql_num_rows($rs) )
{
    while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
    {
        $data[] = array(
            'label' => $row['name'],
            'value' => $row['description']
        );
    }
}

// jQuery wants JSON data
echo json_encode($data);
flush();