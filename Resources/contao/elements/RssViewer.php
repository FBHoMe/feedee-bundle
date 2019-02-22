<?php
/**
 * Created by PhpStorm.
 * User: felix
 * Date: 18.09.2017
 * Time: 15:22
 */

namespace Home\FeedeeBundle\Resources\contao\elements;

use Home\PearlsBundle\Resources\contao\Helper\DataHelper;

class RssViewer extends \Contao\ContentElement
{
    /**
     * @var string
     */
    protected $strTemplate = 'cte_rss_viewer';

    /**
     * @return string
     */
    public function generate()
    {
        return parent::generate();
    }

    /**
     * Generate module
     */
    protected function compile()
    {
        if (TL_MODE == 'BE') {
            $this->generateBackend();
        } else {
            $this->generateFrontend();
        }
    }

    /**
     * generate backend for module
     */
    private function generateBackend()
    {
        $this->Template             = new \BackendTemplate('be_wildcard');
        $this->Template->title      = 'RSS Viewer';
        $this->Template->wildcard   = $this->hm_fed_url;
    }

    /**
     * generate frontend for module
     */
    private function generateFrontend()
    {
        $xml = simplexml_load_file($this->hm_fed_url);

        $this->Template->channel_title = $xml->channel->title;
        $this->Template->channel_link = $xml->channel->link;
        $this->Template->channel_desc = $xml->channel->description;

        #-- get the fields from the be settings
        $fields = self::extractFields();

        #-- get the field values
        $this->Template->feeds = self::getItemValues($xml->channel->item, $fields);

    }

    /**
     * extract the fields from the dca field hm_fed_fields
     * where all fields are separated by ',' and the key (template name) and the value (xml tag) are separated by '::' e.g. 'title::title,desc::description
     * if field has an element prefix 'media:thumbnail' the result has to be an array e.g. 'img::*child++media__attr++url*
     *
     */
    private function extractFields()
    {
        $result = array();
        #-- get the fields
        $fields = explode(',', $this->hm_fed_fields);

        #-- get the key/values as array
        if (is_array($fields) && count($fields) > 0) {
            foreach($fields as $string) {
                $xmlField = explode('::', $string);
                $xmlField = self::trimArray($xmlField);

                #-- tagName is an 'array'
                if (strpos($xmlField[1], '*') > -1) {
                    $xmlField[1] = substr($xmlField[1], 1, strlen($xmlField[1]) - 2);
                    $prefixValues = explode('__', $xmlField[1]);
                    $prefixValues = self::trimArray($prefixValues);

                    if (is_array($prefixValues) && count($prefixValues) > 0) {
                        $new = array();
                        foreach ($prefixValues as $prefix) {
                            $values = explode('++', $prefix);
                            $values = self::trimArray($values);
                            $new[$values[0]] = $values[1];
                        }
                    }
                    $xmlField[1] = $new;
                }

                $result[$xmlField[0]] = $xmlField[1];
            }
        }

        return $result;
    }

    private function trimArray($array)
    {
        if (is_array($array)) {
            foreach ($array as $k => $v) {
                $k = trim($k, "\x00..\x1F");
                $v = trim($v, "\x00..\x1F");
                $array[$k] = $v;
            }
        }

        return $array;
    }

    /**
     * get all item values specified in fields
     * @param $xmlItems
     * @return array
     */
    private function getItemValues($xmlItems, $fields) {

        $feeds = array();
        foreach ($xmlItems as $item) {
            $array = array();
            foreach($fields as $name=>$field) {
                if (is_array($field) && array_key_exists('child', $field) && array_key_exists('attr', $field)) {
                    $attr = $field['attr'];
                    $array[$name] = $item->children($field['child'], True)->attributes()->$attr->__toString();
                } else {
                    $array[$name] = strip_tags(html_entity_decode($item->$field->__toString(), ENT_NOQUOTES, $GLOBALS['TL_CONFIG']['characterSet']), $this->hm_fed_allowed_tags);
                }
            }
            $feeds[] = $array;
        }
        return $feeds;
    }
}