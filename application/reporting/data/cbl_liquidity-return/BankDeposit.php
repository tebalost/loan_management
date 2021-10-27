<?php
class BankDeposit
{
    private $netBankDeposit = 0.0;
    private $fnbDeposit = 0.0;
    private $postBankDeposit = 0.0;
    private $StandardBank = 0.0;

    /**
     * @return float
     */
    public function getStandardBank()
    {
        return $this->StandardBank;
    }

    /**
     * @param float $StandardBank
     */
    public function setStandardBank($StandardBank)
    {
        $this->StandardBank = $StandardBank;
    }

    /**
     * @return float
     */
    public function getNetBankDeposit()
    {
        return $this->netBankDeposit;
    }

    /**
     * @param float $netBankDeposit
     */
    public function setNetBankDeposit($netBankDeposit)
    {
        $this->netBankDeposit = $netBankDeposit;
    }

    /**
     * @return float
     */
    public function getFnbDeposit()
    {
        return $this->fnbDeposit;
    }

    /**
     * @param float $fnbDeposit
     */
    public function setFnbDeposit($fnbDeposit)
    {
        $this->fnbDeposit = $fnbDeposit;
    }

    /**
     * @return float
     */
    public function getPostBankDeposit()
    {
        return $this->postBankDeposit;
    }

    /**
     * @param float $postBankDeposit
     */
    public function setPostBankDespoit($postBankDeposit)
    {
        $this->postBankDespoit = $postBankDeposit;
    }
}