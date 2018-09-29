<?php

/**
 * Copyright (C) 2017 Rounon Dax
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

namespace WordPress\Plugins\EveOnlineFittingManager\Libs\Helper;

\defined('ABSPATH') or die();

class ImageHelper extends \WordPress\Plugins\EveOnlineFittingManager\Libs\Singletons\AbstractSingleton {
    /**
     * base URL to CCP's image server
     *
     * @var var
     */
    protected $imageserverUrl = null;

    /**
     * Array with possible end point on CCP's image server
     *
     * @var array
     */
    protected $imageserverEndpoints = null;

    /**
     * Plugin settings
     *
     * @var array
     */
    protected $pluginSettings = null;

    /**
     * The Construtor
     */
    protected function __construct() {
        parent::__construct();

        $this->imageserverUrl = 'https://image.eveonline.com/';
        $this->imageserverEndpoints = $this->getImageserverEndpoints();
        $this->pluginSettings = PluginHelper::getPluginSettings(true);
    }

    /**
     * Assigning Imagesever Endpoints
     */
    private function getImageserverEndpoints() {
        return [
            'alliance' => 'Alliance/',
            'corporation' => 'Corporation/',
            'character' => 'Character/',
            'item' => 'Type/',
            'ship' => 'Render/',
            'inventory' => 'InventoryType/' // all the other stuff
        ];
    }

    /**
     * Getting the EVE API Url
     *
     * @param string $type
     * @return string The EVE API Url
     */
    public function getImageServerUrl($type = null) {
        $endpoint = '';

        if($type !== null) {
            $endpoint = $this->imageserverEndpoints[$type];
        }

        return $this->imageserverUrl . $endpoint;
    }

    /**
     * Getting the cached URL for a remote image
     *
     * @param string $cacheType The subdirectory in the image cache filesystem
     * @param string $remoteImageUrl The URL for the remote image
     * @return string The cached Image URL
     */
    public function getLocalCacheImageUriForRemoteImage($cacheType = null, $remoteImageUrl = null) {
        $returnValue = $remoteImageUrl;

        // Check if we should use image cache first
        if(isset($this->pluginSettings['template-image-settings']['use-image-cache']) && $this->pluginSettings['template-image-settings']['use-image-cache'] === 'yes') {
            $explodedImageUrl = \explode('/', $remoteImageUrl);
            $imageFilename = \end($explodedImageUrl);
            $cachedImage = CacheHelper::getInstance()->getImageCacheUri() . $cacheType . '/' . $imageFilename;

            // if we don't have the image cached already
            if(CacheHelper::getInstance()->checkCachedImage($cacheType, $imageFilename) === false) {
                /**
                 * Check if the content dir is writable and cache the image.
                 * Otherwise set the remote image as return value.
                 */
                if(\is_dir(CacheHelper::getInstance()->getImageCacheDir() . $cacheType) && \is_writable(CacheHelper::getInstance()->getImageCacheDir() . $cacheType)) {
                    if(CacheHelper::getInstance()->cacheRemoteImageFile($cacheType, $remoteImageUrl) === true) {
                        $returnValue = $cachedImage;
                    }
                }
            } else {
                $returnValue = $cachedImage;
            }
        }

        return $returnValue;
    }
}
