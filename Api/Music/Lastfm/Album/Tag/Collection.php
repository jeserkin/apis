<?php
/**
 * @author  Eugene Serkin <jserkin@gmail.com>
 * @version $Id$
 */
namespace Api\Music\Lastfm\Album\Tag;

class Collection
{
	/**
	 * @var string
	 */
	private $artistName;

	/**
	 * @var string
	 */
	private $albumName;

	/**
	 * @var array
	 */
	private $tags = array();

	/**
	 * @param Tag $tag
	 */
	public function addTag( Tag $tag )
	{
		$this->tags[] = $tag;
	}

	/**
	 * @param array $tags
	 */
	public function addTags( array $tags )
	{
		$this->tags = array_merge( $this->tags, $tags );
	}

	/**
	 * @return array
	 */
	public function getTags()
	{
		return $this->tags;
	}

	/**
	 * @param string $artistName
	 */
	public function setArtistName( $artistName )
	{
		$this->artistName = $artistName;
	}

	/**
	 * @return string
	 */
	public function getArtistName()
	{
		return $this->artistName;
	}

	/**
	 * @param string $albumName
	 */
	public function setAlbumName( $albumName )
	{
		$this->albumName = $albumName;
	}

	/**
	 * @return string
	 */
	public function getAlbumName()
	{
		return $this->albumName;
	}
}
