<?php
class Yougento_S2b_Model_Mysql4_Configs extends Mage_Core_Model_Mysql4_Abstract
{
 
     public function _construct()
     {
 
         $this->_init('s2b/configs','configid');
     }
 
}