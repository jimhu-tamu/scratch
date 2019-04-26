<?php
/*
Testing page creation using HTMLform class methods
*/
class SpecialLoremPage2 extends SpecialPage {
	function __construct() {
		parent::__construct( 'SpecialLoremPage2' );
	}
 
	function execute( $par ) {
		$request = $this->getRequest();
		$output = $this->getOutput();
		$this->setHeaders();
		$user = $this->getUser();
		if ($user->isLoggedIn()){ 
			$formDescriptor = array(
				'pageName' => array(
					'class' => 'HTMLTextField',
					'label' => 'Name',
					'validation-callback' => array('SpecialLoremPage2', 'validatePageName'),
					'required' => true,
					),	
					
			);
		
			#this displays the form, makes the submit button, and creates a callback 	
			$htmlForm = new HTMLForm( $formDescriptor, $this->getContext(), 'myform' ); 
			$htmlForm->setSubmitText( 'Submit' ); # What text does the submit button display
			$htmlForm->setSubmitCallback( array( 'SpecialLoremPage2', 'makePage' ) );  
			$htmlForm->show(); # Displaying the form
		} else{
			$output = $this->getOutput();
			$output->showErrorPage( 'error', 'exception-nologin-text' );
		}
		
	}
	public static function makePage($formData, $form){
		$t = Title::NewFromText($formData['pageName']);
		$wikiPage = new WikiPage($t);
		$content = ContentHandler::makeContent(wfMessage('lorem-ipsum')->text(), $t);
		$reason = wfMessage('lorempage2-desc')->text();
		$wikiPage->doEditContent(
				$content,
				$reason,
				EDIT_NEW
			);
		#redirects page
		$out = $form->getOutput();
		$out -> redirect($t);			
	}
	
	public static function validatePageName($pageName){
        if ($pageName == '') {
        	return wfMessage('htmlform-required','parseinline')->text();
        }		
        return true;
	}
}