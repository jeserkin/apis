<?php
/**
 * @author  Eugene Serkin <jserkin@gmail.com>
 * @version $Id$
 */
namespace Api\Music\Lastfm\Album\Album;

class Collection
{
	/**
	 * @var array
	 */
	private $albums = array();

	/**
	 * @param Album $album
	 */
	public function addAlbum( Album $album )
	{
		$this->albums[] = $album;
	}

	/**
	 * @param array $albumsList
	 */
	public function addAlbums( array $albumsList )
	{
		$this->albums = array_merge( $this->albums, $albumsList );
	}
}
