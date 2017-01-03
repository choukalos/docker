<?php
namespace Magento\Wishlist\Pricing\Render\ConfiguredPriceBox;

/**
 * Interceptor class for @see \Magento\Wishlist\Pricing\Render\ConfiguredPriceBox
 */
class Interceptor extends \Magento\Wishlist\Pricing\Render\ConfiguredPriceBox implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \Magento\Framework\Pricing\SaleableInterface $saleableItem, \Magento\Framework\Pricing\Price\PriceInterface $price, \Magento\Framework\Pricing\Render\RendererPool $rendererPool, array $data = array(), \Magento\Catalog\Model\Product\Pricing\Renderer\SalableResolverInterface $salableResolver = null)
    {
        $this->___init();
        parent::__construct($context, $saleableItem, $price, $rendererPool, $data, $salableResolver);
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheKey()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCacheKey');
        if (!$pluginInfo) {
            return parent::getCacheKey();
        } else {
            return $this->___callPlugins('getCacheKey', func_get_args(), $pluginInfo);
        }
    }
}
