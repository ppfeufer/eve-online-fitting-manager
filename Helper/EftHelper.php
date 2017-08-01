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
	} // END public static function fixLineBreaks($eftFitting)

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
	} // END public static function getShipType($eftFitting)

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
	} // END public static function getFittingName($eftFitting)

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

			$countHighSlots = 1;
			$countMidSlots = 1;
			$countLowSlots = 1;
			$countRigSlots = 1;
			$countSubSystems = 1;
			$countUpwellServices = 1;
			$countCharges = 1;
			$countDrones = 1;

			$arrayHighSlots = array();
			$arrayMidSlots = array();
			$arrayLowSlots = array();
			$arrayRigSlots = array();
			$arraySubSystems = array();
			$arrayUpwellServices = array();
			$arrayCharges = array();
			$arrayFuel = array();
			$arrayDrones = array();

			foreach($fittingArray as &$line) {
				$line = \trim($line);

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
								$arrayHighSlots['highSlot_' . $countHighSlots] = $itemDetail->itemID;
								$countHighSlots++;
								break;

							case 'MedSlot':
							case 'Medium power':
								$arrayMidSlots['midSlot_' . $countMidSlots] = $itemDetail->itemID;
								$countMidSlots++;
								break;

							case 'LoSlot':
							case 'Low power':
								$arrayLowSlots['lowSlot_' . $countLowSlots] = $itemDetail->itemID;
								$countLowSlots++;
								break;

							case 'RigSlot':
							case 'Rig Slot':
								$arrayRigSlots['rigSlot_' . $countRigSlots] = $itemDetail->itemID;
								$countRigSlots++;
								break;

							case 'SubSystem':
							case 'Sub System':
								$arraySubSystems['subSystem_' . $countSubSystems] = $itemDetail->itemID;
								$countSubSystems++;
								break;

							case 'UpwellService':
							case 'UpwellService':
							case 'Service Slot':
								$arrayUpwellServices['upwellService_' . $countUpwellServices] = $itemDetail->itemID;
								$countUpwellServices++;
								break;

							case 'charge':
								$arrayCharges['charge_' . $countCharges] = array(
									'itemID' => $itemDetail->itemID,
									'itemCount' => $itemDetail->itemCount
								);
								$countCharges++;
								break;

							case 'fuel':
								$arrayFuel['fuel_' . $countCharges] = array(
									'itemID' => $itemDetail->itemID,
									'itemCount' => $itemDetail->itemCount
								);
								$countCharges++;
								break;

							case 'drone':
								$arrayDrones['drone_' . $countDrones] = array(
									'itemID' => $itemDetail->itemID,
									'itemCount' => $itemDetail->itemCount
								);
								$countDrones++;
								break;

							default:
								break;
						} // END switch($itemDetail['slotName'])

						$fittingData[] = $itemDetail;
					} // END if(!empty($itemDetails))
				} // END if(!empty(trim($line)))
			} // END foreach($fittingArray as &$line)

			$returnValue = array(
				'highSlots' => $arrayHighSlots,
				'midSlots' => $arrayMidSlots,
				'lowSlots' => $arrayLowSlots,
				'rigSlots' => $arrayRigSlots,
				'subSystems' => $arraySubSystems,
				'upwellServices' => $arrayUpwellServices,
				'charges' => $arrayCharges,
				'fuel' => $arrayFuel,
				'drones' => $arrayDrones
			);
		}

		return $returnValue;
	} // END public function getSlotDataFromEftData($eftFitting)

	/**
	 * Generate a fitting array from the EFT dump
	 *
	 * @param string $eftFitting
	 * @return type
	 */
	public static function getFittingArrayFromEftData($eftFitting) {
		/**
		 * fix line breakings
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
		} // END if(!empty($eftFitting))

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
		$arrayServiceSlots = \unserialize($fitting['serviceSlots']);
		$arrayDrones = \unserialize($fitting['drones']);
		$arrayCharges = \unserialize($fitting['charges']);
		$arrayFuel = \unserialize($fitting['fuel']);

		$eftImport = '';
		$eftImport .= '[' . FittingHelper::getItemNameById($fitting['shipID']) . ', ' . \trim($fitting['fittingType']) . ']' . "\n";

		/**
		 * Low Slots
		 */
		if(\is_array($arrayLowSlots)) {
			foreach($arrayLowSlots as $lowSlot) {
				if($lowSlot !== false) {
					$eftImport .= FittingHelper::getItemNameById($lowSlot) . "\n";
				} // END if($lowSlot != false)
			} // END foreach($arrayLowSlots as $lowSlot)
		} // END if(is_array($arrayLowSlots))

		/**
		 * Mid Slots
		 */
		if(\is_array($arrayMidSlots) && \count($arrayMidSlots) > 0) {
			$eftImport .= '' . "\n";

			foreach($arrayMidSlots as $midSlot) {
				if($midSlot !== false) {
					$eftImport .= FittingHelper::getItemNameById($midSlot) . "\n";
				} // END if($midSlot != false)
			} // END foreach($arrayMidSlots as $midSlot)
		} // END if(is_array($arrayMidSlots))

		/**
		 * High Slots
		 */
		if(\is_array($arrayHighSlots) && \count($arrayHighSlots) > 0) {
			$eftImport .= '' . "\n";

			foreach($arrayHighSlots as $highSlot) {
				if($highSlot !== false) {
					$eftImport .= FittingHelper::getItemNameById($highSlot) . "\n";
				} // END if($highSlot != false)
			} // END foreach($arrayHighSlots as $highSlot)
		} // END if(is_array($arrayHighSlots))

		/**
		 * Rig Slots
		 */
		if(\is_array($arrayRigSlots) && \count($arrayRigSlots) > 0) {
			$eftImport .= '' . "\n";

			foreach($arrayRigSlots as $rigSlot) {
				if($rigSlot !== false) {
					$eftImport .= FittingHelper::getItemNameById($rigSlot) . "\n";
				} // END if($rigSlot != false)
			} // END foreach($arrayRigSlots as $rigSlot)
		} // END if(is_array($arrayRigSlots))

		/**
		 * Sub Systems
		 */
		if(\is_array($arraySubSystems) && \count($arraySubSystems) > 0) {
			$eftImport .= '' . "\n";

			foreach($arraySubSystems as $subSystem) {
				if($subSystem !== false) {
					$eftImport .= FittingHelper::getItemNameById($subSystem) . "\n";
				} // END if($subSystem != false)
			} // END foreach($arrayRigSlots as $rigSlot)
		} // END if(is_array($arraySubSystems))

		/**
		 * Service Slots
		 */
		if(\is_array($arrayServiceSlots) && \count($arrayServiceSlots) > 0) {
			$eftImport .= '' . "\n";

			foreach($arrayServiceSlots as $serviceSlot) {
				if($serviceSlot !== false) {
					$eftImport .= FittingHelper::getItemNameById($serviceSlot) . "\n";
				} // END if($subSystem != false)
			} // END foreach($arrayRigSlots as $rigSlot)
		} // END if(is_array($arraySubSystems))

		/**
		 * Drones
		 */
		if(\is_array($arrayDrones) && \count($arrayDrones) > 0) {
			$eftImport .= '' . "\n";

			foreach($arrayDrones as $drone) {
				if($drone !== false) {
					$eftImport .= FittingHelper::getItemNameById($drone['itemID']) . ' x' . $drone['itemCount'] . "\n";
				} // END if($drones != false)
			} // END foreach($arrayDrones as $drones)
		} // END if(is_array($arrayDrones))

		/**
		 * Charges
		 */
		if(\is_array($arrayCharges) && \count($arrayCharges) > 0) {
			$eftImport .= '' . "\n";

			foreach($arrayCharges as $charge) {
				if($charge !== false) {
					$eftImport .= FittingHelper::getItemNameById($charge['itemID']) . ' x' . $charge['itemCount'] . "\n";
				} // END if($drones != false)
			} // END foreach($arrayDrones as $drones)
		} // END if(is_array($arrayCharges))

		/**
		 * Fuel
		 */
		if(\is_array($arrayFuel) && \count($arrayFuel) > 0) {
			$eftImport .= '' . "\n";

			foreach($arrayFuel as $fuel) {
				if($charge !== false) {
					$eftImport .= FittingHelper::getItemNameById($fuel['itemID']) . ' x' . $fuel['itemCount'] . "\n";
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
} // END class EftHelper
