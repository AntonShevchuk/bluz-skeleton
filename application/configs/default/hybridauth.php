<?php
/**
 * Created by PhpStorm.
 * User: yuklia
 * Date: 05.05.15
 * Time: 14:04
 */

/**
 * You must define provider class inside providers scope
 */
return array(
        //"base_url" the url that point to HybridAuth Endpoint (where index.php and config.php are found)
        "base_url" => "http://sk.com/auth/endpoint",

        "providers" => array(
            // google
            "Google" => array( // 'id' is your google client id
                "enabled" => true,
                "keys" => array("id" => "", "secret" => ""),
                "provider" => 'Application\Auth\Google'
            ),

            // facebook
            "Facebook" => array( // 'id' is your facebook application id
                "enabled" => true,
                "keys" => array("id" => "1413483462306154", "secret" => "0911925061bdb04a3d8c41129f672065"),
                "scope"   => "email, user_about_me, user_birthday, user_hometown", // optional
                "provider" => 'Application\Auth\Facebook'
            ),

            // twitter
            "Twitter" => array( // 'key' is your twitter application consumer key
                "enabled" => true,
                "keys" => array("key" => "eOMvWh3ODqk4A1MHEeolm0Cfv", "secret" => "aJSrRzFlJxZp60IhTPnsOetwYVZ6XBZmcd4wQbPwyc7hgIYg0M"),
                "provider" => 'Application\Auth\Twitter'
            )
        ),

        "debug_mode" => false,

        // to enable logging, set 'debug_mode' to true, then provide here a path of a writable file
        "debug_file" => "",
    );