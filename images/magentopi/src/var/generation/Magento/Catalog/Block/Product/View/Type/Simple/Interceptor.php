<?php
namespace Magento\Catalog\Block\Product\View\Type\Simple;

/**
 * Interceptor class for @see \Magento\Catalog\Block\Product\View\Type\Simple
 */
class Interceptor extends \Magento\Catalog\Block\Product\View\Type\Simple implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Block\Product\Context $context, \Magento\Framework\Stdlib\ArrayUtils $arrayUtils, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $arrayUtils, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getImage($product, $imageId, $attributes = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getImage');
        if (!$pluginInfo) {
            return parent::getImage($product, $imageId, $attributes);
        } else {
            return $this->___callPlugins('getImage', func_get_args(), $pluginInfo);
        }
    }
}
