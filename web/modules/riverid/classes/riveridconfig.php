<?php
class RiverIdConfig
{
    public static $databaseurl = 'localhost';
    public static $username = 'root';
    // public static $password = 'Jy76Rd';
    public static $password = '';
    public static $database = 'swiftmeme';

    public static $createsql = "CREATE TABLE IF NOT EXISTS users ( username VARCHAR(2000), password VARCHAR(2000), role VARCHAR(2000) ) TYPE=innodb";
}