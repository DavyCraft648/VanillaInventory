<?php

/**
 * VanillaInventory
 *
 * Copyright (c) 2021 korado531m7
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */

namespace korado531m7\VanillaInventory\inventory;

use korado531m7\VanillaInventory\DataManager;
use pocketmine\nbt\tag\IntTag;
use pocketmine\network\mcpe\protocol\FilterTextPacket;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\types\ContainerIds;
use pocketmine\network\mcpe\protocol\types\NetworkInventoryAction;
use pocketmine\network\mcpe\protocol\types\WindowTypes;
use pocketmine\Player;

class AnvilInventory extends FakeInventory {

    public function getName(): string {
        return 'AnvilInventory';
    }

    public function getDefaultSize(): int {
        return 2; //Output slot is not counted
    }

    public function getNetworkType(): int {
        return WindowTypes::ANVIL;
    }

    public function getFirstVirtualSlot(): int {
        return 1;
    }

    public function getVirtualSlots(): array {
        return [1, 2];
    }

    public function listen(Player $who, InventoryTransactionPacket $packet): void {
        if ($this->isOutputItem($packet)) {
            foreach ($packet->trData->getActions() as $action) {
                if ($action->sourceType === NetworkInventoryAction::SOURCE_CONTAINER && $action->windowId === ContainerIds::INVENTORY) {
                    $newName = DataManager::getTemporarilyText($who);
                    if ($newName !== null) {
                        $action->newItem->getItemStack()->setCustomName($newName);
                        DataManager::resetTemporarilyText($who);
                    }
                }
            }
        }
        parent::listen($who, $packet);
    }

    private function isOutputItem(InventoryTransactionPacket $packet): bool {
        foreach ($packet->trData->getActions() as $action) {
            if ($action->sourceType === NetworkInventoryAction::SOURCE_TODO && $action->windowId === -12 && $action->inventorySlot === 2) {
                if ($action->oldItem->getItemStack()->getNamedTag()->hasTag('RepairCost', IntTag::class) && $action->newItem->getItemStack()->isNull()) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function writeText(Player $player, FilterTextPacket $packet): void {
        if (DataManager::equalsTemporarilyInventory($player, self::class)) {
            DataManager::setTemporarilyText($player, $packet);
            $player->dataPacket(FilterTextPacket::create($packet->getText(), true));
        }
    }

}
