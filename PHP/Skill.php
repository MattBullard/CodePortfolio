<?php

class Skill
{
	
	private $id 			= "";
	private $name 			= "";
	private $type 			= "";
	private $formula_text	= "";
	private $desc 			= "";
	
	public function __construct($id, $name, $type, $formula_text, $desc = "")
	{
		$this->id 				= $id;
		$this->name 			= $name;
		$this->type 			= $type;
		$this->formula_text 	= $formula_text;
		$this->desc				= $desc;
		
	}
	
	public function getFormulaText()
	{
		return $this->formula_text;
	}
	public function getDesc()
	{
		return $this->desc;
	}	
	public function getName()
	{
		return $this->name;
	}
	public function getType()
	{
		return $this->type;
	}
	public function getID()
	{
		return $this->id;
	}
		
	public function calcSkillLevel($toonStats, $skillLevel)
	{
		/*
		 * This function takes an incoming toon stats array and
		 * the current skill level then calculates and return 
		 * the actual skill level based on the formula
		 * Sample Formula is: "Strength + Dexterity / 3"
		 * the skill level is added in after the formula.
		 */
		
		$tmp = array('(', ')') ;
		$formula_text = strtolower( $this->getFormulaText() );
		$formula_text = str_replace($tmp,'', $formula_text);
		
		$formula_array = explode(' ', $formula_text );

		// during combat, the toon may have buffed their basic attributes, 
		// those changes need to me checked here as well.
		if (array_key_exists('buffs', $toonStats)) {
			$toonStats['strength']     += $toonStats['buffs']['strength'];
			$toonStats['power']        += $toonStats['buffs']['power'];
			$toonStats['endurance']    += $toonStats['buffs']['endurance'];
			$toonStats['vitality']     += $toonStats['buffs']['vitality'];
			$toonStats['dexterity']    += $toonStats['buffs']['dexterity'];
			$toonStats['accuracy']     += $toonStats['buffs']['accuracy'];
			$toonStats['intelligence'] += $toonStats['buffs']['intelligence'];
			$toonStats['wisdom']       += $toonStats['buffs']['wisdom'];
		}

		$level  = 0;
		$level += $toonStats[ $formula_array[0] ] + $toonStats[ $formula_array[2] ];
		$level += $toonStats[ $formula_array[0].'_l' ] + $toonStats[ $formula_array[2].'_l' ];
		
		return floor( ( $level ) / (int)$formula_array[4] ) + $skillLevel;
	}

}

?>
