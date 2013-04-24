<?php
class Yougento_S2b_Model_Product_Type extends Mage_Catalog_Model_Product_Type {

	public function toOptionArray(){
		return parent::getAllOptions();
	}

	static public function getOptionArray()
	{
		$roleId = implode('', Mage::getSingleton('admin/session')->getUser()->getRoles());
		$roleName = Mage::getModel('admin/roles')->load($roleId)->getRoleName();
		if ($roleName != 'Administrators'){
			//we remove the disabled options
			$roleId = implode('', Mage::getSingleton('admin/session')->getUser()->getRoles());
			$customConfig = Mage::getModel('s2b/configs')->load($roleId,'vroleid');
			$a_avalable_types = $customConfig->getProducttypes();
			if($a_avalable_types){
				$a_avalable_types = explode(',', $customConfig->getProducttypes());
			}
			$options = array();
			foreach(self::getTypes() as $typeId=>$type) {
				if (in_array($typeId, $a_avalable_types))
					$options[$typeId] = Mage::helper('catalog')->__($type['label']);
			}

			return $options;
		}
		$res = parent::getOptionArray();
		return $res;
	}

	/**
	 * Retrive all attribute options
	 *
	 * @return array
	 */
	static public function getAllOptions() {
		//we will remove the unneeded options
		$res = parent::getAllOptions();
		return $res;
	}


	/**
	 * Returns label for value
	 * @param string $value
	 * @return string
	 */
	public function getLabel($value) {
		return parent::getOptionText($value);
	}

	/**
	 * Returns array ready for use by grid
	 * @return array
	 */
	public function getGridOptions() {
		$items = $this->getAllOptions();
		$out = array();
		foreach($items as $item) {
			$out[$item['value']] = $item['label'];
		}
		return $out;
	}
}
