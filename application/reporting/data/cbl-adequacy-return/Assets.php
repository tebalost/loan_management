<?php


class Assets
{
    private $depositWithOtherBanks = 0.0;
    private $depositWithNBFI  = 0.0;
    private $officeFurnitureAndEquipment = 0.0;
    private $otherAssets = 0.0;
    private $depositWithCBL = 0.0;

    /**
     * @return float
     */
    public function getDepositWithCBL()
    {
        return $this->depositWithCBL;
    }

    /**
     * @param float $depositWithCBL
     */
    public function setDepositWithCBL($depositWithCBL)
    {
        $this->depositWithCBL = $depositWithCBL;
    }


    /**
     * @return float
     */
    public function getDepositWithOtherBanks()
    {
        return $this->depositWithOtherBanks;
    }

    /**
     * @param float $depositWithOtherBanks
     */
    public function setDepositWithOtherBanks($depositWithOtherBanks)
    {
        $this->depositWithOtherBanks = $depositWithOtherBanks;
    }

    /**
     * @return float
     */
    public function getDepositWithNBFI()
    {
        return $this->depositWithNBFI;
    }

    /**
     * @param float $depositWithNBFI
     */
    public function setDepositWithNBFI($depositWithNBFI)
    {
        $this->depositWithNBFI = $depositWithNBFI;
    }

    /**
     * @return float
     */
    public function getOfficeFurnitureAndEquipment()
    {
        return $this->officeFurnitureAndEquipment;
    }

    /**
     * @param float $officeFurnitureAndEquipment
     */
    public function setOfficeFurnitureAndEquipment($officeFurnitureAndEquipment)
    {
        $this->officeFurnitureAndEquipment = $officeFurnitureAndEquipment;
    }

    /**
     * @return float
     */
    public function getOtherAssets()
    {
        return $this->otherAssets;
    }

    /**
     * @param float $otherAssets
     */
    public function setOtherAssets($otherAssets)
    {
        $this->otherAssets = $otherAssets;
    }

}