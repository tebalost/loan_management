<?php

class NonCurrentAssets
{
    private $officeFurnitureAndFittings = 0.0;
    private $premises = 0.0;
    private $otherNonCurrentAssets = 0.0;
    private $amountPayableToCreditors = 0.0;
    private $investments = 0.0;
    private $longTermLoans = 0.0;
    private $provisionForBadDebts = 0.0;

    /**
     * @param float $provisionForBadDebts
     */
    public function setProvisionForBadDebts($provisionForBadDebts)
    {
        $this->provisionForBadDebts = $provisionForBadDebts;
    }

    /**
     * @return float
     */
    public function getProvisionForBadDebts()
    {
        return $this->provisionForBadDebts;
    }

    /**
     * @return float
     */
    public function getLongTermLoans()
    {
        return $this->longTermLoans;
    }

    /**
     * @param float $longTermLoans
     */
    public function setLongTermLoans($longTermLoans)
    {
        $this->longTermLoans = $longTermLoans;
    }

    /**
     * @return float
     */
    public function getInvestments()
    {
        return $this->investments;
    }

    /**
     * @param float $investments
     */
    public function setInvestments($investments)
    {
        $this->investments = $investments;
    }


    /**
     * @return double
     */
    public function getAmountPayableToCreditors()
    {
        return $this->amountPayableToCreditors;
    }

    /**
     * @param double $amountPayableToCreditors
     */
    public function setAmountPayableToCreditors($amountPayableToCreditors)
    {
        $this->amountPayableToCreditors = $amountPayableToCreditors;
    }

    /**
     * @return double
     */
    public function getOfficeFurnitureAndFittings()
    {
        return $this->officeFurnitureAndFittings;
    }

    /**
     * @param double $officeFurnitureAndFittings
     */
    public function setOfficeFurnitureAndFittings($officeFurnitureAndFittings)
    {
        $this->officeFurnitureAndFittings = $officeFurnitureAndFittings;
    }

    /**
     * @return double
     */
    public function getPremises()
    {
        return $this->premises;
    }

    /**
     * @param double $premises
     */
    public function setPremises($premises)
    {
        $this->premises = $premises;
    }

    /**
     * @return double
     */
    public function getOtherNonCurrentAssets()
    {
        return $this->otherNonCurrentAssets;
    }

    /**
     * @param double $otherNonCurrentAssets
     */
    public function setOtherNonCurrentAssets($otherNonCurrentAssets)
    {
        $this->otherNonCurrentAssets = $otherNonCurrentAssets;
    }

    public function getTotalNonCurrentAssets(){
        return $this->officeFurnitureAndFittings +
        $this->premises +
        $this->otherNonCurrentAssets +
        $this->amountPayableToCreditors +
        $this->investments +
        $this->longTermLoans +
        $this->provisionForBadDebts;

    }


}