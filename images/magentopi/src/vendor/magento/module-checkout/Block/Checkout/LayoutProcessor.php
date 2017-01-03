<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Checkout\Block\Checkout;

use Magento\Framework\App\ObjectManager;

class LayoutProcessor implements \Magento\Checkout\Block\Checkout\LayoutProcessorInterface
{
    /**
     * @var \Magento\Customer\Model\AttributeMetadataDataProvider
     */
    private $attributeMetadataDataProvider;

    /**
     * @var \Magento\Ui\Component\Form\AttributeMapper
     */
    protected $attributeMapper;

    /**
     * @var AttributeMerger
     */
    protected $merger;

    /**
     * @var \Magento\Customer\Model\Options
     */
    private $options;

    /**
     * @param \Magento\Customer\Model\AttributeMetadataDataProvider $attributeMetadataDataProvider
     * @param \Magento\Ui\Component\Form\AttributeMapper $attributeMapper
     * @param AttributeMerger $merger
     */
    public function __construct(
        \Magento\Customer\Model\AttributeMetadataDataProvider $attributeMetadataDataProvider,
        \Magento\Ui\Component\Form\AttributeMapper $attributeMapper,
        AttributeMerger $merger
    ) {
        $this->attributeMetadataDataProvider = $attributeMetadataDataProvider;
        $this->attributeMapper = $attributeMapper;
        $this->merger = $merger;
    }

    /**
     * @deprecated
     * @return \Magento\Customer\Model\Options
     */
    private function getOptions()
    {
        if (!is_object($this->options)) {
            $this->options = ObjectManager::getInstance()->get(\Magento\Customer\Model\Options::class);
        }
        return $this->options;
    }

    /**
     * @return array
     */
    private function getAddressAttributes()
    {
        /** @var \Magento\Eav\Api\Data\AttributeInterface[] $attributes */
        $attributes = $this->attributeMetadataDataProvider->loadAttributesCollection(
            'customer_address',
            'customer_register_address'
        );

        $elements = [];
        foreach ($attributes as $attribute) {
            $code = $attribute->getAttributeCode();
            if ($attribute->getIsUserDefined()) {
                continue;
            }
            $elements[$code] = $this->attributeMapper->map($attribute);
            if (isset($elements[$code]['label'])) {
                $label = $elements[$code]['label'];
                $elements[$code]['label'] = __($label);
            }
        }
        return $elements;
    }

    /**
     * Convert elements(like prefix and suffix) from inputs to selects when necessary
     *
     * @param array $elements address attributes
     * @param array $attributesToConvert fields and their callbacks
     * @return array
     */
    private function convertElementsToSelect($elements, $attributesToConvert)
    {
        $codes = array_keys($attributesToConvert);
        foreach (array_keys($elements) as $code) {
            if (!in_array($code, $codes)) {
                continue;
            }
            $options = call_user_func($attributesToConvert[$code]);
            if (!is_array($options)) {
                continue;
            }
            $elements[$code]['dataType'] = 'select';
            $elements[$code]['formElement'] = 'select';

            foreach ($options as $key => $value) {
                $elements[$code]['options'][] = [
                    'value' => $key,
                    'label' => $value,
                ];
            }
        }

        return $elements;
    }

    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     * @return array
     */
    public function process($jsLayout)
    {
        $attributesToConvert = [
            'prefix' => [$this->getOptions(), 'getNamePrefixOptions'],
            'suffix' => [$this->getOptions(), 'getNameSuffixOptions'],
        ];

        $elements = $this->getAddressAttributes();
        $elements = $this->convertElementsToSelect($elements, $attributesToConvert);
        // The following code is a workaround for custom address attributes
        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
            ['payment']['children']
        )) {
            if (!isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                ['payment']['children']['payments-list']['children'])) {
                $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                ['payment']['children']['payments-list']['children'] = [];
            }

            $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
            ['payment']['children']['payments-list']['children'] =
                array_merge_recursive(
                    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                    ['payment']['children']['payments-list']['children'],
                    $this->processPaymentConfiguration(
                        $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                        ['payment']['children']['renders']['children'],
                        $elements
                    )
                );
        }

        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
            ['children']['shippingAddress']['children']['shipping-address-fieldset']['children']
        )) {
            $fields = $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
            ['children']['shippingAddress']['children']['shipping-address-fieldset']['children'];
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
            ['children']['shippingAddress']['children']['shipping-address-fieldset']['children'] = $this->merger->merge(
                $elements,
                'checkoutProvider',
                'shippingAddress',
                $fields
            );
        }
        return $jsLayout;
    }

    /**
     * Inject billing address component into every payment component
     *
     * @param array $configuration list of payment components
     * @param array $elements attributes that must be displayed in address form
     * @return array
     */
    private function processPaymentConfiguration(array &$configuration, array $elements)
    {
        $output = [];
        foreach ($configuration as $paymentGroup => $groupConfig) {
            foreach ($groupConfig['methods'] as $paymentCode => $paymentComponent) {
                if (empty($paymentComponent['isBillingAddressRequired'])) {
                    continue;
                }
                $output[$paymentCode . '-form'] = [
                    'component' => 'Magento_Checkout/js/view/billing-address',
                    'displayArea' => 'billing-address-form-' . $paymentCode,
                    'provider' => 'checkoutProvider',
                    'deps' => 'checkoutProvider',
                    'dataScopePrefix' => 'billingAddress' . $paymentCode,
                    'sortOrder' => 1,
                    'children' => [
                        'form-fields' => [
                            'component' => 'uiComponent',
                            'displayArea' => 'additional-fieldsets',
                            'children' => $this->merger->merge(
                                $elements,
                                'checkoutProvider',
                                'billingAddress' . $paymentCode,
                                [
                                    'country_id' => [
                                        'sortOrder' => 115,
                                    ],
                                    'region' => [
                                        'visible' => false,
                                    ],
                                    'region_id' => [
                                        'component' => 'Magento_Ui/js/form/element/region',
                                        'config' => [
                                            'template' => 'ui/form/field',
                                            'elementTmpl' => 'ui/form/element/select',
                                            'customEntry' => 'billingAddress' . $paymentCode . '.region',
                                        ],
                                        'validation' => [
                                            'required-entry' => true,
                                        ],
                                        'filterBy' => [
                                            'target' => '${ $.provider }:${ $.parentScope }.country_id',
                                            'field' => 'country_id',
                                        ],
                                    ],
                                    'postcode' => [
                                        'component' => 'Magento_Ui/js/form/element/post-code',
                                        'validation' => [
                                            'required-entry' => true,
                                        ],
                                    ],
                                    'company' => [
                                        'validation' => [
                                            'min_text_length' => 0,
                                        ],
                                    ],
                                    'fax' => [
                                        'validation' => [
                                            'min_text_length' => 0,
                                        ],
                                    ],
                                    'telephone' => [
                                        'config' => [
                                            'tooltip' => [
                                                'description' => __('For delivery questions.'),
                                            ],
                                        ],
                                    ],
                                ]
                            ),
                        ],
                    ],
                ];
            }
            unset($configuration[$paymentGroup]['methods']);
        }

        return $output;
    }
}
