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

use korado531m7\VanillaInventory\inventory\AnvilInventory;
use korado531m7\VanillaInventory\inventory\EnchantInventory;
use korado531m7\VanillaInventory\inventory\FakeInventory;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\network\mcpe\protocol\FilterTextPacket;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\PlayerActionPacket;
use pocketmine\network\mcpe\protocol\types\ContainerIds;
use pocketmine\network\mcpe\protocol\types\inventory\TransactionData;
use pocketmine\network\mcpe\protocol\types\NetworkInventoryAction;

class EventListener implements Listener {

    public function onDataPacketReceive(DataPacketReceiveEvent $event): void {
        $pk = $event->getPacket();

        switch (true) {
            case $pk instanceof ActorEventPacket:
                FakeInventory::dealXp($event->getPlayer(), $pk);
                break;

            case $pk instanceof FilterTextPacket:
                AnvilInventory::writeText($event->getPlayer(), $pk);
                break;

            case $pk instanceof InventoryTransactionPacket:
                $tmp = DataManager::getTemporarilyInventory($event->getPlayer());
                /** @var TransactionData $trData */
                $trData = $pk->trData;
                $actions = array_filter($trData->getActions(), function(NetworkInventoryAction $action) {
                    return !($action->sourceType === NetworkInventoryAction::SOURCE_CONTAINER && $action->windowId === ContainerIds::INVENTORY);
                });
                if ($tmp instanceof FakeInventory && count($actions) !== 0) {
                    $event->setCancelled();
                    $tmp->listen($event->getPlayer(), $pk);
                }
                break;

            case $pk instanceof PlayerActionPacket:
                EnchantInventory::callEvent($event->getPlayer(), $pk);
        }
    }

}
