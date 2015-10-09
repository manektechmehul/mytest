<?php
require_once $base_path.'/modules/promo_codes/class/promotion_code.php';

/**
 * Description of payment_details
 *
 * @author Ian
 */
class paymentDetails {
    public $url;
    public $instId;
    public $cartId;
    public $amount;
    public $currency;
    public $test;
    public $signatureFields;
    public $signature;
    public $percentage = 0;
    public $fixed = false;
    public $fullAmount;
    
    
    function __construct($activation, $action, $speciality, $fixedPrice = 0, $promotionCodeId = 0) {
        $this->url = PAYMENT_URL;
        $this->instId = PAYMENT_INSTALLATION_ID;
        $this->cartId = $activation;
        $this->action = $action;

        $this->setPrice($fixedPrice, $speciality, $promotionCodeId);
        $this->currency = SUBSCRIPTION_CURRENCY;
        $secret = PAYMENT_SECRET;
        $this->test = PAYMENT_TEST;

        $subscriptionDate = mktime(0,0,0,date('n'),date('j'), date('Y')+1);
        $subscriptionDateStr = date('Y-m-d', $subscriptionDate);

        $this->signatureFields =  "instId:amount:currency:cartId";
        $fieldsCode = "$secret;$this->signatureFields;$this->instId;$this->amount;$this->currency;$this->cartId";

        $this->signature = md5($fieldsCode);
    }

    function setPrice ($fixedPrice, $speciality, $promotionCodeId) {

        if ($fixedPrice == 0) {
            $amount = $this->getSpecialityPrice($speciality);
            if ($promotionCodeId > 0) {
                $percentage =  PromotionCode::getPercentage($promotionCodeId);
                if ($percentage > 0) {
                    $this->percentage = $percentage;
                    $this->fullAmount = round($amount);
                    $amount = round($amount * (100 - $percentage) / 100);
                }
            }
        }
        else {
            $this->fixed = true;
            $amount = $fixedPrice;
        }

        $this->amount = $amount;
    }
    
    function getSpecialityPrice($speciality) {
        return db_get_single_value("select price from speciality where id = '$speciality'");        
    }

    function outputHiddenForm()
    {
        global $smarty;
        $smarty->assign('payment', $this);
        $detailsTemplateFile =  dirname(dirname(__FILE__)).'/templates/hiddenform.tpl';
        $smarty->display("file:".$detailsTemplateFile);
    }
    
}

