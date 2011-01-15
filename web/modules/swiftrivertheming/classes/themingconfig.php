<?php
class ThemingConfig
{
    public static $databaseurl = 'localhost';
    public static $username = 'swiftriver';
    public static $password = 'swiftriver';
    public static $database = 'swiftmeme';

    public static $createsql = "CREATE TABLE IF NOT EXISTS theming ( theme VARCHAR(2000) ) TYPE=innodb";
}
?>