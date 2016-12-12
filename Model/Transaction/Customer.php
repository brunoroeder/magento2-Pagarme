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

namespace Roeder\Pagarme\Model\Transaction;

use \Roeder\Pagarme\Model\Transaction\Customer\Address as CustomerAddress;
use \Roeder\Pagarme\Model\Transaction\Customer\Phone as CustomerPhone;

class Customer extends \Magento\Framework\Model\AbstractModel
{
    private $_name;
    private $_documentNumber;
    private $_email;
    /** @var  CustomerAddress */
    private $_address;
    /** @var  CustomerPhone */
    private $_phone;
    private $_sex;
    private $_bornAt;

    /**
     * Constructor
     * @param \Magento\Customer\Model\Customer
     */
    public function __construct(
        \Magento\Customer\Model\Customer $customer
    ) {
        $gender = $customer->getGender();

        $this
            ->setBornAt($customer->getDob())
            ->setDocumentNumber($customer->getTaxvat())
            ->setEmail($customer->getEmail())
            ->setSex($gender == 1 ? 'M' : 'F')
            ->setName($customer->getFirstname() . ' ' . $customer->getLastname());

        if ($customer->getDefaultBillingAddress()) {
            $this->setAddress(new CustomerAddress($customer->getDefaultBillingAddress()));
            $this->setPhone(new CustomerPhone($customer->getDefaultBillingAddress()));
        }
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    public function getDocumentNumber()
    {
        return $this->_documentNumber;
    }

    public function setDocumentNumber($documentNumber)
    {
        $this->_documentNumber = $documentNumber;
        return $this;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function setEmail($email)
    {
        $this->_email = $email;
        return $this;
    }

    public function getAddress()
    {
        return $this->_address;
    }

    public function setAddress(CustomerAddress $address)
    {
        $this->_address = $address;
        return $this;
    }

    public function getPhone()
    {
        return $this->_phone;
    }

    public function setPhone(CustomerPhone $phone)
    {
        $this->_phone = $phone;
        return $this;
    }

    public function getSex()
    {
        return $this->_sex;
    }

    public function setSex($sex)
    {
        $this->_sex = $sex;
        return $this;
    }

    public function getBornAt()
    {
        return $this->_bornAt;
    }

    public function setBornAt($bornAt)
    {
        $this->_bornAt = $bornAt;
        return $this;
    }

    public function toArrayTransform()
    {
        $output = [];

        foreach (array_keys(get_object_vars($this)) as $property) {
            $key = $this->_fromCamelCase(substr($property, 1));
            if (is_object($this->$property)) {
                $output[$key] = $this->$property->toArray();
                continue;
            }

            $output[$key] = $this->$property;
        }

        return $output;
    }

    private function _fromCamelCase($string)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $string, $matches);
        $output = $matches[0];
        foreach ($output as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $output);
    }
}
