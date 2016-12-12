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

class Address extends \Magento\Framework\Model\AbstractModel
{
    private $_street;
    private $_streetNumber;
    private $_complementary;
    private $_neighborhood;
    private $_zipcode;

    /**
     * Constructor
     * @param \Magento\Customer\Model\Address
     */
    public function __construct(
        \Magento\Customer\Model\Address $address
    ) {
        $this
            ->setStreet($address->getStreetLine(1))
            ->setStreetNumber($address->getStreetLine(2))
            ->setComplementary($address->getStreetLine(3))
            ->setNeighborhood($address->getStreetLine(4))
            ->setZipcode($address->getPostcode());

    }

    public function getStreet()
    {
        return $this->_street;
    }

    public function setStreet($street)
    {
        $this->_street = $street;
        return $this;
    }

    public function getStreetNumber()
    {
        return $this->_streetNumber;
    }

    public function setStreetNumber($streetNumber)
    {
        $this->_streetNumber = $streetNumber;
        return $this;
    }

    public function getComplementary()
    {
        return $this->_complementary;
    }

    public function setComplementary($complementary)
    {
        $this->_complementary = $complementary;
        return $this;
    }

    public function getNeighborhood()
    {
        return $this->_neighborhood;
    }

    public function setNeighborhood($neighborhood)
    {
        $this->_neighborhood = $neighborhood;
        return $this;
    }

    public function getZipcode()
    {
        return $this->_zipcode;
    }

    public function setZipcode($zipCode)
    {
        $this->_zipcode = $zipCode;
        return $this;
    }
}
