<?php

/*
 *  ___            __  __
 * |_ _|_ ____   _|  \/  | ___ _ __  _   _
 *  | || '_ \ \ / / |\/| |/ _ \ '_ \| | | |
 *  | || | | \ V /| |  | |  __/ | | | |_| |
 * |___|_| |_|\_/ |_|  |_|\___|_| |_|\__,_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author Muqsit
 * @link http://github.com/Muqsit
 *
*/

declare(strict_types=1);

namespace muqsit\invmenu;

use muqsit\invmenu\inventory\InvMenuInventory;
use muqsit\invmenu\metadata\MenuMetadata;
use pocketmine\Player;

class SharedInvMenu extends InvMenu{

	/** @var InvMenuInventory */
	protected $inventory;

	public function __construct(MenuMetadata $type){
		parent::__construct($type);
		$this->inventory = $this->type->createInventory();
	}

	public function getInventory() : InvMenuInventory{
		return $this->inventory;
	}

	public function getInventoryForPlayer(Player $player) : InvMenuInventory{
		return $this->getInventory();
	}
}