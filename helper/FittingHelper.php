<?php

namespace WordPress\Plugin\EveOnlineFittingManager\Helper;

use WordPress\Plugin\EveOnlineFittingManager;

class FittingHelper {
	/**
	 * Getting High Slot Item Names
	 *
	 * @param type $highSlots
	 * @return type
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
	 * @param type $midSlots
	 * @return type
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
	 * @param type $lowSlots
	 * @return type
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
	 * @param type $rigSlots
	 * @return type
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
	 * @param type $subSystems
	 * @return type
	 */
	public static function getSubSystemItemNames($subSystems) {
		$sub = \unserialize($subSystems);
		$arrayRigSlot = array(
			'subSystem_1_itemName' => (!empty($sub['subSystem_1'])) ? self::getItemNameById($sub['subSystem_1']) : null,
			'subSystem_2_itemName' => (!empty($sub['subSystem_2'])) ? self::getItemNameById($sub['subSystem_2']) : null,
			'subSystem_3_itemName' => (!empty($sub['subSystem_3'])) ? self::getItemNameById($sub['subSystem_3']) : null,
			'subSystem_4_itemName' => (!empty($sub['subSystem_4'])) ? self::getItemNameById($sub['subSystem_4']) : null,
			'subSystem_5_itemName' => (!empty($sub['subSystem_5'])) ? self::getItemNameById($sub['subSystem_5']) : null,
		);

		return $arrayRigSlot;
	}

	/**
	 * Getting Item Description
	 *
	 * @param type $itemID
	 * @return type
	 */
	public static function getItemDescription($itemID) {
		$sql = 'SELECT `description` FROM `kb3_invtypes` WHERE `typeID` = ?';

		$description = $this->killboardDb->fetchOne($sql, array($itemID));
		$description = \preg_replace('/<br>/', '##break##', $description);
		$description = \strip_tags($description);
		$description = \preg_replace('/##break##/', '<br>', $description);

		return \nl2br($description);
	}

	/**
	 * Getting Item Details
	 *
	 * @param type $itemName
	 * @param type $itemCount
	 * @return boolean
	 */
	public static function getItemDetailsByItemName($itemName, $itemCount = 1) {
		$sql = 'SELECT
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
				WHERE it.typeName = ?
				AND itt.itt_id = it.groupID';
		$itemData = $this->killboardDb->fetchAll($sql, array($itemName));

		/**
		 * If we don't have any result here, we might have an item that cannot be fitted.
		 * So do another check without restricting to slots.
		 */
		if(!$itemData) {
			$sql = 'SELECT
						`kb3_invtypes`.`typeID` AS `itemID`,
						`kb3_invtypes`.`groupID` AS `groupID`,
						`kb3_invtypes`.`typeName` AS `itemName`,
						`kb3_invtypes`.`description` AS `itemDescription`,
						`kb3_item_types`.`itt_slot` AS `slotID`,
						`kb3_item_types`.`itt_cat` AS `categoryID`,
						`kb3_item_locations`.`itl_flagName` AS `slotName`
					FROM `kb3_invtypes`, `kb3_item_types`, `kb3_item_locations`
					WHERE `typeName` = ?
					AND `kb3_item_types`.`itt_id` = `kb3_invtypes`.`groupID`
					AND `kb3_item_locations`.`itl_flagID` = `kb3_item_types`.`itt_slot`';
			$itemData = $this->killboardDb->fetchAll($sql, array($itemName));
		} // END if(!$itemData)

		if($itemData) {
			$itemData = $itemData['0'];

			if(!empty($itemData['itemID'])) {
				/**
				 * Category: Ships
				 */
				if($itemData['categoryID'] == '6') {
					$itemData['slotName'] = 'ship';
				} // END if($itemData['categoryID'] == '6')

				/**
				 * Category: Charges
				 */
				if($itemData['categoryID'] == '8') {
					$itemData['slotName'] = 'charge';
				} // END if($itemData['categoryID'] == '6')

				/**
				 * Category: Dones
				 */
				if($itemData['categoryID'] == '18') {
					$itemData['slotName'] = 'drone';
				} // END if($itemData['categoryID'] == '6')

				if($itemCount != null) {
					$itemData['itemCount'] = $itemCount;
				} // END if($itemCount != null)

				return $itemData;
			} // END if(!empty($itemData['itemID']))
		}

		return false;
	} // END public function getItemDetailsByItemName($itemName)

	/**
	 * Getting Items Data
	 *
	 * @param type $itemName
	 * @return boolean
	 */
	public static function getItems($itemName) {
		$sql = 'SELECT
					`kb3_invtypes`.`typeName` AS `itemName`
				FROM `kb3_invtypes`
				WHERE `typeName` LIKE ?';
		$itemData = $this->killboardDb->fetchAll($sql, array('%' . $itemName . '%'));

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
	 * @param type $itemName
	 * @return type
	 */
	public static function getItemIdByName($itemName) {
		$sql = 'SELECT `kb3_invtypes`.`typeID` AS `itemID` from `kb3_invtypes` WHERE `kb3_invtypes`.`typeName` = ?';
		$itemID = $this->killboardDb->fetchOne($sql, array($itemName));

		return $itemID;
	} // END public function getItemIdByName($itemName)

	/**
	 * Getting an item name by its iten ID
	 *
	 * @param type $itemID
	 * @return type
	 */
	public static function getItemNameById($itemID) {
		if(\is_array($itemID)) {
			$itemNames = array();
			foreach($itemID as $id) {
				$sql = 'SELECT `kb3_invtypes`.`typeName` AS `itemName` from `kb3_invtypes` WHERE `kb3_invtypes`.`typeID` = ?';
				$itemNames[$id] = $this->killboardDb->fetchOne($sql, array($id));
			} // END foreach($itemID as $id)

			return $itemNames;
		} // END if(is_array($itemID))

		$sql = 'SELECT `kb3_invtypes`.`typeName` AS `itemName` from `kb3_invtypes` WHERE `kb3_invtypes`.`typeID` = ?';
		$itemName = $this->killboardDb->fetchOne($sql, array($itemID));

		return $itemName;
	} // END public function getItemNameById($itemID)

	public static function getDescriptionItemDetailsByItemName($itemName) {
		$itemDetails = self::getItemDetailsByItemName($itemName);

		return $itemDetails;
	} // END public function getDescriptionItemDetailsByItemName($itemName)

	/**
	 * Getting a fitting DNA from its fitting data
	 *
	 * @param type $fittingData
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
			switch($data['slotName']) {
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
		$shipDnaString .= $shipData['itemID'] . ':';

		/**
		 * Subsystems, if its a T3 Tactical Cruiser
		 */
		if(!empty($subSystems)) {
			foreach($subSystems as $sub) {
				$shipDnaString .= $sub['itemID'] . ';' . $sub['itemCount'] . ':';
			} // END foreach($subSystems as $sub)
		} // END if(!empty($subSystems))

		/**
		 * Highslots
		 */
		if(!empty($highSlots)) {
			foreach($highSlots as $high) {
				$shipDnaString .= $high['itemID'] . ';' . $high['itemCount'] . ':';
			} // END foreach($highSlots as $high)
		} // END if(!empty($highSlots))

		/**
		 * Medslots
		 */
		if(!empty($midSlots)) {
			foreach($midSlots as $mid) {
				$shipDnaString .= $mid['itemID'] . ';' . $mid['itemCount'] . ':';
			} // END foreach($midSlots as $mid)
		} // END if(!empty($midSlots))

		/**
		 * Lowslots
		 */
		if(!empty($lowSlots)) {
			foreach($lowSlots as $low) {
				$shipDnaString .= $low['itemID'] . ';' . $low['itemCount'] . ':';
			} // END foreach($lowSlots as $low)
		} // END if(!empty($lowSlots))

		/**
		 * Rigs
		 */
		if(!empty($rigSlots)) {
			foreach($rigSlots as $rig) {
				$shipDnaString .= $rig['itemID'] . ';' . $rig['itemCount'] . ':';
			} // END foreach($rigSlots as $rig)
		} // END if(!empty($rigSlots))

		/**
		 * Charges
		 */
		if(!empty($charges)) {
			foreach($charges as $charge) {
				$shipDnaString .= $charge['itemID'] . ';' . $charge['itemCount'] . ':';
			} // END foreach($charges as $rig)
		} // END if(!empty($charges))

		/**
		 * Drones
		 */
		if(!empty($drones)) {
			foreach($drones as $drone) {
				$shipDnaString .= $drone['itemID'] . ';' . $drone['itemCount'] . ':';
			} // END foreach($drones as $rig)
		} // END if(!empty($drones))

		$shipDnaString .= ':';

		return $shipDnaString;
	} // END public function getShipDnaFromFittingData($fittingData)

	/**
	 * Getting the slot layout from fitting data
	 *
	 * @param type $fitting
	 * @return type
	 */
	public static function getSlotLayoutFromFittingArray($fitting) {
		$currentHighSlots = 0;
		$currenMidSlots = 0;
		$currentLowSlots = 0;
		$currentRigSlots = 0;
		$currentSubSystems = 0;

		$fittedSubSystems = unserialize($fitting['subSystems']);

		$arrayStrategicCruiserIDs = array(
			29984,
			29986,
			29988,
			29990
		);

		/**
		 * Check if it's a strategic cruiser and has sub systems
		 */
		if(\in_array($fitting['shipID'], $arrayStrategicCruiserIDs) && !empty($fittedSubSystems)) {
			/**
			 * Processing Strategic Cruiser
			 * Those ships have their slot layout from their subsystem modifiers
			 */
			$maxSubSystems = 5;

			for($i = 1; $i <= $maxSubSystems; $i++) {
				$currentHighSlots += self::getHighSlotModifierCountForShipID($fittedSubSystems['subSystem_' . $i]);
				$currenMidSlots += self::getMidSlotModifierCountForShipID($fittedSubSystems['subSystem_' . $i]);
				$currentLowSlots += self::getLowSlotModifierCountForShipID($fittedSubSystems['subSystem_' . $i]);
			} // END for($i = 1; $i <= $maxSubSystems; $i++)

			$currentRigSlots = self::getRigSlotCountForShipID($fitting['shipID']);
			$currentSubSystems = 5;
		} else {
			$currentHighSlots = self::getHighSlotCountForShipID($fitting['shipID']);
			$currenMidSlots = self::getMidSlotCountForShipID($fitting['shipID']);
			$currentLowSlots = self::getLowSlotCountForShipID($fitting['shipID']);
			$currentRigSlots = self::getRigSlotCountForShipID($fitting['shipID']);
		} // END if(in_array($fitting['shipID'], $arrayStrategicCruiserIDs))

		return array(
			'highSlots' => $currentHighSlots,
			'midSlots' => $currenMidSlots,
			'lowSlots' => $currentLowSlots,
			'rigSlots' => $currentRigSlots,
			'subSystems' => $currentSubSystems
		);
	} // END public function getSlotLayoutFromFittingArray($fitting)

	/**
	 * Getting the count of high slots of a given ship by its ID
	 *
	 * @param type $shipID
	 * @return type
	 */
	 public static function getHighSlotCountForShipID($shipID) {
		$sql = 'SELECT `value`
				FROM `kb3_dgmtypeattributes`
				JOIN `kb3_dgmattributetypes` ON `attributeName` = "hiSlots"
				WHERE `kb3_dgmattributetypes`.`attributeID` = `kb3_dgmtypeattributes`.`attributeID`
				AND `kb3_dgmtypeattributes`.`typeID` = ?';

		return $this->killboardDb->fetchOne($sql, array($shipID));
	} // END public function getHighSlotCountForShipID($shipID)

	/**
	 * Getting the count of mid slots of a given ship by its ID
	 *
	 * @param type $shipID
	 * @return type
	 */
	public static function getMidSlotCountForShipID($shipID) {
		$sql = 'SELECT `value`
				FROM `kb3_dgmtypeattributes`
				JOIN `kb3_dgmattributetypes` ON `attributeName` = "medSlots"
				WHERE `kb3_dgmattributetypes`.`attributeID` = `kb3_dgmtypeattributes`.`attributeID`
				AND `kb3_dgmtypeattributes`.`typeID` = ?';

		return $this->killboardDb->fetchOne($sql, array($shipID));
	} // END public function getMidSlotCountForShipID($shipID)

	/**
	 * Getting the count of low slots of a given ship by its ID
	 *
	 * @param type $shipID
	 * @return type
	 */
	public static function getLowSlotCountForShipID($shipID) {
		$sql = 'SELECT `value`
				FROM `kb3_dgmtypeattributes`
				JOIN `kb3_dgmattributetypes` ON `attributeName` = "lowSlots"
				WHERE `kb3_dgmattributetypes`.`attributeID` = `kb3_dgmtypeattributes`.`attributeID`
				AND `kb3_dgmtypeattributes`.`typeID` = ?';

		return $this->killboardDb->fetchOne($sql, array($shipID));
	} // END public function getLowSlotCountForShipID($shipID)

	/**
	 * Getting the amount of high slots modified by a subsystem
	 *
	 * @param type $subsystemID
	 * @return type
	 */
	public static function getHighSlotModifierCountForShipID($subsystemID) {
		$sql = 'SELECT `value`
				FROM `kb3_dgmtypeattributes`
				JOIN `kb3_dgmattributetypes` ON `attributeName` = "hiSlotModifier"
				WHERE `kb3_dgmattributetypes`.`attributeID` = `kb3_dgmtypeattributes`.`attributeID`
				AND `kb3_dgmtypeattributes`.`typeID` = ?';

		return $this->killboardDb->fetchOne($sql, array($subsystemID));
	} // END public function getHighSlotModifierCountForShipID($subsystemID)

	/**
	 * Getting the amount of mid slots modified by a subsystem
	 *
	 * @param type $subsystemID
	 * @return type
	 */
	public static function getMidSlotModifierCountForShipID($subsystemID) {
		$sql = 'SELECT `value`
				FROM `kb3_dgmtypeattributes`
				JOIN `kb3_dgmattributetypes` ON `attributeName` = "medSlotModifier"
				WHERE `kb3_dgmattributetypes`.`attributeID` = `kb3_dgmtypeattributes`.`attributeID`
				AND `kb3_dgmtypeattributes`.`typeID` = ?';

		return $this->killboardDb->fetchOne($sql, array($subsystemID));
	} // END public function getMidSlotModifierCountForShipID($subsystemID)

	/**
	 * Getting the amount of low slots modified by a subsystem
	 *
	 * @param type $subsystemID
	 * @return type
	 */
	public static function getLowSlotModifierCountForShipID($subsystemID) {
		$sql = 'SELECT `value`
				FROM `kb3_dgmtypeattributes`
				JOIN `kb3_dgmattributetypes` ON `attributeName` = "lowSlotModifier"
				WHERE `kb3_dgmattributetypes`.`attributeID` = `kb3_dgmtypeattributes`.`attributeID`
				AND `kb3_dgmtypeattributes`.`typeID` = ?';

		return $this->killboardDb->fetchOne($sql, array($subsystemID));
	} // END public function getLowSlotModifierCountForShipID($subsystemID)

	/**
	 * Getting the count of rig slots of a given ship by its ID
	 *
	 * @param type $shipID
	 * @return type
	 */
	public static function getRigSlotCountForShipID($shipID) {
		$sql = 'SELECT `value`
				FROM `kb3_dgmtypeattributes`
				JOIN `kb3_dgmattributetypes` ON `attributeName` = "rigSlots"
				WHERE `kb3_dgmattributetypes`.`attributeID` = `kb3_dgmtypeattributes`.`attributeID`
				AND `kb3_dgmtypeattributes`.`typeID` = ?';

		return $this->killboardDb->fetchOne($sql, array($shipID));
	} // END public function getRigSlotCountForShipID($shipID)
}
