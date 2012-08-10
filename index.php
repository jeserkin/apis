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

		}
		break;

		default:
		{
			throw new Exception( 'No api specified!' );
		}
		break;
	}
}