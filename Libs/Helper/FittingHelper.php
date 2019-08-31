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

use \stdClass;
use \WordPress\Plugins\EveOnlineFittingManager\Libs\Singletons\AbstractSingleton;
use \WP_Query;

\defined('ABSPATH') or die();

class FittingHelper extends AbstractSingleton {
    /**
     * Getting Item Description
     *
     * @param int $itemID
     * @return array
     */
    public function getItemDescription(int $itemID) {
        $returnValue = null;

        /* @var $itemData \WordPress\EsiClient\Model\Universe\Types\TypeId */
        $itemData = EsiHelper::getInstance()->getItemTypeInformation($itemID);

        if(!\is_null($itemData)) {
            $returnValue = $itemData->getDescription();
        }

        return \wpautop($returnValue);
    }

    /**
     * Getting Item Details
     *
     * @param string $itemName
     * @param int $itemCount
     * @return object|boolean
     */
    public function getItemDetailsByItemName(string $itemName, int $itemCount = 1) {
        $itemId = \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\FittingHelper::getInstance()->getItemIdByName($itemName, 'inventoryTypes');

        /**
         * continue only if we have a valid itemID
         *
         * GitHug issue #48
         */
        if(!\is_null($itemId)) {
            $itemEsiData = EsiHelper::getInstance()->getItemDataByItemId($itemId);

            if(!\is_null($itemEsiData['itemTypeInformation']) && !\is_null($itemEsiData['itemGroupInformation']) && !\is_null($itemEsiData['itemCategoryInformation'])) {
                $returnData = new stdClass;

                $returnData->itemID = $itemId;
                $returnData->groupID = $itemEsiData['itemTypeInformation']->getGroupId();
                $returnData->categoryID = $itemEsiData['itemGroupInformation']->getCategoryId();
                $returnData->itemName = $itemEsiData['itemTypeInformation']->getName();
                $returnData->itemDescription = $itemEsiData['itemTypeInformation']->getDescription();

                /**
                 * Category: Fuel
                 */
                $arrayFuelIDs = [
                    '16273', // Liquid Ozone
                    '16274', // Helium Isotopes
                    '17889', // Hydrogen Isotopes
                    '17887', // Oxygen Isotopes
                    '17888', // Nitrogen Isotopes
                    '16272', // Heavy Water
                    '16275'  // Strontuim Clathrates
                ];

                switch($returnData->categoryID) {
                    case 6:
                        $returnData->slotName = 'ship';
                        break;

                    case 8:
                        $returnData->slotName = 'charge';
                        break;

                    case 18;
                        $returnData->slotName = 'drone';
                        break;

                    case 20;
                        $returnData->slotName = 'Implants and Booster';
                        break;
                }

                if(\in_array($returnData->itemID, $arrayFuelIDs)) {
                    $returnData->slotName = 'fuel';
                }

                // in case we still don't have any slot information ...
                if(!isset($returnData->slotName)) {
                    foreach($itemEsiData['itemTypeInformation']->getDogmaEffects() as $dogmaEffect) {
                        switch($dogmaEffect->getEffectId()) {
                            // low power
                            case 11:
                                $returnData->slotName = 'Low power';
                                $returnData->slotEffectID = $dogmaEffect->getEffectId();
                                break;

                            // high power
                            case 12:
                                $returnData->slotName = 'High power';
                                $returnData->slotEffectID = $dogmaEffect->getEffectId();
                                break;

                            // medium power
                            case 13:
                                $returnData->slotName = 'Medium power';
                                $returnData->slotEffectID = $dogmaEffect->getEffectId();
                                break;

                            // rig slot
                            case 2663:
                                $returnData->slotName = 'Rig Slot';
                                $returnData->slotEffectID = $dogmaEffect->getEffectId();
                                break;

                            // sub system
                            case 3772:
                                $returnData->slotName = 'Sub System';
                                $returnData->slotEffectID = $dogmaEffect->getEffectId();
                                break;
                        }
                    }
                }

                if($itemCount != null) {
                    $returnData->itemCount = $itemCount;
                }

                return $returnData;
            }
        }

        return false;
    }

    /**
     * Getting an item ID ny its item name
     *
     * @param string $itemName
     * @return type
     */
    public function getItemIdByName($itemName, $eveCategory) {
        $returnValue = null;

        /* @var $esiResult \WordPress\EsiClient\Model\Universe\Ids\InventoryTypes */
        $esiResult = EsiHelper::getInstance()->getIdFromName([$itemName], $eveCategory);

        if(\is_a($esiResult['0'], '\WordPress\EsiClient\Model\Universe\Ids\InventoryTypes')) {
            $returnValue = $esiResult['0']->getId();
        }

        return $returnValue;
    }

    /**
     * Getting an item name by its iten ID
     *
     * @param int|array $itemID
     * @return type
     */
    public function getItemNameById($itemID) {
        if(\is_array($itemID)) {
            $itemNames = [];

            foreach($itemID as $id) {
                /* @var $esiResult \WordPress\EsiClient\Model\Universe\Types\TypeId */
                $esiResult = EsiHelper::getInstance()->getItemTypeInformation($itemID);

                if(!\is_null($esiResult)) {
                    $itemNames[$id] = $esiResult->getName();
                }
            }

            return $itemNames;
        }

        /* @var $esiResult UniverseTypesTypeId */
        $esiResult = EsiHelper::getInstance()->getItemTypeInformation($itemID);

        if(!\is_null($esiResult)) {
            $itemName = $esiResult->getName();
        }

        return $itemName;
    }

    /**
     * Getting a fitting DNA from its fitting data
     *
     * @param object $fittingData
     * @return string
     */
    public function getShipDnaFromFittingData($fittingData) {
        $highSlots = [];
        $midSlots = [];
        $lowSlots = [];
        $subSystems = [];
        $rigSlots = [];
        $shipData = [];
        $charges = [];
        $drones = [];

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
            }
        }

        $shipDnaString = '';
        $shipDnaString .= $shipData->itemID . ':';

        /**
         * Subsystems, if its a T3 Tactical Cruiser
         */
        if(!empty($subSystems)) {
            foreach($subSystems as $sub) {
                $shipDnaString .= $sub->itemID . ';' . $sub->itemCount . ':';
            }
        }

        /**
         * Highslots
         */
        if(!empty($highSlots)) {
            foreach($highSlots as $high) {
                $shipDnaString .= $high->itemID . ';' . $high->itemCount . ':';
            }
        }

        /**
         * Medslots
         */
        if(!empty($midSlots)) {
            foreach($midSlots as $mid) {
                $shipDnaString .= $mid->itemID . ';' . $mid->itemCount . ':';
            }
        }

        /**
         * Lowslots
         */
        if(!empty($lowSlots)) {
            foreach($lowSlots as $low) {
                $shipDnaString .= $low->itemID . ';' . $low->itemCount . ':';
            }
        }

        /**
         * Rigs
         */
        if(!empty($rigSlots)) {
            foreach($rigSlots as $rig) {
                $shipDnaString .= $rig->itemID . ';' . $rig->itemCount . ':';
            }
        }

        /**
         * Charges
         */
        if(!empty($charges)) {
            foreach($charges as $charge) {
                $shipDnaString .= $charge->itemID . ';' . $charge->itemCount . ':';
            }
        }

        /**
         * Drones
         */
        if(!empty($drones)) {
            foreach($drones as $drone) {
                $shipDnaString .= $drone->itemID . ';' . $drone->itemCount . ':';
            }
        }

        $shipDnaString .= ':';

        return $shipDnaString;
    }

    /**
     * Getting the slot layout from fitting data
     *
     * @param array $fitting
     * @return type
     */
    public function getSlotLayoutFromFittingArray($fitting) {
        $shipSlotLayout = $this->getShipSlotLayout($fitting['shipID']);

        $currentHighSlots = $shipSlotLayout['hiSlots'];
        $currenMidSlots = $shipSlotLayout['medSlots'];
        $currentLowSlots = $shipSlotLayout['loSlots'];
        $currentRigSlots = $shipSlotLayout['rigSlots'];

        $currentSubSystems = 0;
        $currentServiceSlots = 0;

        $fittedSubSystems = \maybe_unserialize($fitting['subSystems']);
        $fittedServiceSlots = \maybe_unserialize($fitting['serviceSlots']);

        $arrayStrategicCruiserIDs = $this->getStrategicCruiserIds();
        $arrayUpwellStructureIDs = $this->getUpwellStructureIds();

        /**
         * Check if:
         *      it's a strategic cruiser and has sub systems
         *      it's an Upwell Structure
         */
        if(\in_array($fitting['shipID'], $arrayStrategicCruiserIDs) && !empty($fittedSubSystems)) {
            /**
             * Processing Strategic Cruiser
             * Those ships have their slot layout from their subsystem modifiers
             */
            $maxSubSystems = 4;

            for($i = 1; $i <= $maxSubSystems; $i++) {
                if(isset($fittedSubSystems['subSystem_' . $i])) {
                    $subSystemModifier = $this->getSubsystemSlotModifier($fittedSubSystems['subSystem_' . $i]);

                    $currentHighSlots += $subSystemModifier['hiSlots'];
                    $currenMidSlots += $subSystemModifier['medSlots'];
                    $currentLowSlots += $subSystemModifier['loSlots'];
                }
            }

            $currentSubSystems = 4;
        } elseif(\in_array($fitting['shipID'], $arrayUpwellStructureIDs) && !empty($fittedServiceSlots)) {
            /**
             * Processing Upwell Structures
             */
            $currentServiceSlots = 5;
        }

        return [
            'highSlots' => $currentHighSlots,
            'midSlots' => $currenMidSlots,
            'lowSlots' => $currentLowSlots,
            'rigSlots' => $currentRigSlots,
            'subSystems' => $currentSubSystems,
            'serviceSlots' => $currentServiceSlots
        ];
    }

    private function getStrategicCruiserIds() {
        return [
            29984, // Tengu
            29986, // Legion
            29988, // Proteus
            29990 // Loki
        ];
    }

    private function getUpwellStructureIds() {
        return [
            35832, // Astrahus
            35833, // Fortizar
            35834, // Keepstar

            47512, // 'Moreau' Fortizar
            47513, // 'Draccous' Fortizar
            47514, // 'Horizon' Fortizar
            47515, // 'Marginis' Fortizar
            47516, // 'Prometheus' Fortizar

            35825, // Raitaru
            35826, // Azbel
            35827, // Sotiyo

            35835, // Athanor
            35836 // Tatara
        ];
    }

    /**
     * get ship slot layout
     *
     * @param int $shipId
     * @return array
     */
    public function getShipSlotLayout(int $shipId) {
        $returnValue = [
            'hiSlots' => 0,
            'medSlots' => 0,
            'loSlots' => 0,
            'rigSlots' => 0
        ];

        $shipData = EsiHelper::getInstance()->getItemTypeInformation($shipId);

        if(\is_a($shipData, '\WordPress\EsiClient\Model\Universe\Types\TypeId')) {
            foreach($shipData->getDogmaAttributes() as $dogmaAttribute) {
                switch($dogmaAttribute->getAttributeId()) {
                    // hiSlots
                    case 14;
                        $returnValue['hiSlots'] = $dogmaAttribute->getValue();
                        break;

                    // medSlots
                    case 13;
                        $returnValue['medSlots'] = $dogmaAttribute->getValue();
                        break;

                    // loSlots
                    case 12;
                        $returnValue['loSlots'] = $dogmaAttribute->getValue();
                        break;

                    // rigSlots
                    case 1137;
                        $returnValue['rigSlots'] = $dogmaAttribute->getValue();
                        break;
                }
            }
        }

        return $returnValue;
    }

    /**
     * get slot modifier from a asub system
     *
     * @param int $subsystemID
     * @return array
     */
    public function getSubsystemSlotModifier(int $subsystemID) {
        $returnValue = [
            'hiSlots' => 0,
            'medSlots' => 0,
            'loSlots' => 0
        ];

        $subsystemData = EsiHelper::getInstance()->getItemTypeInformation($subsystemID);

        if(\is_a($subsystemData, '\WordPress\EsiClient\Model\Universe\Types\TypeId')) {
            foreach($subsystemData->getDogmaAttributes() as $dogmaAttribute) {
                switch($dogmaAttribute->getAttributeId()) {
                    // hiSlots
                    case 1374;
                        $returnValue['hiSlots'] = $dogmaAttribute->getValue();
                        break;

                    // medSlots
                    case 1375;
                        $returnValue['medSlots'] = $dogmaAttribute->getValue();
                        break;

                    // loSlots
                    case 1376;
                        $returnValue['loSlots'] = $dogmaAttribute->getValue();
                        break;
                }
            }
        }

        return $returnValue;
    }

    /**
     * Getting the ship image by ID
     *
     * @param type $itemID
     * @param type $size
     * @return type
     */
    public function getShipImageById($itemID = null, $size = 512) {
        $image = ImageHelper::getInstance()->getImageServerUrl('inventory') . $itemID . '_' . $size . '.png';

        return $image;
    }

    /**
     * Gettng the doctrine menu for the sidebar
     *
     * @param string $taxonomy
     * @return string
     */
    public function getSidebarMenu($taxonomy) {
        $entityListHtml = '<ul class="sidebar-doctrine-list doctrine-list menu-' . $taxonomy . '">';

        // get all taxonomy terms
        $entities = \get_terms([
            'taxonomy' => $taxonomy,
            'orderby' => 'name',
            'order' => 'ASC'
        ]);

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
                $doctrines = \get_terms([
                    'taxonomy' => $taxonomy,
                    'orderby' => 'name',
                    'order' => 'ASC',
                    'child_of' => $entity->term_id
                ]);

                $doctrineListHtml = '<li class="doctrine entity-' . $entity->slug . ' doctrine-id-' . $entity->term_id . ' has-children" data-doctrine="' . $entity->slug . '"><a class="doctrine-link-item" href="' . \get_term_link($entity->term_id) . '">' . $entity->name . '</a><span class="caret dropdown-toggle" data-toggle="dropdown"><i></i></span>';
                $doctrineListHtml .= '<ul class="dropdown-menu child-doctrine-list">';

                foreach($doctrines as $doctrine) {
                    if($doctrine->parent && $doctrine->parent !== $entity->term_id) {
                        continue;
                    }

                    $wingListHtml = '<li class="doctrine entity-' . $entity->slug . ' doctrine-' . $doctrine->slug . ' doctrine-id-' . $doctrine->term_id . '" data-doctrine="' . $doctrine->slug . '"><a class="doctrine-link-item" href="' . \get_term_link($doctrine->term_id) . '">' . $doctrine->name . '</a></li>';

                    if(isset($hierarchy[$doctrine->term_id])) {
                        $wings = \get_terms([
                            'taxonomy' => $taxonomy,
                            'orderby' => 'name',
                            'order' => 'ASC',
                            'child_of' => $doctrine->term_id
                        ]);

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
    public function getContentMenu($taxonomy) {
        $pluginOptions = PluginHelper::getInstance()->getPluginSettings();
        $uniqueID = \uniqid();

        $entityListHtml = '<div class="gallery-row row">';
        $entityListHtml .= '<ul class="content-doctrine-list doctrine-list menu-' . $taxonomy . ' bootstrap-gallery bootstrap-post-loop-fittings bootstrap-post-loop-fittings-' . $uniqueID . ' clearfix">';

        // get all taxonomy terms
        $entities = \get_terms([
            'taxonomy' => $taxonomy,
            'orderby' => 'name',
            'order' => 'ASC'
        ]);

        // get terms that have children
        $hierarchy = \_get_term_hierarchy($taxonomy);

        // Loop through every entity
        foreach($entities as $entity) {
            // skip term if it has children or is empty
            if($entity->parent) {
                continue;
            }

            $doctrineListHtml = '<li class="doctrine entity-' . $entity->slug . ' doctrine-id-' . $entity->term_id . '"><header class="entry-header"><h2 class="entry-title"><a class="doctrine-link-item" href="' . \get_term_link($entity->term_id) . '">' . $entity->name . '</a></h2></header></li>';
            $doctrineImage = null;

            if(isset($pluginOptions['template-image-settings']['show-doctrine-images-in-loop']) && $pluginOptions['template-image-settings']['show-doctrine-images-in-loop'] === 'yes') {
                if(\function_exists('\z_taxonomy_image')) {
                    $doctrineImage .= '<a class="doctrine-link-item" href="' . \get_term_link($entity->term_id) . '"><figure class="fitting-manager-post-loop-thumbnail">';

                    if(\function_exists('\fly_get_attachment_image')) {
                        $doctrineImage .= \fly_get_attachment_image(\z_get_attachment_id_by_url(\z_taxonomy_image_url($entity->term_id)), 'fitting-manager-post-loop-thumbnail');
                    } else {
                        $doctrineImage .= \z_taxonomy_image($entity->term_id, 'fitting-manager-post-loop-thumbnail', null, false);
                    }

                    $doctrineImage .= '</figure><header class="entry-header"><h2 class="entry-title">' . $entity->name . '</h2></header></a>';
                }

                $doctrineListHtml = '<li class="doctrine entity-' . $entity->slug . ' doctrine-id-' . $entity->term_id . '">' . $doctrineImage . '</li>';
            }

            // If the entity has doctrines...
            if(isset($hierarchy[$entity->term_id])) {
                $doctrines = \get_terms([
                    'taxonomy' => $taxonomy,
                    'orderby' => 'name',
                    'order' => 'ASC',
                    'child_of' => $entity->term_id
                ]);

                $doctrineListHtml = '<li class="doctrine entity-' . $entity->slug . ' doctrine-id-' . $entity->term_id . ' has-children">' . $doctrineImage . '<header class="entry-header"><h2 class="entry-title"><a class="doctrine-link-item" href="' . \get_term_link($entity->term_id) . '">' . $entity->name . '</a></h2></header>';

                if(isset($pluginOptions['template-image-settings']['show-doctrine-images-in-loop']) && $pluginOptions['template-image-settings']['show-doctrine-images-in-loop'] === 'yes') {
                    $doctrineListHtml = '<li class="doctrine entity-' . $entity->slug . ' doctrine-id-' . $entity->term_id . '">' . $doctrineImage;
                }

                $doctrineListHtml .= '<div class="child-doctrine-list">';

                foreach($doctrines as $doctrine) {
                    if($doctrine->parent && $doctrine->parent !== $entity->term_id) {
                        continue;
                    }

                    $wingListHtml = '<div class="doctrine sub-first-level doctrine entity-' . $entity->slug . ' doctrine-' . $doctrine->slug . ' doctrine-id-' . $doctrine->term_id . '"><a class="doctrine-link-item" href="' . \get_term_link($doctrine->term_id) . '">' . $doctrine->name . '</a></div>';

                    if(isset($hierarchy[$doctrine->term_id])) {
                        $wings = \get_terms([
                            'taxonomy' => $taxonomy,
                            'orderby' => 'name',
                            'order' => 'ASC',
                            'child_of' => $doctrine->term_id
                        ]);

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
                                    "classes" : "' . PluginHelper::getInstance()->getLoopContentClasses() . '",
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
    public function getFittingSearchQuery($escaped = true) {
        $query = \apply_filters('get_fitting_search_query', \get_query_var('fitting_search'));

        if($escaped === true) {
            $query = \esc_attr($query);
        }

        return $query;
    }

    public function searchFittings() {
        $args = [
            'post_type' => 'fitting',
            'posts_per_page' => -1,
            '_meta_or_title' => $this->getFittingSearchQuery(),
            'compare' => 'LIKE',
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key' => 'eve-online-fitting-manager_ship_type',
                    'value' => $this->getFittingSearchQuery(),
                    'compare' => 'LIKE'
                ],
                [
                    'key' => 'eve-online-fitting-manager_fitting_name',
                    'value' => $this->getFittingSearchQuery(),
                    'compare' => 'LIKE'
                ]
            ],
            'orderby' => 'title',
            'order' => 'ASC'
        ];

        return new WP_Query($args);
    }

    /**
     * Getting a list of doctrines in whic hthe ship is used
     *
     * @return array
     */
    public function getShipUsedInDoctrine() {
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
    public function isUpwellStructure($shipID) {
        return \in_array($shipID, $this->getUpwellStructureIds());
    }
}
