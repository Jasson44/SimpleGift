# SimpleGift

A lightweight PocketMine-MP plugin that allows players to gift items to other players with a simple command.

## Features

- Gift items to online players with a simple command
- Blacklist specific items from being gifted
- Fully customizable messages with color codes
- Permission-based access
- Clean and intuitive user experience

## Installation

1. Download the latest version of SimpleGift from [Poggit](https://poggit.pmmp.io/p/SimpleGift)
2. Place the `.phar` file in your server's `plugins` folder
3. Restart your server
4. Configure the plugin in the `config.yml` file (optional)

## Commands

| Command          | Description                                        | Permission            |
|------------------|----------------------------------------------------|-----------------------|
| `/gift <player>` | Gift the item in your hand to the specified player | `xeonch.command.gift` |

## Permissions

| Permission            | Description                           | Default |
|-----------------------|---------------------------------------|---------|
| `xeonch.command.gift` | Allows the use of the `/gift` command | true    |

## Configuration

The plugin is highly configurable. You can edit the `config.yml` file to customize messages, blacklist items, and more.

```yaml
# PREFIX MESSAGE
prefix: "&7[&aGIFT&7] &8»&r "

# BLACKLIST ITEM CONFIGURATION
# Items in this list cannot be gifted to other players
# Item names should match Minecraft vanilla names (case insensitive)
blacklist-item:
  - "tnt"
  - "bedrock"
  - "end_portal_frame"
  - "command_block"
  - "barrier"
  - "structure_block"
  - "structure_void"

# MESSAGE CONFIGURATION
# Use these placeholders in your messages:
# @TARGET@ - Recipient player's name
# @SENDER@ - Sender player's name

# Error messages
item:
  invalid: "&cYou need to hold an item in your hand to gift it."
  blacklist: "&cThis item is blacklisted and cannot be given as a gift."

# Player not found message
target:
  not_found: "&cPlayer &e@TARGET@&c could not be found."

# Inventory messages
inventory:
  target_full: "&cCannot give item to &e@TARGET@&c because their inventory is full."

# Success messages
gift:
  sender_success: "&aYou have successfully gifted an item to &e@TARGET@&a!"
  target_received: "&aYou received a gift from &e@SENDER@&a!"
```

## Placeholders

The following placeholders can be used in the messages:

- `@TARGET@` - The name of the player receiving the gift
- `@SENDER@` - The name of the player sending the gift

## Examples

1. Basic usage:
   ```
   /gift Steve
   ```
   This will gift the item in your hand to the player named Steve.

## Dependencies

- [Commando](https://github.com/CortexPE/Commando) - Command framework

## Support

If you encounter any issues or have suggestions for improvements, please create an issue on
the [GitHub repository](https://github.com/xeonch/SimpleGift/issues).

## License

This plugin is licensed under the MIT License. See the [LICENSE](LICENSE) file for more information.

## Credits

- Created by XeonCh (Jasson44)