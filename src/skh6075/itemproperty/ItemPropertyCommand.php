<?php

namespace skh6075\itemproperty;

use pocketmine\command\Command;
use Closure;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\utils\Utils;

final class ItemPropertyCommand extends Command{

	private Closure $callback;

	public function __construct(string $name, string $description, string $usage, string $permission, Closure $callback) {
		parent::__construct($name, $description, $usage);
		$this->setPermission($permission);
		Utils::validateCallableSignature(function(Player $player, array $args, Item $item): void{}, $callback);
		$this->callback = $callback;
	}

	public function execute(CommandSender $player, string $label, array $args): bool{
		if(!$player instanceof Player || !$this->testPermission($player)){
			return false;
		}

		if(($item = $player->getInventory()->getItemInHand())->isNull()){
			$player->sendMessage(ItemPropertyPlus::$prefix . 'Pick up the item you want to edit.');
			return false;
		}

		($this->callback)($player, $args, $item);
		return true;
	}
}