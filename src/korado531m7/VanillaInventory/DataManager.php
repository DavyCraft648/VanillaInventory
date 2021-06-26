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

use korado531m7\VanillaInventory\inventory\FakeInventory;
use pocketmine\network\mcpe\protocol\FilterTextPacket;
use pocketmine\Player;

class DataManager {

    /** @var FakeInventory[] */
    private static $inventories = [];
    /** @var string[] */
    private static $texts = [];

    /**
     * @param Player $player
     *
     * @return FakeInventory|null
     */
    public static function getTemporarilyInventory(Player $player): ?FakeInventory {
        return self::$inventories[$player->getRawUniqueId()] ?? null;
    }

    /**
     * @param Player $player
     * @param FakeInventory|null $inventory
     */
    public static function setTemporarilyInventory(Player $player, ?FakeInventory $inventory): void {
        self::$inventories[$player->getRawUniqueId()] = $inventory;
    }

    /**
     * @param Player $player
     * @param string $expectedInventory
     *
     * @return bool
     */
    public static function equalsTemporarilyInventory(Player $player, string $expectedInventory): bool {
        return self::getTemporarilyInventory($player) instanceof $expectedInventory;
    }

    /**
     * @param Player $player
     * @param FilterTextPacket $packet
     */
    public static function setTemporarilyText(Player $player, FilterTextPacket $packet): void {
        self::$texts[$player->getRawUniqueId()] = $packet->getText();
    }

    /**
     * @param Player $player
     *
     * @return string|null
     */
    public static function getTemporarilyText(Player $player): ?string {
        return self::$texts[$player->getRawUniqueId()] ?? null;
    }

    /**
     * @param Player $player
     */
    public static function resetTemporarilyText(Player $player): void {
        self::$texts[$player->getRawUniqueId()] = null;
    }

    /**
     * @param Player $player
     */
    public static function resetTemporarilyData(Player $player): void {
        self::$inventories[$player->getRawUniqueId()] = null;
        self::resetTemporarilyText($player);
    }

}
