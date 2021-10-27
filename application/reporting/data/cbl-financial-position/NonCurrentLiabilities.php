<?php
class NonCurrentLiabilities{
   private $amountPayableToDebtorsDue = 0;

    /**
     * @return int
     */
    public function getAmountPayableToDebtorsDue()
    {
        return $this->amountPayableToDebtorsDue;
    }

    /**
     * @param int $amountPayableToDebtorsDue
     */
    public function setAmountPayableToDebtorsDue($amountPayableToDebtorsDue)
    {
        $this->amountPayableToDebtorsDue = $amountPayableToDebtorsDue;
    }


}