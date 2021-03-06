<?php
use Mouf\Html\Utils\WebLibraryManager\WebLibrary;
use Mouf\Html\HtmlElement\HtmlString;
use Mouf\Integration\Magento\MagentoHtmlElementBlock;
/* @var $object WebLibrary  */

$headBlock = Mage::app()->getLayout()->getBlock('head');
/* @var $headBlock Mage_Page_Block_Html_Head */
$shouldMergeJs = Mage::getStoreConfigFlag('dev/js/merge_files');


$moufBlock = null;
/**
 * TODO Add the block from xml, to add it just after the displaying of js files. ( and to prevent copy/paste)
 */
$magentoBlock = $headBlock->getChild("moufjsblock");
if ($magentoBlock === false) {
	$moufBlock = new HtmlString("");
	$magentoBlock = new MagentoHtmlElementBlock($moufBlock);
	$headBlock->append($magentoBlock, "moufjsblock");
}
else {
	$moufBlock = $magentoBlock->getMoufBlock();
	if (!($moufBlock instanceof HtmlString)) {
		throw new MoufgentoException("The Mouf block of the head-blocks child named moufjsblock is not a HtmlString");
	}
}

foreach ($object->getJsFiles() as $file) {
	if(strpos($file, 'http://') === false && strpos($file, 'https://') === false && strpos($file, '/') !== 0) {
		$url = ROOT_URL.$file;
		if (!$shouldMergeJs) {
			$headBlock->addJs('../'.$file);
		}
		else {
			$moufBlock->htmlString .= '<script type="text/javascript" src="'.htmlspecialchars($url, ENT_QUOTES).'"></script>'."\n";
		}
	} else {
		
		$moufBlock->htmlString .= '<script type="text/javascript" src="'.htmlspecialchars($file, ENT_QUOTES).'"></script>'."\n";
	}
}