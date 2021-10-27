<?php


class EquityAndLiabilities
{
    private $shareCapital = 0.0;
    private $fundCapital = 0.0;
    private $retainedEarnings = 0.0;

    /**
     * @return float
     */
    public function getShareCapital()
    {
        return $this->shareCapital;
    }

    /**
     * @param float $shareCapital
     */
    public function setShareCapital($shareCapital)
    {
        $this->shareCapital = $shareCapital;
    }

    /**
     * @return float
     */
    public function getFundCapital()
    {
        return $this->fundCapital;
    }

    /**
     * @param float $fundCapital
     */
    public function setFundCapital($fundCapital)
    {
        $this->fundCapital = $fundCapital;
    }

    /**
     * @return float
     */
    public function getRetainedEarnings()
    {
        return $this->retainedEarnings;
    }

    /**
     * @param float $retainedEarnings
     */
    public function setRetainedEarnings($retainedEarnings)
    {
        $this->retainedEarnings = $retainedEarnings;
    }
}