<?php
class ThemingConfig
{
    public static $databaseurl = 'localhost';
    public static $username = 'root';
    // public static $password = 'Jy76Rd';
    public static $password = '';
    public static $database = 'swiftmeme';

    public static $createsql = "CREATE TABLE IF NOT EXISTS theming ( theme VARCHAR(2000) ) TYPE=innodb";
}
?>