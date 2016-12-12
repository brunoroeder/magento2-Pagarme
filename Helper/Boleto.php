<?php
/**
 * PagarMe payment method for Magento 2
 * Copyright (C) 2016 Bruno Roeder
 *
 * @author Bruno Roeder <contato@brunoroeder.com.br>
 *
 * This file included in Roeder/Pagarme is licensed under OSL 3.0
 *
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Roeder\Pagarme\Helper;

class Boleto extends \Roeder\Pagarme\Helper\Data
{
    protected $_transaction;

    /**
     * Constructor
     * @param \Magento\Framework\App\Helper\Context
     * @param \Magento\Customer\Model\Session
     * @param \Roeder\Pagarme\Model\Transaction
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Roeder\Pagarme\Model\Transaction $transaction
    ) {

        parent::__construct($context, $customerSession);
        $this->scopeConfig = $context->getScopeConfig();
        $this->_transaction = $transaction;
    }

    public function getBasicTransactionData()
    {
        $transaction = $this->_transaction;
        $transaction
            ->setApiKey($this->_getApiKey())
            ->setCustomer($this->getCustomerData())
            ->setPaymentMethod('boleto')
            ->setAsync(false)
            ->setPostbackurl($this->getPostBackUrl())
            ->setCapture(true);

        return $transaction;
    }
}
