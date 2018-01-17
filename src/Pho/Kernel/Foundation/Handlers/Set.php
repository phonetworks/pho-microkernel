<?php

/**
 * This file is part of the Phở package.
 * 
 * (c) Emre Sokullu <emre@phonetworks.org> 
 *
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code.
 */

namespace Pho\Kernel\Foundation\Handlers;

use Pho\Framework\ParticleInterface;
use Pho\Framework\FieldHelper;

/**
 * Kernel adapter of the Set Handler class.
 * 
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class Set extends \Pho\Framework\Handlers\Set {

    /**
     * {@inheritDoc}
     * 
     * @todo Find a better way of accessing kernel than calling $GLOBALS
     */
    protected static function saveField(
        ParticleInterface $particle, 
        string $field_name, 
        /*mixed*/ $field_value, 
        bool $defer_persist, 
        FieldHelper $helper): void
    {
        $kernel = $GLOBALS["kernel"]; 
        if($helper->isUnique()) {
            // check if it is unique via index
            //eval(\Psy\sh());
            if($kernel->live() && !$kernel->index()->checkNodeUniqueness($field_name, $field_value, $particle->label())) {
                throw new \InvalidArgumentException("Given field is not unique");
            }
        }
        elseif($helper->withIndex()) {
            // check if there is an index
        }
        parent::saveField($particle, $field_name, $field_value, $defer_persist, $helper);
        
        /*if(!$defer_persist) {
            $particle->attributes()->$field_name = $field_value;
            return;
        }
        $particle->attributes()->quietSet($field_name, $field_value);
        */
    }

}
