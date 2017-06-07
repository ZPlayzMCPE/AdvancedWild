<?php
namespace Wild;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\block\Block;

use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\event\Listener;
use pocketmine\event\entity\EntityTeleportEvent;

use pocketmine\math\Vector3;

use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\level\particle\DestroyBlockParticle;

use pocketmine\utils\Config;

/**
 * Developed by TheAz928(Az928)
 * Editing or copying isn't allowed !
 * Any unauthorized copy of this 
 * file will be severely punished
 * Twitter: @TheAz928
  **/

class Main extends PluginBase implements Listener{
	
	private $max = 200;
	private $min = 200;
	private $world = null;
	public static $prefix = "§8§l[§r§6Wild§8§l]§r ";
	
	public function onEnable(){
		  $this->saveDefaultConfig();
	     $this->cfg = $this->getConfig()->getAll();
	     $this->getServer()->getPluginManager()->registerEvents($this, $this);
	     $this->getServer()->getCommandMap()->register("wild", new WildCommand($this));
	     $this->getLogger()->info("§bAdvancedWild§7 has been loaded!");
	}
	
	# @param Init data
	
	public function getData(){
	     $this->getConfig()->reload();
	     $this->cfg = $this->getConfig()->getAll();
	     $this->max = $this->cfg["max"];
	     $this->min = $this->cfg["min"];
	     $this->world = $this->getServer()->getLevelByName($this->cfg["world"]);
	return array("min" => $this->min, "max" => $this->max, "world" => $this->world);
	}
	
	# @param ToDo: Make it more futuristic 
	
	public function onTeleport(EntityTeleportEvent $event){
	     $to = $event->getTo();
	     $from = $event->getFrom();
	     if($entity instanceof Player){
		    for($i = 0; $i < 3; $i += 0.1){
		      $entity->getLevel()->addParticle(new DestroyBlockParticle(new Vector3($to->x, $to->y + $i, $to->z), Block::get(8,0)));
		      $entity->getLevel()->addParticle(new DestroyBlockParticle(new Vector3($from->x, $from->y + $i, $from->z), Block::get(8,0)));
			  }
		 }
	}
	
	# @param main function 
	
	public function sendToWild(Player $player){
		  $data = $this->getData();
		  $x = rand($data["min"], $data["max"]);
		  $z = rand($data["min"], $data["max"]);
	     $pos = new Position($x, $data["world"]->getHighestBlockAt($x, $z) + 2, $z, $data["world"]);
	     if($pos->level->getBlockIdAt($pos->x, $pos->y + 2, $pos->z) !== 0){   # make sure not spawning underground
		    $pos = new Position($x + 5, $data["world"]->getHighestBlockAt($x + 5, $z + 5) + 2, $z + 5, $data["world"]);
		  }
	     $player->sendTip(self::$prefix.$this->cfg["wild.msg"]);
	     $player->teleport($pos);
	}
}

# @param Command Class

class WildCommand extends Command{
     
	public function __construct(Main $plugin){
	     $this->plugin = $plugin;
		  parent::__construct("wild", "Go somewhere wild!", null, ["wi", "w", "jungle", "survival"]);
	}
	public function execute(CommandSender $sender, $label, array $args){
	     if($sender instanceof Player){
		    $this->plugin->sendToWild($sender);
		  }else{
		    $sender->sendMessage(Main::$prefix."§cRun this command onGame!");
		  }
	}
}
