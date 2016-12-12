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

namespace Roeder\Pagarme\Model\Payment;

use Magento\Payment\Model\Method\AbstractMethod;

class Boleto extends AbstractMethod
{
    const CODE                       = 'pagarme_boleto';

    protected $_infoBlockType = 'Roeder\Pagarme\Block\Info\Boleto';
    protected $_code = self::CODE;
    protected $_canAuthorize        = true;
    protected $_isOffline           = false;
    protected $_isGateway           = true;

    protected $_logger              = null;
    protected $_transaction         = null;
    protected $_boletoHelper        = null;
    protected $_dataHelper          = null;

    /**
     * Constructor
     * @param \Magento\Framework\Model\Context
     * @param \Roeder\Pagarme\Helper\Boleto
     * @param \Roeder\Pagarme\Helper\Data
     * @param \Magento\Framework\Registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory
     * @param \Magento\Framework\Api\AttributeValueFactory
     * @param \Magento\Payment\Helper\Data
     * @param \Magento\Framework\App\Config\ScopeConfigInterface
     * @param \Magento\Payment\Model\Method\Logger
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null
     * @param \Magento\Framework\Data\Collection\AbstractDb|null
     * @param array
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Roeder\Pagarme\Helper\Boleto $boletoHelper,
        \Roeder\Pagarme\Helper\Data $dataHelper,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_logger = $logger;
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $paymentData,
            $scopeConfig,
            $logger,
            $resource,
            $resourceCollection,
            $data
        );

        $this->_logger = $logger;
        $this->_boletoHelper = $boletoHelper;
        $this->_dataHelper = $dataHelper;
    }

    public function authorize(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        parent::authorize($payment, $amount);
        $date = new \DateTime();
        $date->add(new \DateInterval('P' . $this->getConfigData('boleto_expiration_date') . 'D'));
        $this->_transaction = $this->_boletoHelper->getBasicTransactionData();
        $this->_transaction
            ->setPaymentMethod('boleto')
            ->setBoletoExpirationDate($date->format('Y-m-d\TH:i:m'))
            ->setAmount($this->_dataHelper->treatAmount($amount))
            ->setMetadata([
                'Pedido' => $payment->getOrder()->getIncrementId()
            ])
            ->setInstallments(1);

        $this->_response = $this->getAuthorizeResponse();
        $this->_validateResponse();

        // $payment->setPagarmeTransactionId($this->_response['id']);

        if (isset($this->_response['boleto_url'])) {
            $payment->setAdditionalInformation([
                                                'pagarme_transaction_id' => $this->_response['id'],
                                                'boleto_url' => $this->_response['boleto_url'],
                                                'boleto_barcode' => $this->_response['boleto_barcode'],
                                                'boleto_expiration_date' => $this->_response['boleto_expiration_date'],
                                                ]);
        }

        $payment->setPagarmeTransactionId($this->_response['id']);

        $state = \Magento\Sales\Model\Order::STATE_NEW;
        $payment->setState($state);
        $payment->setStatus($this->getConfigData('order_status'));
        $payment->setIsNotified(false);

        // $payment-    >save();

        return $this;
    }

    public function getAuthorizeResponse()
    {
        return $this->_transaction->authorize();
    }

    private function _validateResponse()
    {
        $status = $this->_response['status'];

        if ($status == 'refused') {
            throw new \Exception(
                __('The transaction was refused. Reason code: %s', $this->_response['status_reason'])
            );

            $this->_logger->debug('refused');
        }

        if (!isset($this->_response['id'])) {
            throw new \Exception(
                __('The transaction ID is missing')
            );
        }
    }
}
