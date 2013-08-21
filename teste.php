<?php
	$model	=	Mage::getModel('catalog/category');
	$rootCategoryId = Mage::app()->getStore()->getRootCategoryId();
	$category = $model->load($rootCategoryId);	
	$curId = $this->getRequest()->getParam('cat');
	
	$_helper    = $this->helper('catalog/output');
	$conf = Mage::helper('em0067settings');
	$cate	= $conf->getCategoriesCustom($category,$curId)->addAttributeToSelect('*');
	//echo $conf->get_featured_cate_width();	
	//$w 	= $conf->get_featured_cate_width();
	//$h 	= $conf->get_featured_cate_height();
	$w = 100;
	$h = 100;

$attribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', 'montadora');
if ($attribute->usesSource()) {
    $options = $attribute->getSource()->getAllOptions(true);
}
//print_r($options);

//echo "Teste";
?>


<div class="home_featured_category">
	<div class="featured_category_top">
		<h2><?php echo $this->__("Montadoras Populares") ?></h2>
	</div>
	<div class="featured_category_content">
		<div class="slideshow-box">
			<ul class="category-grid row" id="slideshow_featured_category">
				<?php foreach($options as $montadora): ?>
						<li class="item span3">
				<a href="http://netpartes.com.br/catalogsearch/result/?q=<?echo $montadora['label'] ?>" title="<?php echo $montadora['label'] ?>">
<img  src="/media/montadoras/<?php echo str_replace(' ','',strtolower($montadora['label'])) ?>.png" alt="<?php echo $montadora['label'] ?>" width="<?php echo $w ?>" height="<?php echo $h ?>" /></a>	
						
							<div class="cate_info">
<h2><a href="http://netpartes.com.br/catalogsearch/result/?q=<?echo $montadora['label'] ?>" title="<?php echo $montadora['label']?>"><?php echo $montadora['label'] ?></a></h2>							
							</div>
						</li>
					
				<?php endforeach ?>					
			</ul>			
		</div>
	</div>
</div>