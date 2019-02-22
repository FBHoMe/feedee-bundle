<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 27.09.2017
 * Time: 16:33
 */

namespace Home\FeedeeBundle\Resources\contao\dca;

use Home\PearlsBundle\Resources\contao\Helper\Dca as Helper;

$tl_content = new Helper\DcaHelper('tl_content');

$tl_content
    ->addField('text', 'hm_fed_url')
    ->addField('textarea', 'hm_fed_fields')
    ->addField('textarea', 'hm_fed_allowed_tags', array(
        'default'   => &$GLOBALS['TL_CONFIG']['allowedTags'],
        'eval'      => array(
            'preserveTags' => true,
            'tl_class'     => 'clr',
            'mandatory'    => false,
            'tl_class'     => 'long',
        ),
    ))
    ->copyPalette('default', 'RssViewer')
    ->addPaletteGroup('feedee', array('hm_fed_url','hm_fed_fields', 'hm_fed_allowed_tags'), 'RssViewer')
    ->addPaletteGroup('template', array('customTpl'), 'RssViewer')
;