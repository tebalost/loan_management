<?php


class Expenses
{
    private $computerCharges = 0.0;              // include mailing and communication
    private $salariesAndPayAsYouEarn = 0.0;
    private $otherExpenses = 0.0;
    private $accommodation = 0.0;
    private $badDebts = 0.0;

    /**
     * @return float
     */
    public function getBadDebts()
    {
        return $this->badDebts;
    }

    /**
     * @param float $badDebts
     */
    public function setBadDebts($badDebts)
    {
        $this->badDebts = $badDebts;
    }

    /**
     * @return float
     */
    public function getAccommodation()
    {
        return $this->accommodation;
    }

    /**
     * @param float $accommodation
     */
    public function setAccommodation($accommodation)
    {
        $this->accommodation = $accommodation;
    }

    /**
     * @return float
     */
    public function getComputerCharges()
    {
        return $this->computerCharges;
    }

    /**
     * @param float $computerCharges
     */
    public function setComputerCharges($computerCharges)
    {
        $this->computerCharges = $computerCharges;
    }

    /**
     * @return float
     */
    public function getSalariesAndPayAsYouEarn()
    {
        return $this->salariesAndPayAsYouEarn;
    }

    /**
     * @param float $salariesAndPayAsYouEarn
     */
    public function setSalariesAndPayAsYouEarn($salariesAndPayAsYouEarn)
    {
        $this->salariesAndPayAsYouEarn = $salariesAndPayAsYouEarn;
    }

    /**
     * @return float
     */
    public function getOtherExpenses()
    {
        return $this->otherExpenses;
    }

    /**
     * @param float $otherExpenses
     */
    public function setOtherExpenses($otherExpenses)
    {
        $this->otherExpenses = $otherExpenses;
    }

    /**
     * @return float
     */

    public function getTotalExpenses(){
        return $this->computerCharges+              // include mailing and communication
                $this->salariesAndPayAsYouEarn+
                $this->otherExpenses+
                $this->accommodation+
                $this->badDebts;
    }

}