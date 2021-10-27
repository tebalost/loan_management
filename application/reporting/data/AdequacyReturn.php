<?php
require_once "cbl-adequacy-return/Assets.php";
require_once "LiquidityReturn.php";
require_once "FinancialPosition.php";

class AdequacyReturn
{
    static function getAssets(){
        $assets = new Assets();
        $banksDeposit = LiquidityReturn::getBankingDeposit();
        $nonBanksNBFL = LiquidityReturn::getMobileMoneyDeposit();
        $nonCurrentAssets = FinancialPosition::getNonCurrentAssets();
        $currentAsset = FinancialPosition::getCurrentAssets();


        // setting deposit from banks
        $assets->setDepositWithOtherBanks(
            $banksDeposit->getPostBankDeposit() +
            $banksDeposit->getFnbDeposit() + $banksDeposit->getNetBankDeposit() + $banksDeposit->getStandardBank());

        // setting deposit by non banking financial institutions
        $assets->setDepositWithNBFI($nonBanksNBFL->getMpesa() + $nonBanksNBFL->getEcocash());

        // furniture,fittings and equipment
        $assets->setOfficeFurnitureAndEquipment($nonCurrentAssets->getOfficeFurnitureAndFittings());

//        // other assets
//        $assets->getOtherAssets(($nonCurrentAssets->getTotalNonCurrentAssets()-$nonCurrentAssets->getNetOfficeFurnitureAndFittings()) + $currentAsset->getTotalCurrentAsset());
         return $assets;
    }
}
