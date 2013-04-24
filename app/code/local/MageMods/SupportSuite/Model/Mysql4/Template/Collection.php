<?php
/**
 * MageMods
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageMods EULA that is bundled with
 * this package in the file LICENSE.txt. It is also available through
 * the world-wide-web at this URL: http://www.magemods.co/LICENSE-1.0.txt
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to sales@magemods.co so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension to
 * newer versions in the future. If you wish to customize the extension for your
 * needs please refer to http://www.magemods.co/ for more information.
 */

class MageMods_SupportSuite_Model_Mysql4_Template_Collection
    extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('supportsuite/template');
    }

    public function toOptionArray()
    {
        $options = array();

        if (!$this->isLoaded()) {
            $this->getSelect()
                 ->reset(Zend_Db_Select::COLUMNS)
                 ->columns(array('template_id', 'title'));
        }

        foreach ($this as $item) {
            $options[] = array(
                'value' => $item->getId(),
                'label' => $item->getTitle()
            );
        }

        return $options;
    }
}
