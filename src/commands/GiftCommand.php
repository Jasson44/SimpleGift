<?php
/**
 *MIT License
 *
 * Copyright (c) 2025 Jasson44
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
declare(strict_types=1);

namespace xeonch\gift\commands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use xeonch\gift\Main;

class GiftCommand extends BaseCommand
{
    public function prepare(): void
    {
        $this->addConstraint(new InGameRequiredConstraint($this));
        $this->registerArgument(0, new RawStringArgument("player"));
        $this->setPermission("xeonch.command.gift");
    }

    public function getPermission(): string
    {
        return "xeonch.command.gift";
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            return;
        }

        if (!isset($args["player"])) {
            $sender->sendMessage(Main::getInstance()->getPrefix() . TextFormat::RED . " USE /gift <Player;string>");
            return;
        }

        $targetName = $args["player"];
        $target = Main::getInstance()->getServer()->getPlayerByPrefix($targetName);

        if ($target === null) {
            $this->sendFormattedMessage($sender, "target.not_found", $targetName, $sender->getName());
            return;
        }

        $itemGift = $sender->getInventory()->getItemInHand();

        if ($itemGift->isNull()) {
            $sender->sendMessage(TextFormat::colorize(Main::getInstance()->getPrefix() . Main::getInstance()->getConfig()->getNested("item.invalid")));
            return;
        }

        if ($this->isItemBlacklisted($itemGift)) {
            $sender->sendMessage(TextFormat::colorize(Main::getInstance()->getPrefix() . Main::getInstance()->getConfig()->getNested("item.blacklist")));
            return;
        }

        if (!$target->getInventory()->canAddItem($itemGift)) {
            $this->sendFormattedMessage($sender, "inventory.target_full", $target->getName(), $sender->getName());
            return;
        }

        $this->giftItem($sender, $target, $itemGift, $target->getName());
    }

    private function isItemBlacklisted($item): bool
    {
        $blacklist = Main::getInstance()->getConfig()->get("blacklist-item");
        return in_array(strtolower($item->getVanillaName()), array_map('strtolower', $blacklist));
    }

    private function giftItem(Player $sender, Player $target, $item, string $targetName): void
    {
        $target->getInventory()->addItem($item);
        $sender->getInventory()->remove($item);

        $this->sendFormattedMessage($sender, "gift.sender_success", $targetName, $sender->getName());

        if ($target->isOnline()) {
            $this->sendFormattedMessage($target, "gift.target_received", $targetName, $sender->getName());
        }
    }

    private function sendFormattedMessage(Player $player, string $configKey, string $targetName, string $senderName): void
    {
        $message = Main::getInstance()->getPrefix() . Main::getInstance()->getConfig()->getNested($configKey);
        $formattedMessage = str_replace(["@TARGET@", "@SENDER@"], [$targetName, $senderName], TextFormat::colorize($message));
        $player->sendMessage($formattedMessage);
    }
}