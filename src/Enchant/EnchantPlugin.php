<?php
declare(strict_types=1);

namespace Enchant;

use alvin0319\Jewelry\Jewelry;
use OnixUtils\OnixUtils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\item\Armor;
use pocketmine\item\Axe;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\item\Pickaxe;
use pocketmine\item\Shovel;
use pocketmine\item\Sword;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use function mt_rand;

class EnchantPlugin extends PluginBase implements Listener{

	protected function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		if($sender instanceof Player){
			switch($args[0] ?? "x"){
				case "일반":
				case "common":
					if($this->isAllow($sender->getInventory()->getItemInHand())){
						if(Jewelry::getInstance()->getJewelry($sender, Jewelry::JEWELRY_TYPE_RARE) >= 1000){
							Jewelry::getInstance()->reduceJewelry($sender, Jewelry::JEWELRY_TYPE_RARE, 1000);
							$sender->getInventory()->setItemInHand($this->commonEnchant($sender->getInventory()->getItemInHand()));
							OnixUtils::message($sender, "인챈트에 성공하였습니다.");
						}else{
							OnixUtils::message($sender, "희귀 보석이 부족합니다.");
						}
					}else{
						OnixUtils::message($sender, "인챈트가 가능한 아이템은 §d칼§f, §d삽§f, §d갑옷§f, §d도끼§f, §d곡괭이§f만 가능합니다.");
					}
					break;
				case "레어":
				case "rare":
					if($this->isAllow($sender->getInventory()->getItemInHand())){
						if(Jewelry::getInstance()->getJewelry($sender, Jewelry::JEWELRY_TYPE_UNCOMMON) >= 500){
							Jewelry::getInstance()->reduceJewelry($sender, Jewelry::JEWELRY_TYPE_UNCOMMON, 500);
							$sender->getInventory()->setItemInHand($this->rareEnchantment($sender->getInventory()->getItemInHand()));
							OnixUtils::message($sender, "인챈트에 성공하였습니다.");
						}else{
							OnixUtils::message($sender, "보통 보석이 부족합니다.");
						}
					}else{
						OnixUtils::message($sender, "인챈트가 가능한 아이템은 §d칼§f, §d삽§f, §d갑옷§f, §d도끼§f, §d곡괭이§f만 가능합니다.");
					}
					break;
				default:
					OnixUtils::message($sender, "/인챈트 일반 - 희귀 보석 1000개를 소모하여 인챈트를 합니다.");
					OnixUtils::message($sender, "/인챈트 레어 - 보통 보석 500개를 소모하여 인챈트를 합니다.");
			}
		}
		return true;
	}

	public function commonEnchant(Durable $item) : Durable{
		switch(true){
			case ($item instanceof Sword):
				$enchant = VanillaEnchantments::SHARPNESS();
				break;
			case ($item instanceof Armor):
				$enchant = VanillaEnchantments::PROTECTION();
				break;
			case ($item instanceof Axe):
			case ($item instanceof Shovel):
			case ($item instanceof Pickaxe):
				$enchant = VanillaEnchantments::EFFICIENCY();
				break;
			default:
				$enchant = VanillaEnchantments::PROTECTION();
		}

		$level = mt_rand(1, 3);

		$item->addEnchantment(new EnchantmentInstance($enchant, $level));

		return $item;
	}

	public function rareEnchantment(Durable $item) : Durable{
		switch(true){
			case ($item instanceof Sword):
				$enchant = VanillaEnchantments::SHARPNESS();
				break;
			case ($item instanceof Armor):
				$enchant = VanillaEnchantments::PROTECTION();
				break;
			case ($item instanceof Axe):
			case ($item instanceof Shovel):
			case ($item instanceof Pickaxe):
				$enchant = VanillaEnchantments::EFFICIENCY();
				break;
			default:
				$enchant = VanillaEnchantments::PROTECTION();
		}

		$level = mt_rand(4, 6);

		$item->addEnchantment(new EnchantmentInstance($enchant, $level));

		return $item;
	}

	public function isAllow(Item $item) : bool{
		return ($item instanceof Sword or $item instanceof Armor or $item instanceof Axe or $item instanceof Shovel or $item instanceof Pickaxe) and !$item->hasEnchantments();
	}
}