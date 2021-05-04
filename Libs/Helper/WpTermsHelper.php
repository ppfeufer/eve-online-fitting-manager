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
use WP_Term;

defined('ABSPATH') or die();

class WpTermsHelper extends AbstractSingleton
{
    /**
     * Get the term root
     *
     * @param WP_Term $term
     * @return WP_Term
     */
    public function getTermRoot(WP_Term $term): WP_Term
    {
        // Start from the current term
        $rootTerm = $term;

        // Climb up the hierarchy until we reach a term with parent = '0'
        while ($rootTerm->parent !== 0) {
            $rootTerm = get_term($rootTerm->parent, $rootTerm->taxonomy);
        }

        return $rootTerm;
    }

    /**
     * Get term breadcrumb as array
     *
     * @param WP_Term $term
     * @return array
     */
    public function getTermBreadcrumbArray(WP_Term $term): array
    {
        $termHierarchy = [];
        $parentTerm = $term;

        while ($parentTerm->parent !== 0) {
            $parentTerm = $this->getTermParent($parentTerm);
            $termHierarchy[] = $parentTerm;
        }

        return array_reverse($termHierarchy);
    }

    /**
     * Get the term parent
     *
     * @param WP_Term $term
     * @return WP_Term
     */
    public function getTermParent(WP_Term $term): WP_Term
    {
        // Start from the current term
        $parentTerm = $term;

        // Climb up the hierarchy until we reach a term with parent = '0'
        if ($parentTerm->parent !== 0) {
            $parentTerm = get_term($parentTerm->parent, $parentTerm->taxonomy);
        }

        return $parentTerm;
    }
}
