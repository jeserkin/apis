<?php
/**
 * @author  Eugene Serkin <jserkin@gmail.com>
 * @version $Id$
 */

function __autoload( $className )
{
	$fileExists = false;

	if ( false === strpos( $className, '_' ) )
	{
		$fileExists = file_exists( __DIR__ . '/libs/' . $className . '.php' );

		if ( $fileExists )
		{
			require_once( __DIR__ . '/libs/' . $className . '.php' );
		}
		else
		{
			throw new Exception( 'Helper class: ' . $className . ', not loaded!' );
		}
	}

	if ( ! $fileExists )
	{
		$classPath       = explode( '_', $className );
		$singleApiExists = file_exists( __DIR__ . '/' . implode( '/', $classPath ) . '/' . end( $classPath ) . '.php' );

		if ( $singleApiExists )
		{
			require_once( __DIR__ . '/' . implode( '/', $classPath ) . '/' . end( $classPath ) . '.php' );
		}
		elseif ( ! $singleApiExists )
		{
			$fileName = array_pop( $classPath );

			if ( file_exists( __DIR__ . '/' . implode( '/', $classPath ) . '/' . $fileName . '.php' ) )
			{
				require_once( __DIR__ . '/' . implode( '/', $classPath ) . '/' . $fileName . '.php' );
			}
			else
			{
				throw new Exception( 'Api class not found' );
			}
		}
		else
		{
			throw new Exception( 'Api class not found' );
		}
	}
}