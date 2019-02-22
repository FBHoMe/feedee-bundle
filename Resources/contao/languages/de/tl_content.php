<?php
/**
 * Created by PhpStorm.
 * User: fb
 * Date: 14.02.2019
 * Time: 12:10
 */

$moduleName = 'tl_content';

// #-- Legends
$GLOBALS['TL_LANG'][$moduleName]['feedee_legend'] = 'Feedee';

// #-- Fields
$GLOBALS['TL_LANG'][$moduleName]['hm_fed_url'] = array('RSS Feed Url', 'Url des zu ladenden Feeds');
$GLOBALS['TL_LANG'][$moduleName]['hm_fed_fields'] = array('RSS Tags', 'Tags des Feeds, die im Template zur Verfügung stehen sollen. Einzelne FeedTags kommasepariert. NameImTemplate::FeedTagName || NameImTemplate::*child++media__attr++url*');
$GLOBALS['TL_LANG'][$moduleName]['hm_fed_allowed_tags'] = array('Erlaubte Tags', 'HTML-Tags, die in den FeedTags verwendet werden dürfen. Alle anderen werden rausgefiltert (inkl. deren Content)');
