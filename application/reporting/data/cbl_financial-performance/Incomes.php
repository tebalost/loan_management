<?php


class Incomes
{
    // incomes
    private $loanInterest = 0.0;
    private $feeIncome = 0.0;
    private $anyOtherIncome = 0.0;

    /**
     * @return float
     */
    public function getLoanInterest()
    {
        return $this->loanInterest;
    }

    /**
     * @param float $loanInterest
     */
    public function setLoanInterest($loanInterest)
    {
        $this->loanInterest = $loanInterest;
    }

    /**
     * @return float
     */
    public function getFeeIncome()
    {
        return $this->feeIncome;
    }

    /**
     * @param float $feeIncome
     */
    public function setFeeIncome($feeIncome)
    {
        $this->feeIncome = $feeIncome;
    }

    /**
     * @return float
     */
    public function getAnyOtherIncome()
    {
        return $this->anyOtherIncome;
    }

    /**
     * @param float $anyOtherIncome
     */
    public function setAnyOtherIncome($anyOtherIncome)
    {
        $this->anyOtherIncome = $anyOtherIncome;
    }

    /**
     * @return  float
     */
    public function getTotalIncome(){
        return $this->loanInterest + $this->feeIncome+ $this->anyOtherIncome;
    }

}