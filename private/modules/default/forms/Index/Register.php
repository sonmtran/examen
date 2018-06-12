<?php
/**
 * Register form
 * 
 * @author Dai
 * @since 20-01-2015
 * @verson 1.0
 */

class Default_Form_Index_Register extends Zend_Form
{
	public function init()
	{
		$this->setAttrib('enctype', 'multipart/form-data');
		
		$id_center = $this->createElement('select', 'id_center')
						  ->setLabel('contact_centre')
						  ->setAttrib('class', 'dropdown-select')
						  ->setAttrib('required', 'required')
						  ->setRequired(true)
            ->setRegisterInArrayValidator(false)
						  ->addFilters(array('StripTags', 'StringTrim'));

		$parent_name  = $this->createElement('text', 'parent_name')
							 ->setLabel('parent_name')
							 ->addFilters(array('StripTags', 'StringTrim'));
		
		$fullname = $this->createElement('text', 'fullname')
						 ->setLabel('stu_name')
						 ->setRequired(true)
						 ->addFilters(array('StripTags', 'StringTrim'));
		
		$gender = $this->createElement('select', 'gender')
					   ->setLabel('gender')
					   ->setAttrib('class', 'dropdown-select')
					   ->setAttrib('style', 'width:196px')
					   ->setAttrib('required', 'required')
					   ->setRequired(true)
					   ->addFilters(array('StripTags', 'StringTrim'))
					   ->setMultiOptions(array(
					   			''  => '--------------------',
					   			'1' => $this->getView()->translate('male'),
					   			'0' => $this->getView()->translate('female')
					   ));
		
		$dob = $this->createElement('text', 'dob')
						 ->setLabel('dob')
						 ->setAttrib('placeholder', 'dd/mm/yyyy')
						 ->setAttrib('required', 'required')
						 ->setRequired(true)
						 ->addFilters(array('StripTags', 'StringTrim'))
						 ->addValidator(new Zend_Validate_Date(array('format' => 'DD/MM/YYYY')));
		
		$mobile = $this->createElement('text', 'mobile')
					   ->setLabel('mobile')
					   ->setAttrib('required', 'required')
					   ->setRequired(true)
					   ->addFilters(array('StripTags', 'StringTrim'));		
		
		$email = $this->createElement('text', 'email')
					  ->setLabel('email')
					  ->setAttrib('required', 'required')
					  ->setRequired(true)
					  ->addFilters(array('StripTags', 'StringTrim'))
					  ->addValidator('EmailAddress');
		
		$address = $this->createElement('text', 'address')
						->setLabel('address_2')
						->setAttrib('required', 'required')
						->setRequired(true)
						->addFilters(array('StripTags', 'StringTrim'));
		
		$id_city = $this->createElement('select', 'id_city')
						->setLabel('re_city')
						->setAttrib('class', 'dropdown-select')
						->setAttrib('required', 'required')
						->setRequired(true)
            ->setRegisterInArrayValidator(false)
						->addFilters(array('StripTags', 'StringTrim'));

        $id_city2 = $this->createElement('select', 'id_city2')
            ->setLabel('re_city')
            ->setAttrib('class', 'dropdown-select')
            ->setRegisterInArrayValidator(false)
            ->addFilters(array('StripTags', 'StringTrim'));

		
		$submit = $this->createElement('submit', 'formsubmit')
					   ->setAttrib('class', 'button')
					   ->setLabel('Reg_now');
		
		//thiết lập file view trang trí cho form
		$this->setDecorators(array(
				array('viewScript',
						array(
								'viewScript'=>'index/form.phtml'
						),
				)));
		
		$this->addElements(array(
				$id_center,
				$parent_name,
				$fullname,
				$gender,
				$dob,
				$mobile,
				$email,
				$address,
				$id_city,   $id_city2,
				$submit
		));
		
		//xóa bỏ các thẻ html mặc định của form
		foreach ($this->getElements() as $ele)
		{
			$ele->removeDecorator('Label');
			$ele->removeDecorator('HtmlTag');
			$ele->removeDecorator('Description');
			$ele->removeDecorator('Errors');
			$ele->removeDecorator('DtDdWrapper');
		}
	}
	
	
}

/** End: Default_Form_Register **/
/** Location: module/default/forms/Register.php **/