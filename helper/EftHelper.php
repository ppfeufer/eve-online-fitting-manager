<?php

namespace WordPress\Plugin\EveOnlineFittingManager\Helper;

use WordPress\Plugin\EveOnlineFittingManager;

\defined('ABSPATH') or die();

class EftHelper {
	/**
	 * Fixing the line breaks of the EFT dump
	 *
	 * @param string $eftFitting EFT fitting dump
	 * @return string
	 */
	public static function fixLineBreaks($eftFitting) {
		$eftFitting = \str_replace("\r\n", "\n", $eftFitting); // windows -> linux
		$eftFitting = \str_replace("\r", "\n", $eftFitting); // mac -> linux

		return $eftFitting;
	}

	/**
	 * Getting the ship class name from the EFT dump
	 *
	 * @param string $eftFitting EFT fitting dump
	 * @return string The ship class name
	 */
	public static function getShipType($eftFitting) {
		$returnValue = null;

		if(!empty($eftFitting)) {
			/**
			 * Zeilenumbrüche korrigieren
			 */
			$fittingArray = \explode("\n", \trim(self::fixLineBreaks($eftFitting)));

			$fittingArray['0'] = \str_replace('[', '', $fittingArray['0']);
			$fittingArray['0'] = \str_replace(']', '', $fittingArray['0']);
			$fittingArray['0'] = \preg_replace('/,(.*)/', '', $fittingArray['0']);

			$returnValue = \trim($fittingArray['0']);
		}

		return $returnValue;
	}

	/**
	 * Getting the fitting name from teh EFT dump
	 *
	 * @param string $eftFitting EFT fitting dump
	 * @return string The fitting name
	 */
	public static function getFittingName($eftFitting)  {
		$returnValue = null;

		if(!empty($eftFitting)) {
			/**
			 * Zeilenumbrüche korrigieren
			 */
			$fittingArray = \explode("\n", \trim(self::fixLineBreaks($eftFitting)));

			$fittingArray['0'] = \str_replace('[', '', $fittingArray['0']);
			$fittingArray['0'] = \str_replace(']', '', $fittingArray['0']);
			$fittingArray['0'] = \preg_replace('/(.*),/', '', $fittingArray['0']);

			$returnValue = \trim($fittingArray['0']);
		}

		return $returnValue;
	}

	/**
	 * Get the Item IDs for the fitting from EFT Import
	 *
	 * @param string $eftFitting
	 * @return multitype:multitype:NULL unknown  multitype:multitype:unknown   multitype:NULL Ambigous <>
	 */
	public static function getSlotDataFromEftData($eftFitting) {
		$returnValue = null;

		if(!empty($eftFitting)) {
			/**
			 * Zeilenumbrüche korrigieren
			 */
			$fittingArray = \explode("\n", \trim(self::fixLineBreaks($eftFitting)));

			$fittingData = array();
			$fittingArray['0'] = \str_replace('[', '', $fittingArray['0']);
			$fittingArray['0'] = \str_replace(']', '', $fittingArray['0']);
			$fittingArray['0'] = \preg_replace('/,(.*)/', '', $fittingArray['0']);

			$count_highSlot = 1;
			$count_midSlot = 1;
			$count_lowSlot = 1;
			$count_rigSlot = 1;
			$count_subSystem = 1;
			$count_charges = 1;
			$count_drones = 1;

			$arrayHighSlot = array();
			$arrayMidSlot = array();
			$arrayLowSlot = array();
			$arrayRigSlot = array();
			$arraySubSystem = array();
			$arrayCharges = array();
			$arrayDrones = array();

			foreach($fittingArray as &$line) {
				$line = trim($line);

				if(!empty($line)) {
					$itemCount = 1;
					$line = \trim(\preg_replace('/,(.*)/', '', $line));

					if(!empty($line)) {
						/**
						 * If we have an item count, get it ...
						 */
						if(\preg_match('/ x[0-9]*/', $line, $matches)) {
							$itemCount = \str_replace('x', '', \trim($matches['0']));
							$line = \trim(\str_replace(\trim($matches['0']), '', $line));
						} // END if(preg_match('/ x[0-9]*/', $line, $matches))

						$itemDetail = FittingHelper::getItemDetailsByItemName($line, $itemCount);

						switch($itemDetail->slotName) {
							case 'HiSlot':
							case 'High power':
								$arrayHighSlot['highSlot_' . $count_highSlot] = $itemDetail->itemID;
								$count_highSlot++;
								break;

							case 'MedSlot':
							case 'Medium power':
								$arrayMidSlot['midSlot_' . $count_midSlot] = $itemDetail->itemID;
								$count_midSlot++;
								break;

							case 'LoSlot':
							case 'Low power':
								$arrayLowSlot['lowSlot_' . $count_lowSlot] = $itemDetail->itemID;
								$count_lowSlot++;
								break;

							case 'RigSlot':
							case 'Rig Slot':
								$arrayRigSlot['rigSlot_' . $count_rigSlot] = $itemDetail->itemID;
								$count_rigSlot++;
								break;

							case 'SubSystem':
							case 'Sub System':
								$arraySubSystem['subSystem_' . $count_subSystem] = $itemDetail->itemID;
								$count_subSystem++;
								break;

							case 'charge':
								$arrayCharges['charge_' . $count_charges] = array(
									'itemID' => $itemDetail->itemID,
									'itemCount' => $itemDetail->itemCount
								);
								$count_charges++;
								break;

							case 'drone':
								$arrayDrones['drone_' . $count_drones] = array(
									'itemID' => $itemDetail->itemID,
									'itemCount' => $itemDetail->itemCount
								);
								$count_drones++;
								break;

							default:
								break;
						} // END switch($itemDetail['slotName'])

						$fittingData[] = $itemDetail;
					} // END if(!empty($itemDetails))
				} // END if(!empty(trim($line)))
			} // END foreach($fittingArray as &$line)

			$returnValue = array(
				'highSlots' => $arrayHighSlot,
				'midSlots' => $arrayMidSlot,
				'lowSlots' => $arrayLowSlot,
				'rigSlots' => $arrayRigSlot,
				'subSystems' => $arraySubSystem,
				'charges' => $arrayCharges,
				'drones' => $arrayDrones
			);
		}

		return $returnValue;
	} // END public function getSlotDataFromEftData($eftFitting)

	/**
	 * Generate a fitting array from the EFT dump
	 *
	 * @param type $eftFitting
	 * @return type
	 */
	public static function getFittingArrayFromEftData($eftFitting) {
		/**
		 * Zeilenumbrüche korrigieren
		 */
		$fittingArray = \explode("\n", \trim(self::fixLineBreaks($eftFitting)));

		$fittingData = array();
		$fittingArray['0'] = \str_replace('[', '', $fittingArray['0']);
		$fittingArray['0'] = \str_replace(']', '', $fittingArray['0']);
		$fittingArray['0'] = \preg_replace('/,(.*)/', '', $fittingArray['0']);

		foreach($fittingArray as &$line) {
			$line = \trim($line);

			if(!empty($line)) {
				$itemCount = 1;
				$line = \trim(\preg_replace('/,(.*)/', '', $line));

				if(!empty($line)) {
					/**
					 * If we have an item count, get it ...
					 */
					$matches = null;
					if(\preg_match('/ x[0-9]*/', $line, $matches)) {
						$itemCount = \str_replace('x', '', \trim($matches['0']));
						$line = \trim(\str_replace(\trim($matches['0']), '', $line));
					} // END if(preg_match('/ x[0-9]*/', $line, $matches))

					$fittingData[] = FittingHelper::getItemDetailsByItemName($line, $itemCount);
				} // END if(!empty($itemDetails))
			} // END if(!empty(trim($line)))
		} // END foreach($fittingArray as &$line)

		return $fittingData;
	} // END public function getFittingArrayFromEftData($eftFitting)

	/**
	 * Getting the ships DNA from the EFT dump
	 *
	 * @param type $eftFitting
	 * @return type
	 */
	public static function getShipDnaFromEftData($eftFitting) {
		$returnValue = null;

		if(!empty($eftFitting)) {
			$fittingData = self::getFittingArrayFromEftData(\trim(self::fixLineBreaks($eftFitting)));
			$returnValue = FittingHelper::getShipDnaFromFittingData($fittingData);
		}

		return $returnValue;
	} // END public function getShipDnaFromEftData($eftFitting)

	/**
	 * Getting the EFT dump from a saved fitting
	 *
	 * @param type $fitting
	 * @param type $withShipDna
	 * @return string
	 */
	public static function getEftImportFromFitting($fitting, $withShipDna = false) {
		$arrayHighSlots = \unserialize($fitting['highSlots']);
		$arrayMidSlots = \unserialize($fitting['midSlots']);
		$arrayLowSlots = \unserialize($fitting['lowSlots']);
		$arrayRigSlots = \unserialize($fitting['rigSlots']);
		$arraySubSystems = \unserialize($fitting['subSystems']);
		$arrayDrones = \unserialize($fitting['drones']);
		$arrayCharges = \unserialize($fitting['charges']);

		$eftImport = '';
		$eftImport .= '[' . FittingHelper::getItemNameById($fitting['shipID']) . ', ' . \trim($fitting['fittingType']) . ']' . "\n";

		/**
		 * Low Slots
		 */
		$hasLowSlots = false;
		if(\is_array($arrayLowSlots)) {
			foreach($arrayLowSlots as $lowSlot) {
				if($lowSlot !== false) {
					$hasLowSlots = true;
					$eftImport .= FittingHelper::getItemNameById($lowSlot) . "\n";
				} // END if($lowSlot != false)
			} // END foreach($arrayLowSlots as $lowSlot)

			if($hasLowSlots === true) {
				$eftImport .= '' . "\n";
			} // END if($hasLowSlots == true)
		} // END if(is_array($arrayLowSlots))

		/**
		 * Mid Slots
		 */
		$hasMidSlots = false;
		if(\is_array($arrayMidSlots)) {
			foreach($arrayMidSlots as $midSlot) {
				if($midSlot !== false) {
					$hasMidSlots = true;
					$eftImport .= FittingHelper::getItemNameById($midSlot) . "\n";
				} // END if($midSlot != false)
			} // END foreach($arrayMidSlots as $midSlot)

			if($hasMidSlots === true) {
				$eftImport .= '' . "\n";
			} // END if($hasMidSlots == true)
		} // END if(is_array($arrayMidSlots))

		/**
		 * High Slots
		 */
		$hasHighSlots = false;
		if(\is_array($arrayHighSlots)) {
			foreach($arrayHighSlots as $highSlot) {
				if($highSlot !== false) {
					$hasHighSlots = true;
					$eftImport .= FittingHelper::getItemNameById($highSlot) . "\n";
				} // END if($highSlot != false)
			} // END foreach($arrayHighSlots as $highSlot)

			if($hasHighSlots === true) {
				$eftImport .= '' . "\n";
			} // END if($hasHighSlots == true)
		} // END if(is_array($arrayHighSlots))

		/**
		 * Rig Slots
		 */
		$hasRigSlots = false;
		if(\is_array($arrayRigSlots)) {
			foreach($arrayRigSlots as $rigSlot) {
				if($rigSlot !== false) {
					$hasRigSlots = true;
					$eftImport .= FittingHelper::getItemNameById($rigSlot) . "\n";
				} // END if($rigSlot != false)
			} // END foreach($arrayRigSlots as $rigSlot)
		} // END if(is_array($arrayRigSlots))

		if($hasRigSlots === true) {
			$eftImport .= '' . "\n";
		} // END if($hasRigSlots == true)
		/**
		 * Sub Systems
		 */
		$hasSubSystems = false;
		if(\is_array($arraySubSystems)) {
			foreach($arraySubSystems as $subSystem) {
				if($subSystem !== false) {
					$hasSubSystems = true;
					$eftImport .= FittingHelper::getItemNameById($subSystem) . "\n";
				} // END if($subSystem != false)
			} // END foreach($arrayRigSlots as $rigSlot)

			if($hasSubSystems === true) {
				$eftImport .= '' . "\n";
			} // END if($hasRigSlots == true)
		} // END if(is_array($arraySubSystems))

		/**
		 * Drones
		 */
		$hasDrones = false;
		if(\is_array($arrayDrones)) {
			foreach($arrayDrones as $drone) {
				if($drone !== false) {
					$hasDrones = true;
					$eftImport .= FittingHelper::getItemNameById($drone['itemID']) . ' x' . $drone['itemCount'] . "\n";
				} // END if($drones != false)
			} // END foreach($arrayDrones as $drones)

			if($hasDrones === true) {
				$eftImport .= '' . "\n";
			} // END if($hasRigSlots == true)
		} // END if(is_array($arrayDrones))

		/**
		 * Charges
		 */
		$hasCharges = false;
		if(\is_array($arrayCharges)) {
			foreach($arrayCharges as $charge) {
				if($charge !== false) {
					$hasCharges = true;
					$eftImport .= FittingHelper::getItemNameById($charge['itemID']) . ' x' . $charge['itemCount'] . "\n";
				} // END if($drones != false)
			} // END foreach($arrayDrones as $drones)
		} // END if(is_array($arrayCharges))

		$returnValue = $eftImport;
		if($withShipDna === true) {
			$returnValue = array(
				'eftImport' => $eftImport,
				'shipDna' => $fitting['shipDNA']
			);
		}

		return $returnValue;
	} // END public function getEftImportFromFitting($fitting, $withShipDna = false)
}
