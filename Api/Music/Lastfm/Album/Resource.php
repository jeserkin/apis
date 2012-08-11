<?php
/**
 * @author  Eugene Serkin <jserkin@gmail.com>
 * @version $Id$
 */
namespace Api\Music\Lastfm\Album;

class Resource
{
	const
		PREFIX = 'album.';

	/**
	 * @var \Api\Music\Lastfm
	 */
	private $lastfm;

	/**
	 * @var string
	 */
	private $apiUrl;

	/**
	 * @var string
	 */
	private $apiKey;

	/**
	 * @param \Api\Music\Lastfm $lastfm
	 */
	public function __construct( \Api\Music\Lastfm $lastfm )
	{
		$this->lastfm = $lastfm;
		$this->apiUrl = $this->lastfm->getApiUrl();
		$this->apiKey = $this->lastfm->getApiKey();
	}

	/**
	 * @param string $albumName
	 * @return \Api\Music\Lastfm\Album\Album\Collection
	 */
	public function search( $albumName )
	{
		$Curl = new \CurlRequest();

		$params = array(
			'method'  => self::PREFIX . __FUNCTION__,
			'api_key' => $this->apiKey,
			'album'   => $albumName,
		);

		$Curl->curlGet( $this->apiUrl, $params );

		/** @var $Xml \SimpleXMLElement */
		$Xml = simplexml_load_string( trim( $Curl->fetch() ) );
		$Xml->registerXPathNamespace( 'opensearch', 'http://a9.com/-/spec/opensearch/1.1/' );

		$totalResults = (int) current( $Xml->xpath( '//opensearch:totalResults' ) );
		$itemsPerPage = (int) current( $Xml->xpath( '//opensearch:itemsPerPage' ) );

		$pages = ceil( $totalResults / $itemsPerPage );

		$AlbumCollection = new \Api\Music\Lastfm\Album\Album\Collection();
		$iterations      = 30;

		for ( $i = 1; $i <= $pages; $i++ )
		{
			if ( $i > $iterations )
			{
				$iterations += 30;
				sleep( 10 );
			}

			$params['page'] = $i;

			$AlbumCollection->addAlbums( $this->fetchAdditionalPage( $params ) );
		}

		return $AlbumCollection;
	}

	/**
	 * @param $artistName
	 * @param $albumName
	 * @param bool $autocorrect
	 * @return \Api\Music\Lastfm\Album\Tag\Collection
	 */
	public function getTopTags( $artistName, $albumName, $autocorrect = false )
	{
		$params = array(
			'method'      => self::PREFIX . __FUNCTION__,
			'api_key'     => $this->apiKey,
			'artist'      => $artistName,
			'album'       => $albumName,
			'autocorrect' => $autocorrect,
		);

		return $this->fetchTopTagCollection( $params );
	}

	/**
	 * @param string $mbid
	 * @param bool $autocorrect
	 * @return \Api\Music\Lastfm\Album\Tag\Collection
	 */
	public function getTopTagsByMbid( $mbid, $autocorrect = false )
	{
		$params = array(
			'method'      => self::PREFIX . __FUNCTION__,
			'api_key'     => $this->apiKey,
			'mbid'        => $mbid,
			'autocorrect' => $autocorrect,
		);

		return $this->fetchTopTagCollection( $params );
	}

	/**
	 * @param array $params
	 * @return \Api\Music\Lastfm\Album\Tag\Collection
	 */
	private function fetchTopTagCollection( array $params )
	{
		$Curl = new \CurlRequest();

		$Curl->curlGet( $this->apiUrl, $params );

		$Xml               = simplexml_load_string( trim( $Curl->fetch() ) );
		$TopTagsCollection = new \Api\Music\Lastfm\Album\Tag\Collection();

		$TopTagsCollection->setArtistName( trim( $Xml->toptags['artist'] ) );
		$TopTagsCollection->setAlbumName( trim( $Xml->toptags['album'] ) );

		foreach ( $Xml->toptags->tag as $tagRow )
		{
			$Tag = new \Api\Music\Lastfm\Album\Tag\Tag();

			$Tag->setName( trim( $tagRow->name ) );
			$Tag->setUrl( trim( $tagRow->url ) );
			$Tag->setCount( (int) $tagRow->count );

			$TopTagsCollection->addTag( $Tag );
		}

		return $TopTagsCollection;
	}

	/**
	 * @param array $params
	 * @return array
	 */
	private function fetchAdditionalPage( array $params )
	{
		$Curl = new \CurlRequest();

		$Curl->curlGet( $this->apiUrl, $params );

		/** @var $Xml \SimpleXMLElement */
		$Xml = simplexml_load_string( trim( $Curl->fetch() ) );

		$albums = array();

		foreach ( $Xml->results->albummatches->album as $albumRow )
		{
			$Album = new \Api\Music\Lastfm\Album\Album\Album();

			$Album->setId( (int) $albumRow->id );
			$Album->setName( (string) $albumRow->name );
			$Album->setArtist( (string) $albumRow->artist );
			$Album->setUrl( (string) $albumRow->url );
			$Album->setStreamable( (bool) ( (int) $albumRow->streamable ) );
			$Album->setMbid( (string) $albumRow->mbid );

			foreach ( $albumRow->image as $image )
			{
				if ( '' === ( $imageUrl = trim( $image ) ) )
				{
					continue;
				}

				$Album->addImage( (string) $image['size'], $imageUrl );
			}

			$albums[] = $Album;
		}

		return $albums;
	}
}