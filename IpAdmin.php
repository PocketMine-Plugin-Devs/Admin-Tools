<?php

/*
__Pocketmine Plugin__
name=IpAdmin
description=Add admins with their ip adress
version=1.0.1
author=ZacHack
class=ipadmin
apiversion=10
*/

class ipadmin implements Plugin{
        private $api, $config, $server;
        public function __construct(ServerAPI $api, $server = false){
                $this->api = $api;
                $this->server = ServerAPI::request();
        }
        public function init(){
                $this->api->console->register("ipadmin", "<add> <username>", array($this, "cmd"));
                $this->config = new Config($this->api->plugin->configPath($this)."IpAdmins.yml", CONFIG_YAML, array(
                  "Players" => array(),
                ));
                $this->config = $this->api->plugin->readYAML($this->api->plugin->configPath($this) ."IpAdmins.yml");
                $this->api->console->alias("ipa", "ipadmin");
                $this->api->addHandler('player.connect', array($this, "connect"));
        }
        public function cmd($cmd, $params, $issuer){
            $username = $issuer->username;
            switch($params[0]){
                case "add":
                    if(!isset($params[1])){
                        $output = "Usage: /ipadmin add <playername>";
                        break;
                    }
                    $name = strtolower($params[1]);
                    $player = $this->api->player->get($name);
                    if($player instanceof Player){
                        $ip = $player->ip;
                        $this->config['Players'][] = array($name, $ip);
                        $output = "Added ".$name." as an ip admin!";
                        $this->api->plugin->writeYAML($this->api->plugin->configPath($this) ."IpAdmins.yml", $this->config);
                    }else{
                        $output = "Player doesn't exist";
                    }
                    break;
                default:
                    $output = "Usage: /ipadmin <add> <playername>";
                    break;
            }
            return $output;
        }
        public function connect($data){
            $username = $data->iusername;
            $ip = $data->ip;
            foreach($this->config['Players'] as $val){
                if(($val[0] == $username) and ($val[1] != $ip)){
                    return false;
                }
            }
        }
 
        public function __destruct(){}
}
