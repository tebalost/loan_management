<?php


class MobileMoney
{
    private $mpesa = 0.0;
    private $ecocash = 0.0;

    /**
     * @return float
     */
    public function getMpesa()
    {
        return $this->mpesa;
    }

    /**
     * @param float $mpesa
     */
    public function setMpesa($mpesa)
    {
        $this->mpesa = $mpesa;
    }

    /**
     * @return float
     */
    public function getEcocash()
    {
        return $this->ecocash;
    }

    /**
     * @param float $ecocash
     */
    public function setEcocash($ecocash)
    {
        $this->ecocash = $ecocash;
    }
}