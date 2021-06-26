# VanillaInventory
**Enable to use Anvil and Enchantment GUI for PocketMine**


## Feature
- Enable to use Anvil and enchantment table GUI like vanilla


## How to use
Install this plugin and run your server. Place anvil or enchantment block only.


## Developer Documentation
- PlayerEnchantItemEvent

> When player tries to enchant some items, this event will be called

```php
/** @var \korado531m7\VanillaInventory\event\PlayerEnchantItemEvent $event */
// Return the player who tries to enchant
$player = $event->getPlayer();

// Return an enchanted item
$item = $event->getItem();
```

- PlayerAnvilUseEvent

Coming Soon