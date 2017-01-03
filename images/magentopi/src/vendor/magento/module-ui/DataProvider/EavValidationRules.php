<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Ui\DataProvider;

use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;

/**
 * Class EavValidationRules
 */
class EavValidationRules
{
    /**
     * @var array
     */
    protected $validationRules = [
        'email' => ['validate-email' => true],
        'date' => ['validate-date' => true],
    ];

    /**
     * Build validation rules
     *
     * @param AbstractAttribute $attribute
     * @param array $data
     * @return array
     */
    public function build(AbstractAttribute $attribute, array $data)
    {
        $validation = [];
        if (isset($data['required']) && $data['required'] == 1) {
            $validation = array_merge($validation, ['required-entry' => true]);
        }
        if ($attribute->getFrontendInput() === 'price') {
            $validation = array_merge($validation, ['validate-zero-or-greater' => true]);
        }
        if ($attribute->getValidateRules()) {
            $validation = array_merge($validation, $attribute->getValidateRules());
        }
        $rules = [];
        foreach ($validation as $type => $ruleName) {
            $rule = [$type => $ruleName];
            if ($type === 'input_validation') {
                $rule = isset($this->validationRules[$ruleName]) ? $this->validationRules[$ruleName] : [];
            }
            $rules = array_merge($rules, $rule);
        }

        return $rules;
    }
}
