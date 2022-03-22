<?php
namespace Cetechz\AddressFields\Plugin\Checkout\Block\Checkout\AttributeMerger;

class Plugin
{
  public function afterMerge(\Magento\Checkout\Block\Checkout\AttributeMerger $subject, $result)
  {
    if (array_key_exists('street', $result)) {
      $result['street']['children'][0]['placeholder'] = __('Flat No/House No/Building No');
      $result['street']['children'][1]['placeholder'] = __('Street Address');
      $result['street']['children'][2]['placeholder'] = __('Landmark');
    }
    
    return $result;
  }
}