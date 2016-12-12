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

class Paid extends AbstractPostback implements PostbackInterface
{
    protected $_invoiceService;
    protected $_transaction;

    /**
     * Constructor
     * @param \Roeder\Pagarme\Helper\Data
     * @param \Magento\Sales\Model\Service\InvoiceService
     * @param \Magento\Framework\DB\Transaction
     * @param \Magento\Sales\Model\Order\Payment
     * @param \Magento\Sales\Model\Order
     */
    public function __construct(
        \Roeder\Pagarme\Helper\Data $dataHelper,
        \Magento\Sales\Model\Service\InvoiceService $invoiceService,
        \Magento\Framework\DB\Transaction $transaction,
        \Magento\Sales\Model\Order\Payment $paymentObject,
        \Magento\Sales\Model\Order $orderObject
    ) {

        parent::__construct($paymentObject, $orderObject);

        $this->_dataHelper = $dataHelper;
        $this->_invoiceService = $invoiceService;
        $this->_transaction = $transaction;

    }

    public function process($data)
    {
        $order = $this->getOrder($data->id);
        $order->setState(\Magento\Sales\Model\Order::STATE_PROCESSING);

        if ($order->canInvoice()) {
            $invoice = $this->_invoiceService->prepareInvoice($order);
            $invoice->register();
            $invoice->save();
            $transactionSave = $this->_transaction->addObject($invoice)->addObject($invoice->getOrder());
            $transactionSave->save();
            $order->addStatusHistoryComment(__('Boleto receive paid status from PagarMe.'));
        }

        $order->save();
    }
}
