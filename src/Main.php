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

namespace xeonch\gift;

use CortexPE\Commando\PacketHooker;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\TextFormat;
use xeonch\gift\commands\GiftCommand;

class Main extends PluginBase
{

    use SingletonTrait;

    public function onEnable(): void
    {
        self::setInstance($this);
        $this->saveDefaultConfig();
        $this->loadVirions();
        $this->getServer()->getCommandMap()->register("gift", new GiftCommand($this, "gift", "Gift the item in your hand to the specified player "));
    }

    public function getPrefix(): ?string
    {
        return TextFormat::colorize($this->getConfig()->get("prefix")) ?? TextFormat::GREEN . "GIFT";
    }

    protected function loadVirions(): void
    {
        if (!class_exists(PacketHooker::class)) {
            $this->getLogger()->error("Commando virion not found. Please download SimpleGift from Poggit-CI or use DEVirion (not recommended).");
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }

        if (!PacketHooker::isRegistered()) {
            PacketHooker::register($this);
        }
    }
}
