<?php

/**
 * VanillaInventory
 *
 * Copyright (c) 2021 korado531m7
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */

namespace korado531m7\VanillaInventory\block;

use korado531m7\VanillaInventory\inventory\AnvilInventory;
use pocketmine\block\Anvil as BaseAnvil;
use pocketmine\item\Item;
use pocketmine\Player;

class Anvil extends BaseAnvil {

    public function onActivate(Item $item, Player $player = null): bool {
        $player->addWindow(new AnvilInventory($this));

        return true;
    }

}
