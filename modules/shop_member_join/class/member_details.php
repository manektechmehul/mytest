<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of memberDetails
 *
 * @author Ian
 */
class memberDetails {

    //put your code here
    public $name;
    public $email;
    public $address;
    public $country;
    public $countryCode;
    public $postcode;
    public $speciality;
    public $specialityId;

    public function SetFormMemberData($memberData) {
        $countryDetails = db_get_single_row("select * from countries where id = {$memberData['country_id']}");
        $speciality = db_get_single_value("select title from speciality where id = {$memberData['speciality']}");
        $this->name = $memberData['firstname'] . ' ' . $memberData['surname'];
        $this->email = $memberData['email'];
        $this->address = $memberData['address'];
        $this->postcode = $memberData['postcode'];
        $this->phone = $memberData['phone'];
        $this->country = $countryDetails['title'];
        $this->countryCode = $countryDetails['code'];
        $this->speciality = $speciality;
        $this->specialityId = $memberData['speciality'];
    }

    public function SetFormFields($formFields) {

        foreach ($formFields as $field)
            $fields[$field->fieldname] = $field;


        $this->name = $fields['firstname']->getData() . ' ' . $fields['surname']->getData();
        $countryId = $fields['country']->getData();
        $specialityId = $fields['speciality']->getData();
        $countryDetails = db_get_single_row("select * from countries where id = {$countryId}");
        $speciality = db_get_single_value("select title from speciality where id = {$specialityId}");
        $this->email = $fields['email']->getData();
        $this->address = $fields['address']->getData();
        $this->postcode = $fields['postcode']->getData();
        $this->phone = $fields['phone']->getData();
        $this->country = $countryDetails['title'];
        $this->countryCode = $countryDetails['code'];
        $this->speciality = $speciality;
        $this->specialityId = $specialityId;
    }

    public function Display() {
        global $smarty;

        $smarty->assign('details', $this);
        $detailsTemplateFile = dirname(dirname(__FILE__)) . '/templates/details.tpl';
        $smarty->display("file:" . $detailsTemplateFile);
    }

}
