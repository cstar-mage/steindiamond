<?php

/**
 *
 *
 * @category  ProxiBlue
 * @package   DynCatProd
 * @author    Lucas van Staden <sales@proxiblue.com.au>
 * @copyright 2016 Lucas van Staden (ProxiBlue)
 * @license   http://www.proxiblue.com.au/eula EULA
 * @link      http://www.proxiblue.com.au
 */
class ProxiBlue_DynCatProd_Model_Rule_Condition_Additional_Conditions_Salesreport
    extends
    ProxiBlue_DynCatProd_Model_Rule_Condition_Additional_Conditions_Combine
{

    public function __construct($args)
    {
        parent::__construct($args);
        $this->setType(
            'dyncatprod/rule_condition_additional_conditions_salesreport'
        )
            ->setProcessingOrder(80)
            ->setAggregator('all');
    }

    /**
     * All salesrules must validate for results
     *
     * @return string
     */
    public function getAggregator()
    {
        return 'all';
    }

    public function asHtml()
    {
        $html
            =
            $this->getTypeElement()->getHtml() . Mage::helper('dyncatprod')->__(
                "Any product that will be generated by these Sales Reports:",
                $this->getAggregatorElement()->getHtml()
            );
        if ($this->getId() != '1') {
            $html .= $this->getRemoveLinkHtml();
        }

        return $html;
    }

    public function asString($format = '')
    {
        $str = Mage::helper('dyncatprod')->__(
            "Any product that will be generated by these Sales Reports:",
            $this->getAggregatorName()
        );

        return $str;
    }

    /**
     * Conditions child rules
     * Current supported:
     * Cart Subtotal
     *
     * @return array
     */
    public function getNewChildSelectOptions()
    {
        $conditions = parent::getNewChildSelectOptions();
        $conditions = array_merge_recursive(
            $conditions, array(
                array('value' => 'dyncatprod/rule_condition_additional_conditions_salesreport_bestsellers',
                      'label' => Mage::helper('dyncatprod')->__(
                          'Best Sellers'
                      )),
                array('value' => 'dyncatprod/rule_condition_additional_conditions_salesreport_bestsellersbyrevenue',
                      'label' => Mage::helper('dyncatprod')->__(
                          'Best Sellers by Revenue'
                      )),
                array('value' => 'dyncatprod/rule_condition_additional_conditions_salesreport_bestsellersbyprofit',
                      'label' => Mage::helper('dyncatprod')->__(
                          'Best Sellers by Profit'
                      )),
                array('value' => 'dyncatprod/rule_condition_additional_conditions_salesreport_leastsellers',
                      'label' => Mage::helper('dyncatprod')->__(
                          'Least Sellers'
                      )),
                array('value' => 'dyncatprod/rule_condition_additional_conditions_salesreport_leastsellersbyrevenue',
                      'label' => Mage::helper('dyncatprod')->__(
                          'Least Sellers by Revenue'
                      )),
                array('value' => 'dyncatprod/rule_condition_additional_conditions_salesreport_leastsellersbyprofit',
                      'label' => Mage::helper('dyncatprod')->__(
                          'Least Sellers by Profit'
                      )),
                array('value' => 'dyncatprod/rule_condition_additional_conditions_salesreport_mostviewed',
                      'label' => Mage::helper('dyncatprod')->__('Most Viewed')),
                array('value' => 'dyncatprod/rule_condition_additional_conditions_salesreport_leastviewed',
                      'label' => Mage::helper('dyncatprod')->__(
                          'Least Viewed'
                      )),
                array('value' => 'dyncatprod/rule_condition_additional_conditions_salesreport_lowstock',
                      'label' => Mage::helper('dyncatprod')->__('Low Stock')),
            )
        );

        return $conditions;
    }

}
