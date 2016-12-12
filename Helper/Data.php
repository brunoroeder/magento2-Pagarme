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

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_scopeConfig;
    protected $_customerSession;
    protected $_logger;
    protected $_urlBuilder;

    /**
     * Constructor
     * @param \Magento\Framework\App\Helper\Context
     * @param \Magento\Customer\Model\Session
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Customer\Model\Session $customerSession
    ) {

        $this->_scopeConfig = $context->getScopeConfig();
        $this->_logger = $context->getLogger();
        $this->_customerSession = $customerSession;
        $this->_urlBuilder = $context->getUrlBuilder();
        parent::__construct($context);
    }

    public function getPagarmeRequest(string $url, $requestType = 'GET')
    {

        $request = new \PagarMe_Request($url, $requestType);

        $request->setApiKey($this->_getApiKey());

        return $request;
    }

    protected function _getApiKey()
    {
        return $this->_getKey();
    }

    protected function _getKey()
    {
        if ($this->_scopeConfig->getValue('payment/pagarme_boleto/is_production', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)) {
            return $this->_scopeConfig->getValue('payment/pagarme_boleto/production_api_key', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        }

        return $this->_scopeConfig->getValue('payment/pagarme_boleto/staging_api_key', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function isValidRequestSignature($postBackBody, $signature)
    {
        \PagarMe::setApiKey($this->_getApiKey());

        return \PagarMe::validateRequestSignature($postBackBody, $signature);
    }

    public function treatAmount(float $amount)
    {
        return number_format($amount, 2, '', '');
    }

    public function getCustomerData()
    {
        $loggedCustomer = $this->_customerSession->getCustomer();
        return new \Roeder\Pagarme\Model\Transaction\Customer($loggedCustomer);
    }

    public function phoneToArray(string $phoneString)
    {
        $phoneString = str_replace(
            ['-', ' ', '(', ')'],
            '',
            $phoneString
        );

        if (strlen($phoneString) == 0) {
            return null;
        }

        if (strlen($phoneString) < 10) {
            return null;
        }

        foreach (str_split($phoneString) as $number) {
            if (!is_numeric($number)) {
                return null;
            }
        }

        if (substr($phoneString, 0, 4) == '0800') {
            return ['number' => $phoneString];
        }

        return [
            'areaCode' => substr($phoneString, 0, 2),
            'number' => substr($phoneString, 2)
        ];
    }

    public function getPostBackUrl()
    {
        return $this->_urlBuilder->getUrl('pagarme/postback/transaction');
    }
}
