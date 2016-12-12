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

namespace Roeder\Pagarme\Model\Postback;

abstract class AbstractPostback extends \Magento\Framework\Model\AbstractModel
{
    protected $_paymentObject;
    protected $_orderObject;

    /**
     * Constructor
     * @param \Magento\Sales\Model\Order\Payment
     * @param \Magento\Sales\Model\Order
     */
    public function __construct(
        \Magento\Sales\Model\Order\Payment $paymentObject,
        \Magento\Sales\Model\Order $orderObject
    ) {
        $this->_paymentObject = $paymentObject;
        $this->_orderObject = $orderObject;

    }

    protected function getOrder(int $pagarmeTransactionId)
    {
        $payment = $this->getPayment($pagarmeTransactionId);
        return $this->_orderObject->load($payment->getParentId());
    }

    protected function getPayment(int $pagarmeTransactionId)
    {
        return $this->_paymentObject->load($pagarmeTransactionId, 'pagarme_transaction_id');
    }
}
