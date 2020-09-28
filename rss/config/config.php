<?php

require 'dbc.php';

define("domain", "swiftcampus.com");
define("server_ip", "139.59.10.168");

function get_head()
{
	require 'head.php';
}

function get_header()
{
	require 'header.php';
}

function get_footer()
{
	require 'footer.php';
}

function recurse_copy($src, $dst)
{
	//$src = "dir1";
    $dir = opendir($src); 
    while(false !== ($file = readdir($dir) ))
	{ 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if (is_dir($src . '/' . $file)) { 
               recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir);  
}
?>