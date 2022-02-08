<?php
/**
 * Copyright © Cetechz, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cetechz\LimitShippaddr\Plugin;
use Magento\Directory\Helper\Data as DirectoryHelper;

class LayoutProcessor {
    protected $countryoptions;
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollection,
        DirectoryHelper $directoryHelper){
        $this->_storeManager = $storeManager;
        $this->countryCollectionFactory = $countryCollection;
        $this->directoryHelper = $directoryHelper;
    }

    public function afterProcess(\Magento\Checkout\Block\Checkout\LayoutProcessor $subject , $result){
        $result['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['country_id']=[
            'component' => 'Magento_Ui/js/form/element/select',
            'config' => [
                'customScope' => 'shippingAddress',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/select',
                'id' => 'drop-down'
            ],
            'dataScope' => 'shipping/Address.country_id',
            'label' => 'Country',
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => [],
            'sortOrder' => 70,
            'id'=> 'drop-down',
            'options' => $this->getCountryOptions()
        ];
        return $result;
    }

    public function getCountryOptions(){
        $countryIds = ['US'];
        $countrySelection = $this->countryCollectionFactory->create()->loadByStore($this->_storeManager->getStore()->getId());
        $countrySelection->addFieldToFilter('country_id',['in'=>$countryIds]);
        $countrySelection = $countrySelection->toOptionArray();
        $countryarray = $this->orderCountryOptions($countrySelection);
        return $countryarray;
    }

    public function orderCountryOptions(array $countryOptions){
        $topCountryCodes = $this->directoryHelper->getTopCountryCodes();
        if (empty($topCountryCodes)){
            return $countryOptions;
        }

        $headOptions = [];
        $tailOptions = [[
            'value' => 'delimiter',
            'label' => '_________',
            'disabled' => true 
        ]];

        foreach ($countryOptions as $countryOption) {
            if (empty($countryOption['value']) || in_array($countryOption['value'], $topCountryCodes)){
                $headOptions = $countryOption;
            } else{
                $tailOptions = $countryOption;
            }
        }
        return array_merge($headOptions,$tailOptions);

    }


}