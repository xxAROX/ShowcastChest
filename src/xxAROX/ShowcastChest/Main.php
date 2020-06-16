<?php
/* Copyright (c) 2020 xxAROX. All rights reserved. */
namespace xxAROX\ShowcastChest;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\InvMenuHandler;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\inventory\ContainerInventory;
use pocketmine\inventory\DoubleChestInventory;
use pocketmine\inventory\InventoryHolder;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\tile\Chest;
use xxAROX\LanguageAPI\LanguageAPI;
use xxAROX\ShowcastChest\command\ShowcastChestCommand;
use xxAROX\ShowcastChest\inventory\ShowcastChestInventory;


/**
 * Class Main
 * @package xxAROX\ShowcastChest
 * @author xxAROX
 * @date 16.06.2020 - 03:46
 * @project ShowcastChest
 */
class Main extends PluginBase implements Listener{
	private static $instance;
	const PREFIX = "§eStimoMC §8» §7";
	private $prefix = self::PREFIX;

	public static $casters = [];


	public function onLoad(): void{
		self::$instance = $this;
	}

	public function onEnable(): void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getServer()->getCommandMap()->registerAll(strtoupper("ShowcastChest"), [
			new ShowcastChestCommand("showcastchest"),
			new ShowcastChestCommand("showchest"),
			new ShowcastChestCommand("ssc"),
		]);
		if (!InvMenuHandler::isRegistered()) {
			InvMenuHandler::register($this);
		}
	}

	public function onDisable(): void{
	}

	public function getPrefix(): string{
		return $this->prefix;
	}

	public static function getInstance(): self{
		return self::$instance;
	}

	public function setCaster(Player $player, ?bool $value=true): void{
		if ($value) {
			self::$casters[] = $player->getName();
			LanguageAPI::sendMessage($player, "message.youAreChestCaster");
		} else {
			$key = array_search($player->getName(), self::$casters);
			unset(self::$casters[$key]);
			LanguageAPI::sendMessage($player, "message.youAreNoLongerChestCaster");
		}
	}

	public function isCaster(Player $player): bool{
		return in_array($player->getName(), self::$casters);
	}

	public function onInteractEvent(PlayerInteractEvent $event): void{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		$tile = $block->getLevel()->getTile($block->asVector3());

		if ($this->isCaster($player)) {
			$event->setCancelled(true);
			if ($tile instanceof Chest) {
				$chestInv = $tile->getInventory();

				$menu = ($chestInv instanceof DoubleChestInventory ? InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST) : InvMenu::create(InvMenu::TYPE_CHEST));
				$menu->setName("§aChest at {$block->x}:{$block->y}:{$block->z}");
				$menu->readonly();
				$menu->getInventory()->setContents($chestInv->getContents(true));
				$menu->send($player);
			}
		}
	}

	public function ChestInvFix(InventoryTransactionEvent $event): void{
		$transaction = $event->getTransaction();
		$player = $transaction->getSource();
		foreach ($transaction->getActions() as $action) {
			if ($action instanceof SlotChangeAction && $event->isCancelled()) $action->getInventory()->sendSlot($action->getSlot(), $player);
		}
	}
}
