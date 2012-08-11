<?php
/**
 * @author  Eugene Serkin <jserkin@gmail.com>
 * @version $Id$
 */

ini_set( 'error_reporing', -1 );
ini_set( 'display_errors', 1 );

require_once( __DIR__ . '/autoload.php' );

if ( isset( $_REQUEST['api'] ) )
{
	switch ( $_REQUEST['api'] )
	{
		case 'lastfm':
		{
			/*
			 * Api key    - 88ed512f8d498b141a94ceb3be19e7c9
			 * secret key - f0dde6a6782bb0f17cab5f72ad7d5970
			 */
			//
			$Api = new Api\Music\Lastfm( '88ed512f8d498b141a94ceb3be19e7c9' );
			var_dump( $Api->getAlbum()->search( 'Conspiracy of One' ) );
			//var_dump( $Api->getAlbum()->search( 'believe' ) );
			//var_dump( $Api->getAlbum()->getTopTags( 'The Offspring', 'Conspiracy of One' ) );
		}
		break;

		default:
		{
			throw new Exception( 'No api specified!' );
		}
		break;
	}
}