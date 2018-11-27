<?php
class DatabaseSettings
{
    var $settings;

    function getSettings()
    {
        $settings['dbhost'] = "localhost";
        $settings['dbname'] = "scouting";
        $settings['dbusername'] = "scoutingapp";
        $settings['dbpassword'] = "team3489";

        return $settings;
    }
}
