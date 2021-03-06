<?php

/*
 * Copyright (C) 2017 ppfeufer
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace WordPress\Plugins\EveOnlineFittingManager\Libs\Helper;

use WordPress\Plugins\EveOnlineFittingManager\Libs\Singletons\AbstractSingleton;

use function count;
use function defined;
use function explode;
use function is_array;
use function maybe_unserialize;
use function preg_match;
use function preg_replace;
use function str_replace;
use function trim;

defined('ABSPATH') or die();

class EftHelper extends AbstractSingleton
{
    /**
     * Getting the ship class name from the EFT dump
     *
     * @param string $eftFitting EFT fitting dump
     * @return string|null The ship class name
     */
    public function getShipType(string $eftFitting): ?string
    {
        $returnValue = null;

        if (!empty($eftFitting)) {
            $fittingArray = explode("\n", trim(StringHelper::getInstance()->fixLineBreaks($eftFitting)));

            $fittingArray['0'] = str_replace('[', '', $fittingArray['0']);
            $fittingArray['0'] = str_replace(']', '', $fittingArray['0']);
            $fittingArray['0'] = preg_replace('/,(.*)/', '', $fittingArray['0']);

            $returnValue = trim($fittingArray['0']);
        }

        return $returnValue;
    }

    /**
     * Getting the fitting name from the EFT dump
     *
     * @param string $eftFitting EFT fitting dump
     * @return string|null The fitting name
     */
    public function getFittingName(string $eftFitting): ?string
    {
        $returnValue = null;

        if (!empty($eftFitting)) {
            $fittingArray = explode("\n", trim(StringHelper::getInstance()->fixLineBreaks($eftFitting)));

            $fittingArray['0'] = str_replace('[', '', $fittingArray['0']);
            $fittingArray['0'] = str_replace(']', '', $fittingArray['0']);
            $fittingArray['0'] = preg_replace('/(.*),/', '', $fittingArray['0']);

            $returnValue = trim($fittingArray['0']);
        }

        return $returnValue;
    }

    /**
     * Get the Item IDs for the fitting from EFT Import
     *
     * @param string $eftFitting
     * @return array[]|null
     */
    public function getSlotDataFromEftData(string $eftFitting): ?array
    {
        $returnValue = null;

        if (!empty($eftFitting)) {
            $fittingArray = $this->removeEmptySlotsFromEftFittingDataArray(
                explode("\n", trim(StringHelper::getInstance()->fixLineBreaks($eftFitting)))
            );

            $fittingArray['0'] = str_replace('[', '', $fittingArray['0']);
            $fittingArray['0'] = str_replace(']', '', $fittingArray['0']);
            $fittingArray['0'] = preg_replace('/,(.*)/', '', $fittingArray['0']);

            $countHighSlots = 1;
            $countMidSlots = 1;
            $countLowSlots = 1;
            $countRigSlots = 1;
            $countSubSystems = 1;
            $countUpwellServices = 1;
            $countCharges = 1;
            $countFuel = 1;
            $countImplantsAndBooster = 1;
            $countDrones = 1;

            $arrayHighSlots = [];
            $arrayMidSlots = [];
            $arrayLowSlots = [];
            $arrayRigSlots = [];
            $arraySubSystems = [];
            $arrayUpwellServices = [];
            $arrayCharges = [];
            $arrayFuel = [];
            $arrayImplantsAndBooster = [];
            $arrayDrones = [];

            foreach ($fittingArray as &$line) {
                $line = trim($line);

                if (!empty($line)) {
                    $itemCount = 1;
                    $line = trim(preg_replace('/,(.*)/', '', $line));

                    if (!empty($line)) {
                        /**
                         * If we have an item count, get it ...
                         */
                        if (preg_match('/ x[0-9]*/', $line, $matches)) {
                            $itemCount = str_replace('x', '', trim($matches['0']));
                            $line = trim(str_replace(trim($matches['0']), '', $line));
                        }

                        $itemDetail = FittingHelper::getInstance()->getItemDetailsByItemName($line, $itemCount);

                        switch ($itemDetail->slotName) {
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
                            case 'Service Slot':
                                $arrayUpwellServices['upwellService_' . $countUpwellServices] = $itemDetail->itemID;
                                $countUpwellServices++;
                                break;

                            case 'charge':
                                $arrayCharges['charge_' . $countCharges] = [
                                    'itemID' => $itemDetail->itemID,
                                    'itemCount' => $itemDetail->itemCount
                                ];
                                $countCharges++;
                                break;

                            case 'fuel':
                                $arrayFuel['fuel_' . $countFuel] = [
                                    'itemID' => $itemDetail->itemID,
                                    'itemCount' => $itemDetail->itemCount
                                ];
                                $countFuel++;
                                break;

                            case 'Implants and Booster':
                                $arrayImplantsAndBooster['implants_booster_' . $countImplantsAndBooster] = [
                                    'itemID' => $itemDetail->itemID,
                                    'itemCount' => $itemDetail->itemCount
                                ];
                                $countImplantsAndBooster++;
                                break;

                            case 'drone':
                                $arrayDrones['drone_' . $countDrones] = [
                                    'itemID' => $itemDetail->itemID,
                                    'itemCount' => $itemDetail->itemCount
                                ];
                                $countDrones++;
                                break;

                            default:
                                break;
                        }
                    }
                }
            }

            unset($line);

            $returnValue = [
                'highSlots' => $arrayHighSlots,
                'midSlots' => $arrayMidSlots,
                'lowSlots' => $arrayLowSlots,
                'rigSlots' => $arrayRigSlots,
                'subSystems' => $arraySubSystems,
                'upwellServices' => $arrayUpwellServices,
                'charges' => $arrayCharges,
                'fuel' => $arrayFuel,
                'implantsAndBooster' => $arrayImplantsAndBooster,
                'drones' => $arrayDrones
            ];
        }

        return $returnValue;
    }

    /**
     * Removing [Empty High|Med|Low Slot] lines
     *
     * Fixes GitHub issue #48
     *
     * @param array $eftFittingArray
     * @return array|null
     */
    private function removeEmptySlotsFromEftFittingDataArray(array $eftFittingArray): ?array
    {
        $returnData = null;

        foreach ($eftFittingArray as $line) {
            if (preg_match('/\[Empty/', $line)) {
                continue;
            }

            $returnData[] = $line;
        }

        return $returnData;
    }

    /**
     * Getting the ships DNA from the EFT dump
     *
     * @param string $eftFitting
     * @return string|null
     */
    public function getShipDnaFromEftData(string $eftFitting): ?string
    {
        $returnValue = null;

        if ($eftFitting !== null) {
            $fittingData = $this->getFittingArrayFromEftData(trim(StringHelper::getInstance()->fixLineBreaks($eftFitting)));
            $returnValue = FittingHelper::getInstance()->getShipDnaFromFittingData($fittingData);
        }

        return $returnValue;
    }

    /**
     * Generate a fitting array from the EFT dump
     *
     * @param string $eftFitting
     * @return array
     */
    public function getFittingArrayFromEftData(string $eftFitting): array
    {
        $fittingArray = $this->removeEmptySlotsFromEftFittingDataArray(explode("\n", trim(StringHelper::getInstance()->fixLineBreaks($eftFitting))));

        $fittingData = [];
        $fittingArray['0'] = str_replace('[', '', $fittingArray['0']);
        $fittingArray['0'] = str_replace(']', '', $fittingArray['0']);
        $fittingArray['0'] = preg_replace('/,(.*)/', '', $fittingArray['0']);

        foreach ($fittingArray as &$line) {
            $line = trim($line);

            if (!empty($line)) {
                $itemCount = 1;
                $line = trim(preg_replace('/,(.*)/', '', $line));

                if (!empty($line)) {
                    /**
                     * If we have an item count, get it ...
                     */
                    $matches = null;
                    if (preg_match('/ x[0-9]*/', $line, $matches)) {
                        $itemCount = str_replace('x', '', trim($matches['0']));
                        $line = trim(str_replace(trim($matches['0']), '', $line));
                    } // END if(preg_match('/ x[0-9]*/', $line, $matches))

                    $fittingData[] = FittingHelper::getInstance()->getItemDetailsByItemName($line, $itemCount);
                }
            }
        }

        return $fittingData;
    }

    /**
     * Getting the EFT dump from a saved fitting
     *
     * @param array $fitting
     * @param bool $withShipDna
     * @return array|string
     */
    public function getEftImportFromFitting(array $fitting, bool $withShipDna = false)
    {
        $arrayHighSlots = maybe_unserialize($fitting['highSlots']);
        $arrayMidSlots = maybe_unserialize($fitting['midSlots']);
        $arrayLowSlots = maybe_unserialize($fitting['lowSlots']);
        $arrayRigSlots = maybe_unserialize($fitting['rigSlots']);
        $arraySubSystems = maybe_unserialize($fitting['subSystems']);
        $arrayServiceSlots = maybe_unserialize($fitting['serviceSlots']);
        $arrayDrones = maybe_unserialize($fitting['drones']);
        $arrayCharges = maybe_unserialize($fitting['charges']);
        $arrayFuel = maybe_unserialize($fitting['fuel']);
        $arrayImplantsAndBooster = maybe_unserialize($fitting['implantsAndBooster']);

        $eftImport = '[' . FittingHelper::getInstance()->getItemNameById($fitting['shipID']) . ', ' . trim($fitting['fittingType']) . ']' . "\n";

        /**
         * Low Slots
         */
        if (is_array($arrayLowSlots)) {
            foreach ($arrayLowSlots as $lowSlot) {
                if ($lowSlot !== false) {
                    $eftImport .= FittingHelper::getInstance()->getItemNameById($lowSlot) . "\n";
                }
            }
        }

        /**
         * Mid Slots
         */
        if (is_array($arrayMidSlots) && count($arrayMidSlots) > 0) {
            $eftImport .= '' . "\n";

            foreach ($arrayMidSlots as $midSlot) {
                if ($midSlot !== false) {
                    $eftImport .= FittingHelper::getInstance()->getItemNameById($midSlot) . "\n";
                }
            }
        }

        /**
         * High Slots
         */
        if (is_array($arrayHighSlots) && count($arrayHighSlots) > 0) {
            $eftImport .= '' . "\n";

            foreach ($arrayHighSlots as $highSlot) {
                if ($highSlot !== false) {
                    $eftImport .= FittingHelper::getInstance()->getItemNameById($highSlot) . "\n";
                }
            }
        }

        /**
         * Rig Slots
         */
        if (is_array($arrayRigSlots) && count($arrayRigSlots) > 0) {
            $eftImport .= '' . "\n";

            foreach ($arrayRigSlots as $rigSlot) {
                if ($rigSlot !== false) {
                    $eftImport .= FittingHelper::getInstance()->getItemNameById($rigSlot) . "\n";
                }
            }
        }

        /**
         * Sub Systems
         */
        if (is_array($arraySubSystems) && count($arraySubSystems) > 0) {
            $eftImport .= '' . "\n";

            foreach ($arraySubSystems as $subSystem) {
                if ($subSystem !== false) {
                    $eftImport .= FittingHelper::getInstance()->getItemNameById($subSystem) . "\n";
                }
            }
        }

        /**
         * Service Slots
         */
        if (is_array($arrayServiceSlots) && count($arrayServiceSlots) > 0) {
            $eftImport .= '' . "\n";

            foreach ($arrayServiceSlots as $serviceSlot) {
                if ($serviceSlot !== false) {
                    $eftImport .= FittingHelper::getInstance()->getItemNameById($serviceSlot) . "\n";
                }
            }
        }

        /**
         * Drones
         */
        if (is_array($arrayDrones) && count($arrayDrones) > 0) {
            $eftImport .= '' . "\n";

            foreach ($arrayDrones as $drone) {
                if ($drone !== false) {
                    $eftImport .= FittingHelper::getInstance()->getItemNameById($drone['itemID']) . ' x' . $drone['itemCount'] . "\n";
                }
            }
        }

        /**
         * Charges
         */
        if (is_array($arrayCharges) && count($arrayCharges) > 0) {
            $eftImport .= '' . "\n";

            foreach ($arrayCharges as $charge) {
                if ($charge !== false) {
                    $eftImport .= FittingHelper::getInstance()->getItemNameById($charge['itemID']) . ' x' . $charge['itemCount'] . "\n";
                }
            }
        }

        /**
         * Fuel
         */
        if (is_array($arrayFuel) && count($arrayFuel) > 0) {
            $eftImport .= '' . "\n";

            foreach ($arrayFuel as $fuel) {
                if ($fuel !== false) {
                    $eftImport .= FittingHelper::getInstance()->getItemNameById($fuel['itemID']) . ' x' . $fuel['itemCount'] . "\n";
                }
            }
        }

        /**
         * Implants and Booster
         */
        if (is_array($arrayImplantsAndBooster) && count($arrayImplantsAndBooster) > 0) {
            $eftImport .= '' . "\n";

            foreach ($arrayImplantsAndBooster as $item) {
                if ($item !== false) {
                    $eftImport .= FittingHelper::getInstance()->getItemNameById($item['itemID']) . ' x' . $item['itemCount'] . "\n";
                }
            }
        }

        $returnValue = $eftImport;

        if ($withShipDna === true) {
            $returnValue = [
                'eftImport' => $eftImport,
                'shipDna' => $fitting['shipDNA']
            ];
        }

        return $returnValue;
    }
}
