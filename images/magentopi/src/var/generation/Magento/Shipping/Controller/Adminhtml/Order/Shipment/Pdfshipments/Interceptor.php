<?php
namespace Magento\Shipping\Controller\Adminhtml\Order\Shipment\Pdfshipments;

/**
 * Interceptor class for @see \Magento\Shipping\Controller\Adminhtml\Order\Shipment\Pdfshipments
 */
class Interceptor extends \Magento\Shipping\Controller\Adminhtml\Order\Shipment\Pdfshipments implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Ui\Component\MassAction\Filter $filter, \Magento\Framework\Stdlib\DateTime\DateTime $dateTime, \Magento\Framework\App\Response\Http\FileFactory $fileFactory, \Magento\Sales\Model\Order\Pdf\Shipment $shipment, \Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory $collectionFactory)
    {
        $this->___init();
        parent::__construct($context, $filter, $dateTime, $fileFactory, $shipment, $collectionFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        if (!$pluginInfo) {
            return parent::dispatch($request);
        } else {
            return $this->___callPlugins('dispatch', func_get_args(), $pluginInfo);
        }
    }
}
