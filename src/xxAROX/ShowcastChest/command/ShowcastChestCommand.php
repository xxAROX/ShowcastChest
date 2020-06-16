<?php
/* Copyright (c) 2020 xxAROX. All rights reserved. */
namespace xxAROX\ShowcastChest\command;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use xxAROX\ShowcastChest\Main;


/**
 * Class ShowcastChestCommand
 * @package xxAROX\ShowcastChest\command
 * @author xxAROX
 * @date 16.06.2020 - 03:53
 * @project ShowcastChest
 */
class ShowcastChestCommand extends Command{
	/**
	 * ShowcastChestCommand constructor.
	 * @param string $name
	 */
	public function __construct(string $name){
		parent::__construct($name, "Show a movie.", "/{$name}", []);
		$this->setPermission("xxarox.command.showcastchest");
	}

	/**
	 * Function execute
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 * @return mixed|void
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if (!$sender instanceof Player) {
			return;
		}
		if (!$this->testPermission($sender)) {
			return;
		}
		$bool = Main::getInstance()->isCaster($sender) ? false : true;
		Main::getInstance()->setCaster($sender, $bool);
	}
}
