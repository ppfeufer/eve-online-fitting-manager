<?php

namespace WordPress\Plugin\EveOnlineFittingManager\Helper;

use WordPress\Plugin\EveOnlineFittingManager;

\defined('ABSPATH') or die();

class FittingHelper {
	/**
	 * Getting High Slot Item Names
	 *
	 * @param string $highSlots
	 * @return array
	 */
	public static function getHighSlotItemNames($highSlots) {
		$slots = \unserialize($highSlots);
		$arrayHighSlot = array(
			'highSlot_1_itemName' => (!empty($slots['highSlot_1'])) ? self::getItemNameById($slots['highSlot_1']) : null,
			'highSlot_2_itemName' => (!empty($slots['highSlot_2'])) ? self::getItemNameById($slots['highSlot_2']) : null,
			'highSlot_3_itemName' => (!empty($slots['highSlot_3'])) ? self::getItemNameById($slots['highSlot_3']) : null,
			'highSlot_4_itemName' => (!empty($slots['highSlot_4'])) ? self::getItemNameById($slots['highSlot_4']) : null,
			'highSlot_5_itemName' => (!empty($slots['highSlot_5'])) ? self::getItemNameById($slots['highSlot_5']) : null,
			'highSlot_6_itemName' => (!empty($slots['highSlot_6'])) ? self::getItemNameById($slots['highSlot_6']) : null,
			'highSlot_7_itemName' => (!empty($slots['highSlot_7'])) ? self::getItemNameById($slots['highSlot_7']) : null,
			'highSlot_8_itemName' => (!empty($slots['highSlot_8'])) ? self::getItemNameById($slots['highSlot_8']) : null,
		);

		return $arrayHighSlot;
	}

	/**
	 * Getting Mid Slot Item Names
	 *
	 * @param string $midSlots
	 * @return array
	 */
	public static function getMidSlotItemNames($midSlots) {
		$slots = \unserialize($midSlots);

		$arrayMidSlot = array(
			'midSlot_1_itemName' => (!empty($slots['midSlot_1'])) ? self::getItemNameById($slots['midSlot_1']) : null,
			'midSlot_2_itemName' => (!empty($slots['midSlot_2'])) ? self::getItemNameById($slots['midSlot_2']) : null,
			'midSlot_3_itemName' => (!empty($slots['midSlot_3'])) ? self::getItemNameById($slots['midSlot_3']) : null,
			'midSlot_4_itemName' => (!empty($slots['midSlot_4'])) ? self::getItemNameById($slots['midSlot_4']) : null,
			'midSlot_5_itemName' => (!empty($slots['midSlot_5'])) ? self::getItemNameById($slots['midSlot_5']) : null,
			'midSlot_6_itemName' => (!empty($slots['midSlot_6'])) ? self::getItemNameById($slots['midSlot_6']) : null,
			'midSlot_7_itemName' => (!empty($slots['midSlot_7'])) ? self::getItemNameById($slots['midSlot_7']) : null,
			'midSlot_8_itemName' => (!empty($slots['midSlot_8'])) ? self::getItemNameById($slots['midSlot_8']) : null,
		);

		return $arrayMidSlot;
	}

	/**
	 * Getting Low Slot Item Names
	 *
	 * @param string $lowSlots
	 * @return srray
	 */
	public static function getLowSlotItemNames($lowSlots) {
		$slots = \unserialize($lowSlots);

		$arrayLowSlot = array(
			'lowSlot_1_itemName' => (!empty($slots['lowSlot_1'])) ? self::getItemNameById($slots['lowSlot_1']) : null,
			'lowSlot_2_itemName' => (!empty($slots['lowSlot_2'])) ? self::getItemNameById($slots['lowSlot_2']) : null,
			'lowSlot_3_itemName' => (!empty($slots['lowSlot_3'])) ? self::getItemNameById($slots['lowSlot_3']) : null,
			'lowSlot_4_itemName' => (!empty($slots['lowSlot_4'])) ? self::getItemNameById($slots['lowSlot_4']) : null,
			'lowSlot_5_itemName' => (!empty($slots['lowSlot_5'])) ? self::getItemNameById($slots['lowSlot_5']) : null,
			'lowSlot_6_itemName' => (!empty($slots['lowSlot_6'])) ? self::getItemNameById($slots['lowSlot_6']) : null,
			'lowSlot_7_itemName' => (!empty($slots['lowSlot_7'])) ? self::getItemNameById($slots['lowSlot_7']) : null,
			'lowSlot_8_itemName' => (!empty($slots['lowSlot_8'])) ? self::getItemNameById($slots['lowSlot_8']) : null,
		);

		return $arrayLowSlot;
	}

	/**
	 * Getting Rog Slot Item Names
	 *
	 * @param string $rigSlots
	 * @return array
	 */
	public static function getRigSlotItemNames($rigSlots) {
		$rigs = \unserialize($rigSlots);

		$arrayRigSlot = array(
			'rigSlot_1_itemName' => (!empty($rigs['rigSlot_1'])) ? self::getItemNameById($rigs['rigSlot_1']) : null,
			'rigSlot_2_itemName' => (!empty($rigs['rigSlot_2'])) ? self::getItemNameById($rigs['rigSlot_2']) : null,
			'rigSlot_3_itemName' => (!empty($rigs['rigSlot_3'])) ? self::getItemNameById($rigs['rigSlot_3']) : null,
		);

		return $arrayRigSlot;
	}

	/**
	 * Getting Subsystem Item Names
	 *
	 * @param string $subSystems
	 * @return array
	 */
	public static function getSubSystemItemNames($subSystems) {
		$sub = \unserialize($subSystems);

		$arraySubSystems = array(
			'subSystem_1_itemName' => (!empty($sub['subSystem_1'])) ? self::getItemNameById($sub['subSystem_1']) : null,
			'subSystem_2_itemName' => (!empty($sub['subSystem_2'])) ? self::getItemNameById($sub['subSystem_2']) : null,
			'subSystem_3_itemName' => (!empty($sub['subSystem_3'])) ? self::getItemNameById($sub['subSystem_3']) : null,
			'subSystem_4_itemName' => (!empty($sub['subSystem_4'])) ? self::getItemNameById($sub['subSystem_4']) : null,
		);

		return $arraySubSystems;
	} // END public static function getSubSystemItemNames($subSystems)

	/**
	 * Getting Item Description
	 *
	 * @param string $itemID
	 * @return array
	 */
	public static function getItemDescription($itemID) {
		$sql = EveOnlineFittingManager\Libs\Database::getInstance()->db->prepare('SELECT `description` FROM `kb3_invtypes` WHERE `typeID` = %d', array($itemID));
		$description = EveOnlineFittingManager\Libs\Database::getInstance()->db->get_var($sql);
//		$description = \preg_replace('/<br>/', '##break##', $description);
//		$description = \strip_tags($description);
//		$description = \preg_replace('/##break##/', '<br>', $description);

		return \nl2br($description);
	} // END public static function getItemDescription($itemID)

	/**
	 * Getting Item Details
	 *
	 * @param string $itemName
	 * @param int $itemCount
	 * @return boolean
	 */
	public static function getItemDetailsByItemName($itemName, $itemCount = 1) {
		$sql = EveOnlineFittingManager\Libs\Database::getInstance()->db->prepare('SELECT
					it.typeID AS itemID,
					it.groupID AS groupID,
					it.typeName AS itemName,
					it.description AS itemDescription,
					itt.itt_cat AS categoryID,
					e.displayName AS slotName,
					e.effectId AS slotEffectID
				FROM kb3_invtypes it
				LEFT JOIN kb3_dgmtypeeffects te ON te.typeID = it.typeID
				AND te.effectID IN (
					12, -- needs high slot
					13, -- needs med slot
					11, -- needs lot slow
					2663, -- needs rig slot
					3772, -- needs subsystem slot
					6306 -- needs service slot
				)
				INNER JOIN kb3_dgmeffects e ON e.effectID = te.effectID
				INNER JOIN kb3_item_types itt ON itt.itt_id = it.groupID
				WHERE it.typeName = %s
				AND itt.itt_id = it.groupID;', array($itemName));
		$itemData = EveOnlineFittingManager\Libs\Database::getInstance()->db->get_results($sql, \OBJECT);

		/**
		 * If we don't have any result here, we might have an item that cannot be fitted.
		 * So do another check without restricting to slots.
		 */
		if(!$itemData) {
			$sql = EveOnlineFittingManager\Libs\Database::getInstance()->db->prepare('SELECT
						`kb3_invtypes`.`typeID` AS `itemID`,
						`kb3_invtypes`.`groupID` AS `groupID`,
						`kb3_invtypes`.`typeName` AS `itemName`,
						`kb3_invtypes`.`description` AS `itemDescription`,
						`kb3_item_types`.`itt_slot` AS `slotID`,
						`kb3_item_types`.`itt_cat` AS `categoryID`,
						`kb3_item_locations`.`itl_flagName` AS `slotName`
					FROM `kb3_invtypes`, `kb3_item_types`, `kb3_item_locations`
					WHERE `typeName` = %s
					AND `kb3_item_types`.`itt_id` = `kb3_invtypes`.`groupID`
					AND `kb3_item_locations`.`itl_flagID` = `kb3_item_types`.`itt_slot`;', array($itemName));
			$itemData = EveOnlineFittingManager\Libs\Database::getInstance()->db->get_results($sql, \OBJECT);
		} // END if(!$itemData)

		if($itemData) {
			$itemData = $itemData['0'];

			if(!empty($itemData->itemID)) {
				/**
				 * Category: Ships
				 */
				if($itemData->categoryID == '6') {
					$itemData->slotName = 'ship';
				} // END if($itemData->categoryID == '6')

				/**
				 * Category: Charges
				 */
				if($itemData->categoryID == '8') {
					$itemData->slotName = 'charge';
				} // END if($itemData->categoryID == '6')

				/**
				 * Category: Fuel
				 */
				$arrayFuelIDs = array(
					'16273',	// Liquid Ozone
					'16274',	// Helium Isotopes
					'17889',	// Hydrogen Isotopes
					'17887',	// Oxygen Isotopes
					'17888',	// Nitrogen Isotopes
					'16272',	// Heavy Water
					'16275'		// Strontuim Clathrates
				);
				if(\in_array($itemData->itemID, $arrayFuelIDs)) {
					$itemData->slotName = 'fuel';
				}

				/**
				 * Category: Dones
				 */
				if($itemData->categoryID == '18') {
					$itemData->slotName = 'drone';
				} // END if($itemData->categoryID == '6')


				if($itemCount != null) {
					$itemData->itemCount = $itemCount;
				} // END if($itemCount != null)

				return $itemData;
			} // END if(!empty($itemData['itemID']))
		}

		return false;
	} // END public function getItemDetailsByItemName($itemName)

	/**
	 * Getting Items Data
	 *
	 * @param string $itemName
	 * @return boolean
	 */
	public static function getItems($itemName) {
		$sql = EveOnlineFittingManager\Libs\Database::getInstance()->db->prepare('SELECT `kb3_invtypes`.`typeName` AS `itemName` FROM `kb3_invtypes` WHERE `typeName` LIKE %s', array('%' . $itemName . '%'));
		$itemData = EveOnlineFittingManager\Libs\Database::getInstance()->db->get_results($sql, \OBJECT);

		if($itemData) {
			foreach($itemData as $item) {
				$items[] = $item['itemName'];
			}

			return $items;
		}

		return false;
	} // END public function getItemDetailsByItemName($itemName)

	/**
	 * Getting an item ID ny its item name
	 *
	 * @param string $itemName
	 * @return type
	 */
	public static function getItemIdByName($itemName) {
		$returnValue = null;

		if(!empty($itemName)) {
			$sql = EveOnlineFittingManager\Libs\Database::getInstance()->db->prepare('SELECT `kb3_invtypes`.`typeID` AS `itemID` from `kb3_invtypes` WHERE `kb3_invtypes`.`typeName` = %s', array($itemName));
			$returnValue = EveOnlineFittingManager\Libs\Database::getInstance()->db->get_var($sql);
		}

		return $returnValue;
	} // END public function getItemIdByName($itemName)

	/**
	 * Getting an item name by its iten ID
	 *
	 * @param int $itemID
	 * @return type
	 */
	public static function getItemNameById($itemID) {
		if(\is_array($itemID)) {
			$itemNames = array();

			foreach($itemID as $id) {
				$sql = EveOnlineFittingManager\Libs\Database::getInstance()->db->prepare('SELECT `kb3_invtypes`.`typeName` AS `itemName` from `kb3_invtypes` WHERE `kb3_invtypes`.`typeID` = %d', array($id));
				$itemNames[$id] = EveOnlineFittingManager\Libs\Database::getInstance()->db->get_var($sql);
			} // END foreach($itemID as $id)

			return $itemNames;
		} // END if(is_array($itemID))

		$sql = EveOnlineFittingManager\Libs\Database::getInstance()->db->prepare('SELECT `kb3_invtypes`.`typeName` AS `itemName` from `kb3_invtypes` WHERE `kb3_invtypes`.`typeID` = %d', array($itemID));
		$itemName = EveOnlineFittingManager\Libs\Database::getInstance()->db->get_var($sql);

		return $itemName;
	} // END public function getItemNameById($itemID)

	public static function getDescriptionItemDetailsByItemName($itemName) {
		$itemDetails = self::getItemDetailsByItemName($itemName);

		return $itemDetails;
	} // END public function getDescriptionItemDetailsByItemName($itemName)

	/**
	 * Getting a fitting DNA from its fitting data
	 *
	 * @param object $fittingData
	 * @return string
	 */
	public static function getShipDnaFromFittingData($fittingData) {
		$highSlots = array();
		$midSlots = array();
		$lowSlots = array();
		$subSystems = array();
		$rigSlots = array();
		$shipData = array();
		$charges = array();
		$drones = array();

		foreach($fittingData as $data) {
			switch($data->slotName) {
				case 'ship':
					$shipData = $data;
					break;

				case 'LoSlot':
				case 'Low power':
					$lowSlots[] = $data;
					break;
				case 'MedSlot':
				case 'Medium power':
					$midSlots[] = $data;
					break;

				case 'HiSlot':
				case 'High power':
					$highSlots[] = $data;
					break;

				case 'RigSlot':
				case 'Rig Slot':
					$rigSlots[] = $data;
					break;

				case 'SubSystem':
				case 'Sub System':
					$subSystems[] = $data;
					break;

				case 'charge':
					$charges[] = $data;
					break;

				case 'drone':
					$drones[] = $data;
					break;
			} // END switch($fittingData['slotName'])
		} // END foreach($fittingData as $data)

		$shipDnaString = '';
		$shipDnaString .= $shipData->itemID . ':';

		/**
		 * Subsystems, if its a T3 Tactical Cruiser
		 */
		if(!empty($subSystems)) {
			foreach($subSystems as $sub) {
				$shipDnaString .= $sub->itemID . ';' . $sub->itemCount . ':';
			} // END foreach($subSystems as $sub)
		} // END if(!empty($subSystems))

		/**
		 * Highslots
		 */
		if(!empty($highSlots)) {
			foreach($highSlots as $high) {
				$shipDnaString .= $high->itemID . ';' . $high->itemCount . ':';
			} // END foreach($highSlots as $high)
		} // END if(!empty($highSlots))

		/**
		 * Medslots
		 */
		if(!empty($midSlots)) {
			foreach($midSlots as $mid) {
				$shipDnaString .= $mid->itemID . ';' . $mid->itemCount . ':';
			} // END foreach($midSlots as $mid)
		} // END if(!empty($midSlots))

		/**
		 * Lowslots
		 */
		if(!empty($lowSlots)) {
			foreach($lowSlots as $low) {
				$shipDnaString .= $low->itemID . ';' . $low->itemCount . ':';
			} // END foreach($lowSlots as $low)
		} // END if(!empty($lowSlots))

		/**
		 * Rigs
		 */
		if(!empty($rigSlots)) {
			foreach($rigSlots as $rig) {
				$shipDnaString .= $rig->itemID . ';' . $rig->itemCount . ':';
			} // END foreach($rigSlots as $rig)
		} // END if(!empty($rigSlots))

		/**
		 * Charges
		 */
		if(!empty($charges)) {
			foreach($charges as $charge) {
				$shipDnaString .= $charge->itemID . ';' . $charge->itemCount . ':';
			} // END foreach($charges as $rig)
		} // END if(!empty($charges))

		/**
		 * Drones
		 */
		if(!empty($drones)) {
			foreach($drones as $drone) {
				$shipDnaString .= $drone->itemID . ';' . $drone->itemCount . ':';
			} // END foreach($drones as $rig)
		} // END if(!empty($drones))

		$shipDnaString .= ':';

		return $shipDnaString;
	} // END public function getShipDnaFromFittingData($fittingData)

	/**
	 * Getting the slot layout from fitting data
	 *
	 * @param array $fitting
	 * @return type
	 */
	public static function getSlotLayoutFromFittingArray($fitting) {
		$currentHighSlots = self::getHighSlotCountForShipID($fitting['shipID']);
		$currenMidSlots = self::getMidSlotCountForShipID($fitting['shipID']);
		$currentLowSlots = self::getLowSlotCountForShipID($fitting['shipID']);
		$currentRigSlots = self::getRigSlotCountForShipID($fitting['shipID']);
		$currentSubSystems = 0;
		$currentServiceSlots = 0;

		$fittedSubSystems = \unserialize($fitting['subSystems']);
		$fittedServiceSlots = \unserialize($fitting['serviceSlots']);

		$arrayStrategicCruiserIDs = self::getStrategicCruiserIds();
		$arrayUpwellStructureIDs = self::getUpwellStructureIds();

		/**
		 * Check if:
		 *	» it's a strategic cruiser and has sub systems
		 *	» it's an Upwell Structure
		 */
		if(\in_array($fitting['shipID'], $arrayStrategicCruiserIDs) && !empty($fittedSubSystems)) {
			/**
			 * Processing Strategic Cruiser
			 * Those ships have their slot layout from their subsystem modifiers
			 */
			$maxSubSystems = 4;

			for($i = 1; $i <= $maxSubSystems; $i++) {
				if(isset($fittedSubSystems['subSystem_' . $i])) {
					$currentHighSlots += self::getHighSlotModifierCountForShipID($fittedSubSystems['subSystem_' . $i]);
					$currenMidSlots += self::getMidSlotModifierCountForShipID($fittedSubSystems['subSystem_' . $i]);
					$currentLowSlots += self::getLowSlotModifierCountForShipID($fittedSubSystems['subSystem_' . $i]);
				} // END if(isset($fittedSubSystems['subSystem_' . $i]))
			} // END for($i = 1; $i <= $maxSubSystems; $i++)

			$currentRigSlots = self::getRigSlotCountForShipID($fitting['shipID']);
			$currentSubSystems = 5;
		} elseif(\in_array($fitting['shipID'], $arrayUpwellStructureIDs) && !empty($fittedServiceSlots)) {
			/**
			 * Processing Upwell Structures
			 */
			$currentServiceSlots = 5;
		} // END if(in_array($fitting['shipID'], $arrayStrategicCruiserIDs))

		return array(
			'highSlots' => $currentHighSlots,
			'midSlots' => $currenMidSlots,
			'lowSlots' => $currentLowSlots,
			'rigSlots' => $currentRigSlots,
			'subSystems' => $currentSubSystems,
			'serviceSlots' => $currentServiceSlots
		);
	} // END public function getSlotLayoutFromFittingArray($fitting)

	private static function getStrategicCruiserIds() {
		return array(
			29984, // Tengu
			29986, // Legion
			29988, // Proteus
			29990 // Loki
		);
	}

	private static function getUpwellStructureIds() {
		return array(
			35832, // Astrahus
			35833, // Fortizar
			35834, // Keepstar
			35825, // Raitaru
			35826, // Azbel
			35827 // Sotiyo
		);
	}

	/**
	 * Getting the count of high slots of a given ship by its ID
	 *
	 * @param int $shipID
	 * @return int
	 */
	public static function getHighSlotCountForShipID($shipID) {
		$sql = EveOnlineFittingManager\Libs\Database::getInstance()->db->prepare('SELECT `value`
				FROM `kb3_dgmtypeattributes`
				JOIN `kb3_dgmattributetypes` ON `attributeName` = "hiSlots"
				WHERE `kb3_dgmattributetypes`.`attributeID` = `kb3_dgmtypeattributes`.`attributeID`
				AND `kb3_dgmtypeattributes`.`typeID` = %d', array($shipID));

		return EveOnlineFittingManager\Libs\Database::getInstance()->db->get_var($sql);
	} // END public function getHighSlotCountForShipID($shipID)

	/**
	 * Getting the count of mid slots of a given ship by its ID
	 *
	 * @param int $shipID
	 * @return int
	 */
	public static function getMidSlotCountForShipID($shipID) {
		$sql = EveOnlineFittingManager\Libs\Database::getInstance()->db->prepare('SELECT `value`
				FROM `kb3_dgmtypeattributes`
				JOIN `kb3_dgmattributetypes` ON `attributeName` = "medSlots"
				WHERE `kb3_dgmattributetypes`.`attributeID` = `kb3_dgmtypeattributes`.`attributeID`
				AND `kb3_dgmtypeattributes`.`typeID` = %d', array($shipID));

		return EveOnlineFittingManager\Libs\Database::getInstance()->db->get_var($sql);
	} // END public function getMidSlotCountForShipID($shipID)

	/**
	 * Getting the count of low slots of a given ship by its ID
	 *
	 * @param int $shipID
	 * @return int
	 */
	public static function getLowSlotCountForShipID($shipID) {
		$sql = EveOnlineFittingManager\Libs\Database::getInstance()->db->prepare('SELECT `value`
				FROM `kb3_dgmtypeattributes`
				JOIN `kb3_dgmattributetypes` ON `attributeName` = "lowSlots"
				WHERE `kb3_dgmattributetypes`.`attributeID` = `kb3_dgmtypeattributes`.`attributeID`
				AND `kb3_dgmtypeattributes`.`typeID` = %d', array($shipID));

		return EveOnlineFittingManager\Libs\Database::getInstance()->db->get_var($sql);
	} // END public function getLowSlotCountForShipID($shipID)

	/**
	 * Getting the amount of high slots modified by a subsystem
	 *
	 * @param int $subsystemID
	 * @return int
	 */
	public static function getHighSlotModifierCountForShipID($subsystemID) {
		$sql = EveOnlineFittingManager\Libs\Database::getInstance()->db->prepare('SELECT `value`
				FROM `kb3_dgmtypeattributes`
				JOIN `kb3_dgmattributetypes` ON `attributeName` = "hiSlotModifier"
				WHERE `kb3_dgmattributetypes`.`attributeID` = `kb3_dgmtypeattributes`.`attributeID`
				AND `kb3_dgmtypeattributes`.`typeID` = %d', array($subsystemID));

		return EveOnlineFittingManager\Libs\Database::getInstance()->db->get_var($sql);
	} // END public function getHighSlotModifierCountForShipID($subsystemID)

	/**
	 * Getting the amount of mid slots modified by a subsystem
	 *
	 * @param int $subsystemID
	 * @return int
	 */
	public static function getMidSlotModifierCountForShipID($subsystemID) {
		$sql = EveOnlineFittingManager\Libs\Database::getInstance()->db->prepare('SELECT `value`
				FROM `kb3_dgmtypeattributes`
				JOIN `kb3_dgmattributetypes` ON `attributeName` = "medSlotModifier"
				WHERE `kb3_dgmattributetypes`.`attributeID` = `kb3_dgmtypeattributes`.`attributeID`
				AND `kb3_dgmtypeattributes`.`typeID` = %d', array($subsystemID));

		return EveOnlineFittingManager\Libs\Database::getInstance()->db->get_var($sql);
	} // END public function getMidSlotModifierCountForShipID($subsystemID)

	/**
	 * Getting the amount of low slots modified by a subsystem
	 *
	 * @param int $subsystemID
	 * @return int
	 */
	public static function getLowSlotModifierCountForShipID($subsystemID) {
		$sql = EveOnlineFittingManager\Libs\Database::getInstance()->db->prepare('SELECT `value`
				FROM `kb3_dgmtypeattributes`
				JOIN `kb3_dgmattributetypes` ON `attributeName` = "lowSlotModifier"
				WHERE `kb3_dgmattributetypes`.`attributeID` = `kb3_dgmtypeattributes`.`attributeID`
				AND `kb3_dgmtypeattributes`.`typeID` = %d', array($subsystemID));

		return EveOnlineFittingManager\Libs\Database::getInstance()->db->get_var($sql);
	} // END public function getLowSlotModifierCountForShipID($subsystemID)

	/**
	 * Getting the count of rig slots of a given ship by its ID
	 *
	 * @param int $shipID
	 * @return int
	 */
	public static function getRigSlotCountForShipID($shipID) {
		$sql = EveOnlineFittingManager\Libs\Database::getInstance()->db->prepare('SELECT `value`
				FROM `kb3_dgmtypeattributes`
				JOIN `kb3_dgmattributetypes` ON `attributeName` = "rigSlots"
				WHERE `kb3_dgmattributetypes`.`attributeID` = `kb3_dgmtypeattributes`.`attributeID`
				AND `kb3_dgmtypeattributes`.`typeID` = %d', array($shipID));

		return EveOnlineFittingManager\Libs\Database::getInstance()->db->get_var($sql);
	} // END public function getRigSlotCountForShipID($shipID)

	/**
	 * Getting the ship image by ID
	 *
	 * @param type $itemID
	 * @param type $size
	 * @return type
	 */
	public static function getShipImageById($itemID = null, $size = 512) {
		$image = ImageHelper::getLocalCacheImageUriForRemoteImage('ship', EveApiHelper::getInstance()->getImageServerUrl('inventory') . $itemID . '_' . $size . '.png');

		return $image;
	} // END public static function getShipImageById($itemID = null, $size = 512)

	/**
	 * Gettng the doctrine menu for the sidebar
	 *
	 * @param string $taxonomy
	 * @return string
	 */
	public static function getSidebarMenu($taxonomy) {
		$entityListHtml = '<ul class="sidebar-doctrine-list doctrine-list menu-' . $taxonomy . '">';

		// get all taxonomy terms
		$entities = \get_terms(array(
			'taxonomy' => $taxonomy,
			'orderby' => 'name',
			'order' => 'ASC'
		));

		// get terms that have children
		$hierarchy = \_get_term_hierarchy($taxonomy);

		// Loop through every entity
		foreach($entities as $entity) {
			// skip term if it has children or is empty
			if($entity->parent) {
				continue;
			}

			$doctrineListHtml = '<li class="doctrine entity-' . $entity->slug . ' doctrine-id-' . $entity->term_id . '" data-doctrine="' . $entity->slug . '"><a class="doctrine-link-item" href="' . \get_term_link($entity->term_id) . '">' . $entity->name . '</a></li>';

			// If the entity has doctrines...
			if(isset($hierarchy[$entity->term_id])) {
				$doctrines = \get_terms(array(
					'taxonomy' => $taxonomy,
					'orderby' => 'name',
					'order' => 'ASC',
					'child_of' => $entity->term_id
				));

				$doctrineListHtml = '<li class="doctrine entity-' . $entity->slug . ' doctrine-id-' . $entity->term_id . ' has-children" data-doctrine="' . $entity->slug . '"><a class="doctrine-link-item" href="' . \get_term_link($entity->term_id) . '">' . $entity->name . '</a><span class="caret dropdown-toggle" data-toggle="dropdown"><i></i></span>';
				$doctrineListHtml .= '<ul class="dropdown-menu child-doctrine-list">';

				foreach($doctrines as $doctrine) {
					if($doctrine->parent && $doctrine->parent !== $entity->term_id) {
						continue;
					}

					$wingListHtml = '<li class="doctrine entity-' . $entity->slug . ' doctrine-' . $doctrine->slug . ' doctrine-id-' . $doctrine->term_id . '" data-doctrine="' . $doctrine->slug . '"><a class="doctrine-link-item" href="' . \get_term_link($doctrine->term_id) . '">' . $doctrine->name . '</a></li>';

					if(isset($hierarchy[$doctrine->term_id])) {
						$wings = \get_terms(array(
							'taxonomy' => $taxonomy,
							'orderby' => 'name',
							'order' => 'ASC',
							'child_of' => $doctrine->term_id
						));

						$wingListHtml = '<li class="doctrine entity-' . $entity->slug . ' doctrine-' . $doctrine->slug . ' doctrine-id-' . $doctrine->term_id . ' has-children" data-doctrine="' . $doctrine->slug . '"><a class="doctrine-link-item" href="' . \get_term_link($doctrine->term_id) . '">' . $doctrine->name . '</a>';
						$wingListHtml .= '<ul class="dropdown-menu child-doctrine-second-level child-doctrine-list">';

						if(isset($hierarchy[$doctrine->term_id])) {
							foreach($wings as $wing) {
								$wingListHtml .= '<li class="doctrine entity-' . $entity->slug . ' doctrine-' . $doctrine->slug . ' doctrine-wing-' . $wing->slug . ' doctrine-id-' . $wing->term_id . '" data-doctrine="' . $wing->slug . '"><a class="doctrine-link-item" href="' . \get_term_link($wing->term_id) . '">' . $wing->name . '</a></li>';
							}
						}

						$wingListHtml .= '</li>';
						$wingListHtml .= '</ul>';
					}

					$doctrineListHtml .= $wingListHtml;
				}

				$doctrineListHtml .= '</ul>';
				$doctrineListHtml .= '</li>';
			}

			$entityListHtml .= $doctrineListHtml;
			$entityListHtml .= '</li>';
		}

		$entityListHtml .= '</ul>';

		return $entityListHtml;
	}

	/**
	 * Gettng the doctrine menu for the conten shortcode
	 *
	 * @param string $taxonomy
	 * @return string
	 */
	public static function getContentMenu($taxonomy) {
		$pluginOptions = PluginHelper::getPluginSettings();
		$uniqueID = \uniqid();

		$entityListHtml = '<div class="gallery-row row">';
		$entityListHtml .= '<ul class="content-doctrine-list doctrine-list menu-' . $taxonomy . ' bootstrap-gallery bootstrap-post-loop-fittings bootstrap-post-loop-fittings-' . $uniqueID . ' clearfix">';

		// get all taxonomy terms
		$entities = \get_terms(array(
			'taxonomy' => $taxonomy,
			'orderby' => 'name',
			'order' => 'ASC'
		));

		// get terms that have children
		$hierarchy = \_get_term_hierarchy($taxonomy);

		// Loop through every entity
		foreach($entities as $entity) {
			// skip term if it has children or is empty
			if($entity->parent) {
				continue;
			} // END if($entity->parent)

			$doctrineImage = null;
			if(!empty($pluginOptions['template-settings']['show-doctrine-images-in-loop'])) {
				if(\function_exists('\z_taxonomy_image')) {
					$doctrineImage .= '<a class="doctrine-link-item" href="' . \get_term_link($entity->term_id) . '"><figure class="fitting-helper-post-loop-thumbnail">';
						if(\function_exists('\fly_get_attachment_image')) {
							$doctrineImage .= \fly_get_attachment_image(\z_get_attachment_id_by_url(\z_taxonomy_image_url($entity->term_id)), 'fitting-helper-post-loop-thumbnail');
						} else {
							$doctrineImage .= \z_taxonomy_image($entity->term_id, 'fitting-helper-post-loop-thumbnail', null, false);
						} // END if(\function_exists('\fly_get_attachment_image'))
					$doctrineImage .= '</figure></a>';
				} // END if(\function_exists('\z_taxonomy_image'))
			} // END if(!empty($pluginOptions['template-settings']['show-doctrine-images-in-loop']))

			$doctrineListHtml = '<li class="doctrine entity-' . $entity->slug . ' doctrine-id-' . $entity->term_id . '">' . $doctrineImage . '<header class="entry-header"><h2 class="entry-title"><a class="doctrine-link-item" href="' . \get_term_link($entity->term_id) . '">' . $entity->name . '</a></h2></header></li>';

			// If the entity has doctrines...
			if(isset($hierarchy[$entity->term_id])) {
				$doctrines = \get_terms(array(
					'taxonomy' => $taxonomy,
					'orderby' => 'name',
					'order' => 'ASC',
					'child_of' => $entity->term_id
				));

				$doctrineListHtml = '<li class="doctrine entity-' . $entity->slug . ' doctrine-id-' . $entity->term_id . ' has-children">' . $doctrineImage . '<header class="entry-header"><h2 class="entry-title"><a class="doctrine-link-item" href="' . \get_term_link($entity->term_id) . '">' . $entity->name . '</a></h2></header>';
				$doctrineListHtml .= '<div class="child-doctrine-list">';

				foreach($doctrines as $doctrine) {
					if($doctrine->parent && $doctrine->parent !== $entity->term_id) {
						continue;
					}

					$wingListHtml = '<div class="doctrine sub-first-level doctrine entity-' . $entity->slug . ' doctrine-' . $doctrine->slug . ' doctrine-id-' . $doctrine->term_id . '"><a class="doctrine-link-item" href="' . \get_term_link($doctrine->term_id) . '">' . $doctrine->name . '</a></div>';

					if(isset($hierarchy[$doctrine->term_id])) {
						$wings = \get_terms(array(
							'taxonomy' => $taxonomy,
							'orderby' => 'name',
							'order' => 'ASC',
							'child_of' => $doctrine->term_id
						));

						$wingListHtml = '<div class="doctrine sub-first-level doctrine entity-' . $entity->slug . ' doctrine-' . $doctrine->slug . ' doctrine-id-' . $doctrine->term_id . ' has-children"><a class="doctrine-link-item" href="' . \get_term_link($doctrine->term_id) . '">' . $doctrine->name . '</a><span class="caret dropdown-toggle" data-toggle="dropdown"><i></i></span>';
						$wingListHtml .= '<div class="child-doctrine-second-level child-doctrine-list">';

						if(isset($hierarchy[$doctrine->term_id])) {
							foreach($wings as $wing) {
								$wingListHtml .= '<div class="doctrine entity-' . $entity->slug . ' doctrine-' . $doctrine->slug . ' doctrine-wing-' . $wing->slug . ' doctrine-id-' . $wing->term_id . '"><a class="doctrine-link-item" href="' . \get_term_link($wing->term_id) . '">' . $wing->name . '</a></div>';
							}
						}

						$wingListHtml .= '</div>';
						$wingListHtml .= '</div>';
					}

					$doctrineListHtml .= $wingListHtml;
				}

				$doctrineListHtml .= '</div>';
			}

			$entityListHtml .= $doctrineListHtml;
			$entityListHtml .= '</li>';
		}

		$entityListHtml .= '</ul>';
		$entityListHtml .= '</div>';

		$entityListHtml .= '<script type="text/javascript">
								jQuery(document).ready(function() {
									jQuery("ul.bootstrap-post-loop-fittings-' . $uniqueID . '").bootstrapGallery({
										"classes" : "col-lg-4 col-md-6 col-sm-6 col-xs-12",
										"hasModal" : false
									});
								});
								</script>';

		return $entityListHtml;
	}

	/**
	 * Get the search query for fittings search
	 *
	 * @param boolean $escaped wether the query should be escaped or not
	 * @return string
	 */
	public static function getFittingSearchQuery($escaped = true) {
		/**
		 * Filters the contents of the search query variable.
		 *
		 * @since 2.3.0
		 *
		 * @param mixed $search Contents of the search query variable.
		 */
		$query = \apply_filters('get_fitting_search_query', \get_query_var('fitting_search'));

		if($escaped === true) {
			$query = \esc_attr($query);
		}

		return $query;
	}

	public static function searchFittings() {
		$args = array(
			'post_type' => 'fitting',
			'_meta_or_title' => self::getFittingSearchQuery(),
			'compare' => 'LIKE',
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key' => 'eve-online-fitting-manager_ship_type',
					'value' => self::getFittingSearchQuery(),
					'compare' => 'LIKE'
				),
				array(
					'key' => 'eve-online-fitting-manager_fitting_name',
					'value' => self::getFittingSearchQuery(),
					'compare' => 'LIKE'
				)
			),
			'orderby'=> 'title',
			'order' => 'ASC'
		);

		return new \WP_Query($args);
	}

	public static function getShipUsedInDoctrine() {
		$terms = \wp_get_object_terms(\get_the_ID(), 'fitting-doctrines');
		$doctrines = null;

		foreach($terms as $term) {
			$doctrines[$term->name] = $term;
		}

		if($doctrines !== null) {
			\ksort($doctrines);
		}

		return $doctrines;
	}

	/**
	 * Checl if a shipID is an Upwell Structure or not
	 *
	 * @param type $shipID
	 * @return boolean
	 */
	public static function isUpwellStructure($shipID) {
		return \in_array($shipID, self::getUpwellStructureIds());
	}
}
