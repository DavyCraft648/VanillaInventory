<?php

/**
 * VanillaInventory
 *
 * Copyright (c) 2021 korado531m7
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */

namespace korado531m7\VanillaInventory;

use korado531m7\VanillaInventory\block\Anvil;
use korado531m7\VanillaInventory\block\EnchantmentBlock;
use pocketmine\block\BlockFactory;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    public function onEnable(): void {
        BlockFactory::registerBlock(new Anvil(), true);
        BlockFactory::registerBlock(new EnchantmentBlock(), true);

        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
    }

}
