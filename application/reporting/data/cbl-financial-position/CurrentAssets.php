<?php


class CurrentAssets
{
    private $otherDepositMaturity = 0.0;
    private $unearnedInterest = 0.0;
    private $accountReceivable = 0.0;
    private $loanPayableLessThanAYear = 0.0;
    private $ProvisionOfDoubtful = 0.0;
    private $otherCurrentAssets = 0.0;
    private $bankDepositMaturity = 0;
    private $cash = 0.0;

    /**
     * @return double
     */
    public function getCash()
    {
        return $this->cash;
    }

    /**
     * @param double $cash
     */
    public function setCash($cash)
    {
        $this->cash = $cash;
    }


    /**
     * @return double
     */
    public function getBankDepositMaturity()
    {
        return $this->bankDepositMaturity;
    }

    /**
     * @param double $bankDepositMaturity
     */
    public function setBankDepositMaturity($bankDepositMaturity)
    {
        $this->bankDepositMaturity = $bankDepositMaturity;
    }

    /**
     * @return double
     */
    public function getOtherDepositMaturity()
    {
        return $this->otherDepositMaturity;
    }

    /**
     * @param double $otherDepositMaturity
     */
    public function setOtherDepositMaturity($otherDepositMaturity)
    {
        $this->otherDepositMaturity = $otherDepositMaturity;
    }

    /**
     * @return double
     */
    public function getUnearnedInterest()
    {
        return $this->unearnedInterest;
    }

    /**
     * @param double $unearnedInterest
     */
    public function setUnearnedInterest($unearnedInterest)
    {
        $this->unearnedInterest = $unearnedInterest;
    }

    /**
     * @return double
     */
    public function getAccountReceivable()
    {
        return $this->accountReceivable;
    }

    /**
     * @param double $accountReceivable
     */
    public function setAccountReceivable($accountReceivable)
    {
        $this->accountReceivable = $accountReceivable;
    }

    /**
     * @return double
     */
    public function getLoanPayableLessThanAYear()
    {
        return $this->loanPayableLessThanAYear;
    }

    /**
     * @param double $loanPayableLessThanAYear
     */
    public function setLoanPayableLessThanAYear($loanPayableLessThanAYear)
    {
        $this->loanPayableLessThanAYear = $loanPayableLessThanAYear;
    }

    /**
     * @return double
     */
    public function getProvisionOfDoubtful()
    {
        return $this->ProvisionOfDoubtful;
    }

    /**
     * @param int $ProvitionOfDoubtful
     */
    public function setProvisionOfDoubtful($ProvisionOfDoubtful)
    {
        $this->ProvisionOfDoubtful = $ProvisionOfDoubtful;
    }


    /**
     * @return double
     */
    public function getOtherCurrentAssets()
    {
        return $this->otherCurrentAssets;
    }

    /**
     * @param double $otherCurrentAssets
     */
    public function setOtherCurrentAssets($otherCurrentAssets)
    {
        $this->otherCurrentAssets = $otherCurrentAssets;
    }

    /**
     * @return double
     */
    public function getTotalCurrentAsset()
    {
        return $this->otherDepositMaturity
        + $this->unearnedInterest
        + $this->accountReceivable
        + $this->loanPayableLessThanAYear
        + $this->ProvisionOfDoubtful
        + $this->otherCurrentAssets
        + $this->bankDepositMaturity
        + $this->cash;
    }

}