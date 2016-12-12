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

namespace Roeder\Pagarme\Block\Onepage;

class Success extends \Magento\Framework\View\Element\Template
{
    protected $_checkoutSession;
    protected $_salesOrderFactory;

    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Model\Order $salesOrderFactory,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_checkoutSession = $checkoutSession;
        $this->_salesOrderFactory = $salesOrderFactory;
    }

    public function getBoletoPrintUrl()
    {
        $orderId = $this->_checkoutSession->getLastOrderId();
        $order = $this->_salesOrderFactory->load($orderId);
        $boletoPrintUrl = $order->getPayment()->getAdditionalInformation('boleto_url');

        return $boletoPrintUrl;
    }
}
