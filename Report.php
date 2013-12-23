<?php

/*
__PocketMine Plugin__
name=Report
version=1.0.2
author=ZacHack
class=Report
apiversion=10
*/

class Report implements Plugin{
	private $api, $message, $player;
	public function __construct(ServerAPI $api, $server = false){
		$this->api = $api;
		$this->server = ServerAPI::request();
	}
	
	public function init(){
		$this->config = new Config($this->api->plugin->configPath($this)."Reports.yml", CONFIG_YAML, array(
			"Reports" => array(),
		));
		$this->api->console->register("report", "Report a player for the owner", array($this, "cmd"));
		$this->config = $this->api->plugin->readYAML($this->api->plugin->configPath($this) ."Reports.yml");
		$this->server->api->ban->cmdWhitelist("report");
	}
	
	public function cmd($cmd, $args, $issuer, $params){
		switch($cmd){
			case "report":
				$name = $issuer->username;
				$msg = implode(" ", $args);
				$this->config['Reports'][] = array($name, $msg);
				$output = "You have made a report";
				$this->api->plugin->writeYAML($this->api->plugin->configPath($this) ."Reports.yml", $this->config);
		}
		else
		{
			$output = "Report not sent..."
		}
		return $output;
	}
	public function __destruct(){}
}
