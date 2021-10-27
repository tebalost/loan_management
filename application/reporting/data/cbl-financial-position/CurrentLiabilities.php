<?php

class CurrentLiabilities{
   private $amountPayableToCreditors = 0.0;
   private $provisionForTax = 0.0;
   private $dividends = 0.0;
   private $provisionInterest = 0.0;

    /**
     * @return float
     */
    public function getAmountPayableToCreditors()
    {
        return $this->amountPayableToCreditors;
    }

    /**
     * @param float $amountPayableToCreditors
     */
    public function setAmountPayableToCreditors($amountPayableToCreditors)
    {
        $this->amountPayableToCreditors = $amountPayableToCreditors;
    }

    /**
     * @return float
     */
    public function getProvisionForTax()
    {
        return $this->provisionForTax;
    }

    /**
     * @param float $provisionForTax
     */
    public function setProvisionForTax($provisionForTax)
    {
        $this->provisionForTax = $provisionForTax;
    }

    /**
     * @return float
     */
    public function getDividends()
    {
        return $this->dividends;
    }

    /**
     * @param float $dividends
     */
    public function setDividends($dividends)
    {
        $this->dividends = $dividends;
    }

    /**
     * @return float
     */
    public function getProvisionInterest()
    {
        return $this->provisionfloaterest;
    }

    /**
     * @param float $provisionInterest
     */
    public function setProvisionInterest($provisionInterest)
    {
        $this->provisionInterest = $provisionInterest;
    }
   


}