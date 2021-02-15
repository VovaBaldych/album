<?php

namespace Drupal\album;

use GuzzleHttp\ClientInterface;
use Drupal\Core\Cache\CacheBackendInterface;

/**
 * Defines PhotosService class.
 */
class AlbumService {

  /**
   * Implement AlbumService class.
   *
   * @var httpClient
   * Keep httpClient object.
   */

  private $httpClient;

  /**
   * Implement AlbumService class.
   *
   * @var cacheBackend
   * Keep cacheBackend object.
   */
  private $cacheBackend;

  /**
   * Inject HttpClient in PhotosService class.
   */
  public function __construct(ClientInterface $http_client, CacheBackendInterface $cache_backend) {
    $this->httpClient = $http_client;
    $this->cacheBackend = $cache_backend;
  }

  /**
   * Function returns all albums.
   */
  public function getAlbums() {
    $response = $this->httpClient->request('GET', 'https://jsonplaceholder.typicode.com/albums');
    $albums = json_decode($response->getBody()->getContents());
    return $albums;
  }

  /**
   * Function returns all albums by user ID.
   */
  public function getAlbumsByUserId($userID) {
    $cid = 'tag_albums_cache_data_' . $userID;

    if ($cache = $this->cacheBackend->get($cid)) {
      $albums = $cache->data;
    }
    else {
      $tags = ['tag_user_id:' . $userID];
      $response = $this->httpClient->request('GET', 'https://jsonplaceholder.typicode.com/albums?userId=' . $userID);
      $albumsAll = json_decode($response->getBody()->getContents());

      if ($albumsAll) {
        foreach ($albumsAll as $album) {
          $albums[$album->id] = $album->title;
        }
        $this->cacheBackend->set('tag_albums_cache_data_' . $userID, $albums, REQUEST_TIME + (3600), $tags);
        return $albums;
      }
      else {
        return [];
      }
    }
    return $albums;
  }

  /**
   * Function returns photos from $albumId.
   */
  public function getAlbumPhotos($albumId) {
    $response = $this->httpClient->request('GET', 'https://jsonplaceholder.typicode.com/photos');
    $photos = json_decode($response->getBody()->getContents());
    foreach ($photos as $photo) {
      if ($photo->albumId == $albumId) {
        $album[] = [
          'title' => $photo->title,
          'thumbnailUrl' => $photo->thumbnailUrl,
        ];
      }
    }
    return $album;
  }

}
