<?php

namespace skh6075\itemproperty;

use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use Closure;

final class ItemPropertyPlus extends PluginBase{

	public static string $prefix = "§l§6[ItemProperty]§r§7 ";

	protected function onEnable() : void{
		$this->registerCommandSession("item name", "changed item name command.", "/item name [name: string]", function(Player $player, array $args, Item $item): void{
			if(trim($name = $args[0] ?? "") !== ""){
				$item->setCustomName("§r§f" . $name);
				$player->getInventory()->setItemInHand($item);
				$player->sendMessage(self::$prefix . 'Item properties were modified.');
			}else{
				$player->sendMessage(self::$prefix . '/item name [name: string]');
			}
		}, "item.property.name.permission");
		$this->registerCommandSession("item lore", "changed item lore command.", "/item lore [lore: string] [index: int|null]", function(Player $player, array $args, Item $item): void{
			$text = array_shift($args) ?? "";
			$index = array_shift($args) ?? "";
			if(trim($text) !== "" && is_numeric($index)){
				$lore = $item->getLore();
				if(count($lore) < $index){
					for($tempIndex = 0; $tempIndex < ($index - count($lore)); $tempIndex++){
						$lore[$tempIndex] = "§r ";
					}
				}
				$lore[$index] = "§r§f" . $text;
				$item->setLore($lore);
				$player->getInventory()->setItemInHand($item);
				$player->sendMessage(self::$prefix . 'Item properties were modified.');
			}else{
				$player->sendMessage(self::$prefix . '/item lore [lore: string] [index: int|null]');
			}
		}, "item.property.lore.permission");
	}

	private function registerCommandSession(string $name, string $description, string $usage, Closure $callback, string $permission): void{
		$command = new ItemPropertyCommand($name, $description, $usage, $permission, $callback);
		$this->getServer()->getCommandMap()->register(strtolower($this->getName()), $command);
	}
}