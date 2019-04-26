<?php
/*
Testing page creation without using HTMLform class methods

This works

*/
class SpecialLoremPage1 extends SpecialPage {
	function __construct() {
		parent::__construct( 'SpecialLoremPage1' );
	}
 
	function execute( $par ) {
		$request = $this->getRequest();
		$output = $this->getOutput();
		$this->setHeaders();
		$user = $this->getUser();
		if ($user->isLoggedIn()){
 
			# Get request data from, e.g.
			$pageName = $request->getText( 'lorempage-pageName' ); 
			# Do stuff
			if( isset($pageName ) && $pageName != ''){
				$t = Title::NewFromText($pageName);
				self::makePage($t);
				$output->redirect($t->getFullURL());
			}
			$output->addWikiText('Enter a page name');
			$output->addHTML(self::formHTML($pageName));
		}else{
			$output = $this->getOutput();
			$output->showErrorPage( 'error', 'exception-nologin-text' );
		}	
		
	}
	public static function makePage(Title $t){
		$wikiPage = new WikiPage($t);
		$content = ContentHandler::makeContent(wfMessage('lorem-ipsum')->text(), $t);
		$reason = wfMessage('lorempage1-desc')->text();
		$wikiPage->doEditContent(
				$content,
				$reason,
				EDIT_NEW
			);
			
	}
	
	public static function formHTML($pageName = ''){
		return "
<form>
<input type='text' name = 'lorempage-pageName'>$pageName</input>
<input type = 'submit' name ='lorempage-submit' />
</form>	
	";
	}
}