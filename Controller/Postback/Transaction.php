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

namespace Roeder\Pagarme\Controller\Postback;

class Transaction extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;
    protected $context;
    protected $_scopeConfig;
    protected $_dataHelper;
    protected $_requestContext;
    protected $_paidObject;

    /**
     * Constructor
     * @param \Magento\Framework\App\Action\Context
     * @param \Magento\Framework\View\Result\PageFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface
     * @param \Roeder\Pagarme\Helper\Data
     * @param \Magento\Framework\View\Context
     * @param \Roeder\Pagarme\Model\Postback\Paid
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Roeder\Pagarme\Helper\Data $dataHelper,
        \Magento\Framework\View\Context $requestContext,
        \Roeder\Pagarme\Model\Postback\Paid $paidObject
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);

        $this->context = $context;
        $this->_scopeConfig = $scopeConfig;
        $this->_dataHelper = $dataHelper;
        $this->_requestContext = $requestContext;
        $this->_paidObject = $paidObject;
        //validade if request is from pagarme
        $this->_validateRequest();
    }

    private function _validateRequest()
    {
        $request = $this->getRequest();
        if (!$this->_dataHelper->isValidRequestSignature($this->_requestContext->getContent(), $request->getHeader('X-Hub-Signature'))) {
            throw new \Exception('Invalid signature');
        }
    }

    public function execute()
    {
        $body = json_decode($this->_requestContext->getContent());

        switch ($body->current_status) {
            case 'paid':
                return $this->_paidObject->process($body);
                break;
        }
        return false;
    }
}
