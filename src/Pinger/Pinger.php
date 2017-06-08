<?php
namespace Pinger;

use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerCommandPreprocessEvent;

class Pinger extends PluginBase implements Listener{

    public function onEnable(){
    	$this->getServer()->getPluginManager()->registerEvents($this, $this);

    	@mkdir($this->getDataFolder());
    
        $this->saveDefaultConfig();
        $this->getResource("config.yml");

        $this->getLogger()->info(TextFormat::YELLOW."(Os-Ping) Working...");
        $this->getLogger()->info(TextFormat::GOLD."Os.Ping: ".TextFormat::GREEN.$this->getConfig()->get("os.ping").TextFormat::GOLD.", check in config.yml if you want change it.");

    }

    public function onCommand(CommandSender $sender, Command $command, $label, array $args){

		switch($command->getName()){

            case "pinger": 
                if($sender instanceOf Player && empty($args[0])){
                	$sender->sendMessage($this->getConfig()->get("success.normal.message").TextFormat::GREEN.$this->exec($sender));

                }elseif($sender->isOp()){

                    if(!empty($args[0]) && $this->getServer()->getPlayer($args[0])){
                    	$sender->sendMessage($this->getConfig()->get("success.normal.message").TextFormat::GREEN.$this->exec($this->getServer()->getPlayer($args[0])));

                    }else{
                    	$sender->sendMessage($this->getConfig()->get("error.detect.message"));

                    }

                }else{
                	$sender->sendMessage($this->getConfig()->get("error.perm.message"));

                }

            break;  

        }

    }

    public function exec(Player $player){

        if($this->getConfig()->get("os.ping") == "LinuxOrMacOS"){
            $pinger = exec("ping -c 1"." ".$player->getAddress());

        }else{
            $pinger = exec("ping -n 1"." ".$player->getAddress());

        }
        
        $a = str_replace(" ", "", $pinger); 
        $b = explode("=", $a);
        $c = explode("/", $b[1]);

    	return $c[1];
    }

}


  