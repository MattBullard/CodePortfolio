function characterAttacks(&$atk, &$def, &$combatText) {
		global $armor_piece_hit, $debug, $skill_object_array;
		global $destruction_spell_school;
        
		$atk_weapon_id = $playerInventory->findItemIdFromInventory($atk['inventory'], $atk['weapon_rhand']);
		$def_weapon_id = $playerInventory->findItemIdFromInventory($def['inventory'], $def['weapon_rhand']);
		
		$minCritRoll = 190;
		$maxCritRoll = 200;  

		// need to check the player's invnetory to see if they have 
		// an equipped item that modifies the crit rate.
		
		$critRollModifier = $playerInventory->getCritRollModifier($atk['inventory'], $def['inventory']);
		$crit_damage_modifier = $playerInventory->getCritDamageModifier($atk['inventory'], $def['inventory']);
		
		$maxCritRoll += $critRollModifier;
							 
		if ($atk['npc']== 1) {
			// this should increase the crit chance of NPC attacks for every 10 levels
			$minCritRoll -= floor( $def['dlevel'] / 10 ) * 10;
			if($minCritRoll < 150) { $minCritRoll = 150; }
		}

		$roll = mt_rand(1, $maxCritRoll);
		$isHit = 0;
		$isCrit = 0;
		$pick = 0;
		$totalOfSkills = 0;
		$stamCostToAttack = 2;
		$critMissThresHold = 10;
		
		// melee and ranged attacks cost 2 stamina, if the character has no stamina, 
		// the attack is a miss and they regain 1 stamina as a rest action.
		// magic attacks cost mana, and if there is no mana, the spell may not be cast
		// mana regens every turn.
		
		if ($atk['current_stam'] < $stamCostToAttack) { 
			$roll = 1;
			$combatText .= "<font color='#990099'><b>[Exhausted]</b></font>";
			$player[0]->toonStaminaChange($atk, ($stamCostToAttack * 2), 1);
		}
		$player[0]->toonStaminaChange($atk, $stamCostToAttack, 0); // reduce stamina by the cost to attack
		
		if ($roll <= $critMissThresHold) { 
			$combatText .= " <font color='#000099'><b>[Critical Miss]</b></font> "; 
			$isHit = 1;
		}
		if ($roll >= $minCritRoll) { 
			$combatText .= " <font color='#009900'><b>[Critical Hit]</b></font> "; 
			$isHit = 1; 
			$isCrit = 1;
		}
		
		$attacker_skill_level = 0;
		$defender_skill_level = 0;
		$totalOfSkills = 0;
		$pick = 0;
		
		// on a critical miss, the oppenent gets a free attack for half damage
		// on a critical hit, the agressor's damage is not reduced by armor
		
		// a hit is calculated by using the attacker's weapon skill and the defender's defensive skill
		// the skills choosen are based on the attacker's equipped weapon, if any?  
		// Brawling is the default skill to check if no weapon is equipped.
		
		// there is no skill XP gain for crit-hit and crit-miss because the 
		// skill was not used to determine the result.
		
		$spell_info = $spells->getSpellInfo( $atk['atk_spell'] );
		$brawling_skill = $skill_object_array['Brawling']->calcSkillLevel($atk, $atk['brawling']);
		$brawling_skill += ($atk['buffs']['brawling'] - $atk['debuffs']['brawling']);
		
		$manac_skill = $skill_object_array['Mana_Conversion']->calcSkillLevel($atk, $atk['mana_conversion']);
		$manac_skill += ($atk['buffs']['mana_conversion'] - $atk['debuffs']['mana_conversion']);
		
		$attack_spell_cost = $spells->getAttackSpellCost($spell_info, $manac_skill);
		
		if ($atk['attacker_attack_skill'] == "Magic_Destruction" && $atk['current_mana'] < $attack_spell_cost) {
			$def['defender_defend_skill'] = "";
			$atk['attacker_attack_skill'] = "Brawling";
			$combatText .= "<font color='#990099'><b>[Out Of Mana]</b></font>";
		} // out of mana
		
		if ($atk['attacker_attack_skill'] == "") { 
			$atk['attacker_attack_skill'] = $player[0]->findAttackSkillFromWeapon( $atk_weapon_id, $atk['attack_method'] );
		}
		$attackSkillName = $atk['attacker_attack_skill']; 
		

		if ($def['defender_defend_skill'] == "") { 
			$def['defender_defend_skill'] = $player[1]->findDefendSkillFrommAttackSkill( $attackSkillName );
		}
		$defendSkillName = $def['defender_defend_skill']; 
		
		$attackSkillNameTranslated = $this->translateSkillName($attackSkillName);
		$defendSkillNameTranslated = $this->translateSkillName($defendSkillName);
		$attack_method = $atk['attack_method'];
		
		// find, calculate and sum the two skills.
		
		$attacker_skill_level = $skill_object_array[$attackSkillName]->calcSkillLevel($atk, $atk[$attackSkillNameTranslated]);
		$attacker_skill_level += ($atk['buffs'][$attackSkillNameTranslated] - $atk['debuffs'][$attackSkillNameTranslated]);
		
		$defender_skill_level = $skill_object_array[$defendSkillName]->calcSkillLevel($def, $def[$defendSkillNameTranslated]);
		$defender_skill_level += ($def['buffs'][$defendSkillNameTranslated] - $def['debuffs'][$defendSkillNameTranslated]);
		
		$totalOfSkills = $attacker_skill_level + $defender_skill_level; 
		
		if ($isHit == 0) { // crit roll was betwwen 20 and 190
			$minXpGain = 1;
			$maxXpGain = ($atk['dlevel'] + $def['dlevel']);
			$maxSkillXpPerMatch = ($atk['dlevel'] * $def['dlevel']);
			
			// pick a random number between 1 and the skill total
			$pick = mt_rand(1, $totalOfSkills);
            
			// if the random number is equal to or less than the attack skill, it is a hit
			// if the random number is greater than the attack skill it is a miss.
			// skills that are very similar will result in a 50% change to hit.
            
			if ($pick <= $attacker_skill_level) { 
				$isHit = 1; 
                
				// gain xp only when your skill is less than opponent, and your skill usage was a success.
				// this prevents someone with a skill of 100 attacking someone with a def skill of 10 and 
				// gaining a lot of XP. But if a skill of 10 defends an attack from a skill 100, then a 
				// lot of xp should be earned as that is hard to do.
                
				if ($atk['skill_xp_gained'] < $maxSkillXpPerMatch) {
					$xp_gain = ($defender_skill_level - $attacker_skill_level) * $atk['xp_modifier'];
					if ($xp_gain < $minXpGain) { $xp_gain = $minXpGain; }
					if ($xp_gain > $maxXpGain) { $xp_gain = $maxXpGain; }
					if ($atk['skill_xp_gained'] + $xp_gain > $maxSkillXpPerMatch) { 
                    	$xp_gain = $maxSkillXpPerMatch - $atk['skill_xp_gained']; 
                    }
					if ($xp_gain > 0) {
						giveToonSkillXp($atk['toonId'], $attackSkillNameTranslated, $xp_gain);
						$atk['skill_xp_gained'] += $xp_gain;
					}
				}
			} else { 
				$isHit = 0; 
				if ($def['skill_xp_gained'] < $maxSkillXpPerMatch) {
					$xp_gain = ($attacker_skill_level - $defender_skill_level) * $def['xp_modifier'];
					if ($xp_gain < $minXpGain) { $xp_gain = $minXpGain; }
					if ($xp_gain > $maxXpGain) { $xp_gain = $maxXpGain; }
					if ($def['skill_xp_gained'] + $xp_gain > $maxSkillXpPerMatch) { 
                    	$xp_gain = $maxSkillXpPerMatch - $def['skill_xp_gained']; 
                    }
					if ($xp_gain > 0) {
						giveToonSkillXp($def['toonId'], $defendSkillNameTranslated, $xp_gain);
						$def['skill_xp_gained'] += $xp_gain;
					}
				}
			}
		} // crit roll was betwwen 20 and 190
		
		$armor_piece_hit = ""; 
		$damage_type = "";
		$weapon_info = getItemInfo( $atk_weapon_id );
		$calculated_damage = $this->calcCalculatedDamage( $weapon_info, $atk['atk_spell'], $brawling_skill, $spell_info );
		
		$playerInventory->checkEnchantsForModifiedDamage($calculated_damage, $atk['inventory'], $def['inventory']);
		
		$calculated_damage = round($calculated_damage);
		
		if ( array_key_exists('weapon_damage_type', $weapon_info) ) { 
			$damage_type = $weapon_info['weapon_damage_type'];
		} 
		if ($damage_type == "None") { 
		 	if ( array_key_exists('spell_damage_type', $spell_info) ) { 
				if ($atk['current_mana'] >= $attack_spell_cost && $spell_info['school'] == $destruction_spell_school) {
					$damage_type = $spell_info['spell_damage_type'];
					toonManaChange($atk, $attack_spell_cost, 0);
				} else {
					$damage_type = ""; // out of mana or wrong spell type
				}
			}
		}
		if ($damage_type == "") {
			$damage_type = "Bludgeoning"; // default for Brawling
		}		
		
		$target_armor_info = $item->getItemInfo( findTargetedArmorId( $def ) );
		if (array_key_exists('stats', $target_armor_info)) {
			$tmpArmorValue = $target_armor_info['stats'];
		} else {
			// this is the basic armor value of the skin
			$tmpArmorValue = 0;
			$target_armor_info['stats'] = 0;
		}
		$target_armor_info = $player[1]->adjustArmorDefByDamageType($target_armor_info, $damage_type, $attack_method);

		$def_absorbed_damage = 0;
		$attacker_absorbed_damage = 0;
		
		if ($isCrit == 0) {
			$calculated_damage -= $target_armor_info['stats'];
			$def_absorbed_damage = $target_armor_info['stats'];
			$reflected_dmg = floor( $player[1]->checkForReflectiveArmor($def_absorbed_damage, $target_armor_info) );
			if ($reflected_dmg > 0) {
				$combatText .= "<br>" . $atk['dname'] . " took damage " . $reflected_dmg . "from " . $def['dname'] . " reflective armor.</font> ";
				$player[0]->toonHealthChange($atk, $reflected_dmg, 0);
			}			
			if ($calculated_damage < 1) { $calculated_damage = 0; }		
		}
		
		if ($isHit == 1 && $isCrit == 1 ) { // critical Hit - ignores armor
			$calculated_damage *= $crit_damage_modifier;
			$calculated_damage = ceil($calculated_damage);
			
            $player[0]->toonHealthChange($def, $calculated_damage, 0);
            
			$combatText .= " hitting " . $def['dname'] . " in the " . $armor_piece_hit . " ";
			$combatText .= " for " . $calculated_damage . " unprotected " . strtolower($damage_type) . " damage.";
		} // critical Hit
        
		if ($isHit == 1 && $roll <= $critMissThresHold ) { // * Critical MISS *
			// the defending player counter attacks, but does half the normal damage.
			$brawling_skill = $skill_object_array['Brawling']->calcSkillLevel($def, $def['brawling']) + ($def['buffs']['brawling'] - $def['debuffs']['brawling']);
			
            $weapon_info = $items->getItemInfo( $def_weapon_id );
			$spell_info = $spells->getSpellInfo( $def['atk_spell'] );
            
			$calculated_damage = calcCalculatedDamage( $weapon_info, $def['atk_spell'], $brawling_skill, $spell_info );
			$player[0]->checkEnchantsForModifiedDamage($calculated_damage, $def['inventory'], $atk['inventory']);
			
			if ( array_key_exists('weapon_damage_type', $weapon_info) ) {
				$damage_type = $weapon_info['weapon_damage_type']; 
			}
			if ($damage_type == "None") { 
				if ( array_key_exists('spell_damage_type', $spell_info) )	{ 
					if ($def['current_mana'] >= $attack_spell_cost && $spell_info['school'] == $destruction_spell_school) {
						$manac_skill = $skill_object_array['Mana_Conversion']->calcSkillLevel($def, $def['mana_conversion']);
						$manac_skill += ($def['buffs']['mana_conversion'] - $def['debuffs']['mana_conversion']);
                        
						$attack_spell_cost = $spells->getAttackSpellCost($spell_info, $manac_skill);
                        
						$damage_type = $spell_info['spell_damage_type'];
						$player[1]->toonManaChange($def, $attack_spell_cost, 0);
					} else {
						$damage_type = ""; // out of mana or wrong spell type
					}
				}
			}
			if ($damage_type == "") {
				$damage_type = "Bludgeoning"; // default for Brawling
			}
			$target_armor_info = getItemInfo( findTargetedArmorId( $atk ) );
			if (!array_key_exists('stats', $target_armor_info)) {
				$target_armor_info['stats'] = 0;
			}			
			$target_armor_info = $player[0]->adjustArmorDefByDamageType($target_armor_info, $damage_type, $attack_method);
			
			$calculated_damage -= $target_armor_info['stats'];
			$calculated_damage *= 0.5;
			$calculated_damage = ceil($calculated_damage); // rounded up.
			if ($calculated_damage < 2) { $calculated_damage = 2; }	 // minimum of 2 damage for a crit-miss
		
			$player[0]->toonHealthChange($atk, $calculated_damage, 0);

			$combatText .= " - " . $atk['dname'] . " took " . $calculated_damage;
			$combatText .= " " . strtolower($damage_type) . " damage from a counter attack ";
			$combatText .= " that hit in the " . $armor_piece_hit . ". ";

		} // * Critical MISS *
		if ($isHit == 1 && $roll > $critMissThresHold && $isCrit == 0) {
			toonHealthChange($def, $calculated_damage, 0);
			if ($calculated_damage == 0) { $calculated_damage = "no"; $damage_type = ""; }
			
			$combatText .= " hitting " . $def['dname'] . " in the " . $armor_piece_hit . " ";
			$combatText .= " for " . $calculated_damage . " " . strtolower($damage_type) . " damage.";
		} 
		if ($isHit == 0) {
			$combatText .= " but missed.";
		}
		
		// now that this attack is over and XP is given, check for level up for 
		// the Atk skill, def skill, and the character level of each player.
        
		$x = $player[0]->checkForSkillLevelUp($atk['toonId'], $attackSkillNameTranslated);
		if ($x == 1) { 
			$combatText .= "<br><font color='#999900'><i>" . $atk['dname'] . " leveled up a skill. ($attackSkillNameTranslated)</i></font> ";
		}
		$x = $player[1]->checkForSkillLevelUp($def['toonId'], $defendSkillNameTranslated);
		if ($x == 1) { 
			$combatText .= "<br><font color='#999900'><i>" . $def['dname'] . " leveled up a skill. ($defendSkillNameTranslated)</i></font> ";
		}
		
		$x = $player[0]->checkForToonLevelUp($atk);
		if ($x == 1) { 
			$combatText .= "<br><font color='#999900'><i>" . $atk['dname'] . " leveled up!</i></font> ";
			$atk['dlevel']++;
		}
		$x = $player[1]->checkForToonLevelUp($def);
		if ($x == 1) { 
			$combatText .= "<br><font color='#999900'><i>" . $def['dname'] . " leveled up!</i></font> ";
			$def['dlevel']++;
		}		
		
		$combatText .= "<br>";
	}
