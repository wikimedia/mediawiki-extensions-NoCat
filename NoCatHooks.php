<?php
class NoCatHooks {

	/**
	 * Add __NOCAT__
	 *
	 * @param Array $doubleUnderscoreIDs list of ids
	 */
	public function onGetDoubleUnderscoreIDs( array &$doubleUnderscoreIDs ) {
		$doubleUnderscoreIDs[] = 'nocat';
	}

	/**
	 * Delete all the categories if __NOCAT__ is set.
	 *
	 * @todo Ideally tracking categories would not be removed.
	 * @param Parser $parser
	 * @param string $text The html output
	 * @param StripState $stripState
	 */
	public function onParserAfterParse( Parser $parser, $text, $stripState ) {
		global $wgNoCatShowCat;
		$pout = $parser->getOutput();
		if ( $pout->getProperty( 'nocat' ) !== false ) {
			$old = $pout->setCategoryLinks( [] );
			$pout->addWarning( wfMessage( 'nocat-warning' )->text() );
			if ( $wgNoCatShowCat ) {
				$pout->addOutputHook( 'nocat_fakecategories', $old );
			}
		}
	}

	public function noCatParserOutputHook( OutputPage $out, ParserOutput $parserOutput, $data ) {
		if ( $out->getConfig()->get( 'NoCatShowCat' ) && $data ) {
			$out->addCategoryLinks( $data );
		}
	}
}
