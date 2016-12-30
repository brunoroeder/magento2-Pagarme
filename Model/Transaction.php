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

namespace Roeder\Pagarme\Model;

class Transaction extends \Magento\Framework\Model\AbstractModel
{
    private $_apiKey;
    private $_amount;
    private $_cardHash;
    private $_cardId;
    private $_paymentMethod;
    private $_postbackUrl;
    private $_async;
    private $_installments;
    private $_softDescriptor;
    private $_capture = false;
    private $_metadata = [];
    private $_boletoExpirationDate;
    private $_customer;

    /**
     * Constructor
     * @param \Roeder\Pagarme\Helper\Data
     */
    public function __construct(
        \Roeder\Pagarme\Helper\Data $dataHelper
    ) {
        $this->_dataHelper = $dataHelper;
        // $this->_init('Roeder\Pagarme\Model\Transactions');

    }

    public function authorize()
    {
        /** @var PagarMe_Request $request */
        $request = $this->_dataHelper->getPagarmeRequest('/transactions', 'POST');
        // $request->setParameters($this->toArrayTransform());
        $request->setParameters(['api_key' => $this->getApiKey(),
                                 'amount' => $this->getAmount(),
                                 'payment_method' => $this->getPaymentMethod(),
                                 'postback_url' => $this->getPostbackurl(),
                                 'capture' => $this->isCapture(),
                                 'async' => $this->isAsync(),
                                 'boleto_expiration_date' => $this->getBoletoExpirationDate(),
                                 'metadata' => $this->getMetadata()
                                 ]);

        return $request->run();
    }

    public function capture($transactionId)
    {
        //@TODO not iplemented yet
        /** @var PagarMe_Request $request */
        $request = $this->_dataHelper
            ->getPagarmeRequest('/transactions/'.$transactionId.'/capture', 'POST');
        $request->setParameters(['api_key' => $this->getApiKey(),
                                 'amount' => $this->getAmount(),
                                 'payment_method' => $this->getPaymentMethod(),
                                 'postback_url' => $this->getPostbackurl(),
                                 'capture' => $this->isCapture(),
                                 'async' => $this->isAsync(),
                                 'boleto_expiration_date' => $this->getBoletoExpirationDate(),
                                 'metadata' => $this->getMetadata()
                                 ]);

        return $request->run();
    }

    public function getApiKey()
    {
        return $this->_apiKey;
    }

    public function setApiKey($apiKey)
    {
        $this->_apiKey = $apiKey;
        return $this;
    }

    public function getBoletoExpirationDate()
    {
        return $this->_boletoExpirationDate;
    }

    public function setBoletoExpirationDate($boletoExpirationDate)
    {
        $this->_boletoExpirationDate = $boletoExpirationDate;
        return $this;
    }

    public function getAmount()
    {
        return $this->_amount;
    }

    public function setAmount($amount)
    {
        $this->_amount = $amount;
        return $this;
    }

    public function getCardHash()
    {
        return $this->_cardHash;
    }

    public function setCardHash($cardHash)
    {
        $this->_cardHash = $cardHash;
        return $this;
    }

    public function getCardId()
    {
        return $this->_cardId;
    }

    public function setCardId($cardId)
    {
        $this->_cardId = $cardId;
        return $this;
    }

    public function getPaymentMethod()
    {
        return $this->_paymentMethod;
    }

    public function setPaymentMethod($paymentMethod)
    {
        $this->_paymentMethod = $paymentMethod;
        return $this;
    }

    public function getPostbackurl()
    {
        return $this->_postbackUrl;
    }

    public function setPostbackurl($postBackUrl)
    {
        $this->_postbackUrl = $postBackUrl;
        return $this;
    }

    public function isAsync()
    {
        return $this->_async;
    }

    public function setAsync($async)
    {
        $this->_async = $async;
        return $this;
    }

    public function getInstallments()
    {
        return $this->_installments;
    }

    public function setInstallments($installments)
    {
        $this->_installments = $installments;
        return $this;
    }

    public function getSoftDescriptor()
    {
        return $this->_softDescriptor;
    }

    public function setSoftDescriptor($softDescriptor)
    {
        $this->_softDescriptor = $softDescriptor;
        return $this;
    }

    public function isCapture()
    {
        return $this->_capture;
    }

    public function setCapture($capture)
    {
        $this->_capture = $capture;
        return $this;
    }

    public function getMetadata()
    {
        return $this->_metadata;
    }

    public function setMetadata($metadata)
    {
        $this->_metadata = $metadata;
        return $this;
    }

    public function getCustomer()
    {
        return $this->_customer;
    }

    public function setCustomer($customer)
    {
        $this->_customer = $customer;
        return $this;
    }

    public function getSplitRules()
    {
        return $this->_splitRules;
    }

    public function setSplitRules($splitRules)
    {
        $this->_splitRules = $splitRules;
        return $this;
    }
}
