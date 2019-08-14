<?php
/**
 
 *
 * @category   CP
 * @package    CP_Catalog
 * @author     CP
 */ 
class Kvh_Simpleseo_Block_Catalog_Product_View extends Mage_Catalog_Block_Product_View
{ 
    /**
     * Add meta information from product to head block
     *
     * @return Mage_Catalog_Block_Product_View
     */
     protected function _prepareLayout()
    { 
		parent::_prepareLayout();
		
		$simpleseo_enable = Mage::getStoreConfig('catalog/simpleseo/simpleseo_enable');
		
		if(!$simpleseo_enable) return; 
		
		$product_meta_title = Mage::getStoreConfig('catalog/simpleseo/product_meta_title');
		$product_meta_keyword = Mage::getStoreConfig('catalog/simpleseo/product_meta_keyword');
		$product_meta_description = Mage::getStoreConfig('catalog/simpleseo/product_meta_description');
		
		$string=$product_meta_title." ".$product_meta_keyword." ".$product_meta_description;
		
		preg_match_all("/\[(.*?)\]/",$string,$words);
		
		
		$product = $this->getProduct();
		 
		$word=array_unique($words[1]);
		  
		
		foreach($word as $w)
		{ 
			$p= strpos($w,'product_');
		
			if($p==0)
			{
				$attribute=substr($w,8);	
				$attributeModel = Mage::getModel('eav/entity_attribute')->loadByCode("catalog_product",$attribute);
				 
				$attrtype=$attributeModel->getFrontendInput();
				
				switch($attrtype)
				{
				case "text":
						$data=$product->getData($attribute); 
					break;
				case  "select":
						$data=$product->getAttributeText($attribute); 
					break;
				
				} 
				$product_meta_title=str_replace("[".$w."]",$data,$product_meta_title);  
				$product_meta_keyword=str_replace("[".$w."]",$data,$product_meta_keyword);  
				$product_meta_description=str_replace("[".$w."]",$data,$product_meta_description);  
			} 
		
		}   
			$headBlock = $this->getLayout()->getBlock('head');
			if ($headBlock) {
			
				$title = $product->getMetaTitle();
				
				if (!$title) {
					 $headBlock->setTitle($product_meta_title); 
				}
		
			}
		
		
			$keyword = $product->getMetaKeyword();
             
            if (!$keyword) {
                $headBlock->setKeywords($product_meta_keyword);
            }  
		
		 $description = $product->getMetaDescription();
            if (!$description) {
                $headBlock->setDescription($product_meta_description);
		}
			 
		
	}

    
    
  
}