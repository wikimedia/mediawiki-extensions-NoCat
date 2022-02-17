<?php
class NoCatHooks {

	/**
	 * Add __NOCAT__
	 *
	 * @param array &$doubleUnderscoreIDs list of ids
	 */
	public static function onGetDoubleUnderscoreIDs( array &$doubleUnderscoreIDs ) {
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
	public static function onParserAfterParse( Parser $parser, $text, $stripState ) {
		global $wgNoCatShowCat;
		$pout = $parser->getOutput();
		// T301915
		if ( ( $pout->getPageProperty( 'nocat' ) ?? false ) !== false ) {
			$old = $pout->setCategories( [] );
			$pout->addWarning( wfMessage( 'nocat-warning' )->text() );
			if ( $wgNoCatShowCat ) {
				$pout->setExtensionData( 'nocat_fakecategories', $old );
			}
		}
	}

	/**
	 * Add removed categories as links if configured to do so.
	 *
	 * @param OutputPage $out
	 * @param ParserOutput $pout
	 */
	public static function onOutputPageParserOutput( OutputPage $out, ParserOutput $pout ) {
		if ( $out->getConfig()->get( 'NoCatShowCat' ) ) {
			$cats = $pout->getExtensionData( 'nocat_fakecategories' );
			if ( $cats ) {
				$out->addCategoryLinks( $cats );
			}
		}
	}
}
