<?php

namespace Drupal\album\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\album\AlbumService;

/**
 * Defines 'Album' Block.
 *
 * @Block(
 *   id = "album",
 *   admin_label = @Translation("Album block"),
 *   category = @Translation("Hello World"),
 * )
 */
class AlbumBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Guzzle\Client instance.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * Construct of AlbumBlock class.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, AlbumService $http_client) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->httpClient = $http_client;
  }

  /**
   * Creates new object.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('album.album')
    );
  }

  /**
   * Function build().
   */
  public function build() {
    $config = $this->getConfiguration();
    if (isset($config['selected_album'])) {
      $albumId = $config['selected_album'];
      $photos = $this->httpClient->getAlbumPhotos($albumId);
      $result = '';
      foreach ($photos as $photo) {
        $result .= '
          <h4>' . $photo['title'] . '</h4><br>
          <img src=' . $photo['thumbnailUrl'] . '>';
      }
      return [
        '#markup' => $result,
      ];
    } else {
      return [
        '#markup' => $this->t("Album is not selected! Please, select someone!"),
      ];
    }
  }

  /**
   * Function blockForm().
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();
    $albums = $this->httpClient->getAlbums();
    $options = [];
    foreach ($albums as $album) {
      $options[$album->id] = $album->title;
    }
    $form['selected_album'] = [
      '#type' => 'select',
      '#title' => $this->t('Select album'),
      '#options' => $options,
      '#default_value' => isset($config['selected_album']) ? $config['selected_album'] : '',
    ];
    return $form;
  }

  /**
   * Function blockSubmit().
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $values = $form_state->getValues();
    $this->configuration['selected_album'] = $values['selected_album'];
  }

}
