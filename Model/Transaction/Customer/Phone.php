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

namespace Roeder\Pagarme\Model\Transaction\Customer;

class Phone extends \Magento\Framework\Model\AbstractModel
{
    private $_ddd;
    private $_number;

    /**
     * Constructor
     * @param \Magento\Customer\Model\Address
     */
    public function __construct(
        \Magento\Customer\Model\Address $address
    ) {
        if (!$address->getTelephone()) {
            return;
        }

        $filter = $this->phoneToArray($address->getTelephone());
        $this
            ->setDdd($filter['areaCode'])
            ->setNumber($filter['number']);

    }

    public function getDdd()
    {
        return $this->_ddd;
    }

    public function setDdd($ddd)
    {
        $this->_ddd = $ddd;
        return $this;
    }

    public function getNumber()
    {
        return $this->_number;
    }

    public function setNumber($number)
    {
        $this->_number = $number;
        return $this;
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
}
