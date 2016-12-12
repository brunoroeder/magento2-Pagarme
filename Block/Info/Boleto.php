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

namespace Roeder\Pagarme\Block\Info;

use Magento\Framework\View\Element\Template;

class Boleto extends \Magento\Payment\Block\Info
{
    /**
     * @var string
     */
    protected $_template = 'Roeder_Pagarme::info/boleto.phtml';

    protected $orderIncrementId;
    protected $scopeConfig;
    protected $helperData;

    /**
     * Constructor
     *
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Roeder\Pagarme\Helper\Data $helperData,
        Template\Context $context,
        array $data = []
    ) {

        parent::__construct($context, $data);
        $this->scopeConfig = $context->getScopeConfig();
        $this->helperData = $helperData;
    }

    public function getBoletoPrintUrl()
    {
        $order = $this->getInfo()->getOrder();
        return $order->getPayment()->getAdditionalInformation('boleto_url');
    }

    public function getPagarmeTransactionId()
    {
        $order = $this->getInfo()->getOrder();
        return $order->getPayment()->getAdditionalInformation('pagarme_transaction_id');
    }

    public function getBoletoBarCode()
    {
        $order = $this->getInfo()->getOrder();
        return $order->getPayment()->getAdditionalInformation('boleto_barcode');
    }

    public function getBoletoExpirationDate()
    {
        $order = $this->getInfo()->getOrder();
        $expiration = $order->getPayment()->getAdditionalInformation('boleto_expiration_date');
        $date = new \DateTime($expiration);
        return $date->format('d/m/Y');
    }
}
