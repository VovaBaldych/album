<?php

namespace Drupal\album\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\album\AlbumService;

/**
 * Implement AlbumForm class.
 */
class AlbumForm extends FormBase {

  /**
   * {@inheritdoc}
   *
   * Guzzle\Client instance.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * {@inheritdoc}
   *
   * Construct of AlbumForm class.
   */
  public function __construct(AlbumService $http_client) {
    $this->httpClient = $http_client;
  }

  /**
   * {@inheritdoc}
   *
   * Implement function 'create'.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('album.album')
    );
  }

  /**
   * {@inheritdoc}
   *
   * Implement function 'create'.
   */
  public function getFormId() {
    return 'album_simple_form';
  }

  /**
   * {@inheritdoc}
   *
   * Implement function 'buildForm'.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#theme'] = 'album_form';
    $form['#attached']['library'][] = 'album/form-styles';

    $form['field_user_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('User ID:'),
      '#ajax' => [
        'wrapper' => 'field-albums',
        'callback' => '::validateFieldId',
        'event' => 'change',
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t('Verifying email..'),
        ],
      ],
      '#prefix' => '<div class="error-messages"></div>',
    ];

    $form['field_album'] = [
      '#type' => 'select',
      '#title' => $this->t('Select album:'),
      '#options' => [],
      '#empty_option' => '- Select -',
      '#ajax' => [
        'callback' => '::getResult',
      ],
      '#prefix' => '<div id="field-albums">',
      '#suffix' => '</div>',
    ];

    $form['output'] = [
      '#type' => 'markup',
      '#suffix' => '<div class="error-messages"></div>',
      '#prefix' => '<div id="dummy-photos"></div>',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   *
   * Implement function 'validateFieldID'.
   */
  public function validateFieldId(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $userID = $form_state->getValue('field_user_id');
    $form['field_album']['#options'] = ['default' => $this->t('Select')];
    if (!isset($userID) || !is_numeric($userID)) {
      $response->addCommand(new HtmlCommand('.error-messages', $this->t('Please, input number!')));
      return $response;
    }
    $form['field_album']['#options'] += $this->httpClient->getAlbumsByUserID($userID);
    return $form['field_album'];
  }

  /**
   * {@inheritdoc}
   *
   * Implement function 'getResult'.
   */
  public function getResult(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $photos = $this->httpClient->getAlbumPhotos($form_state->getValue('field_album'));
    $photos_list = '';

    foreach ($photos as $photo) {
      $photos_list .= '
            <h4>' . $photo['title'] . '</h4><br>
            <img src=' . $photo['thumbnailUrl'] . '>';
    }
    $response->addCommand(new HtmlCommand('#dummy-photos', $photos_list));

    return $response;
  }

  /**
   * {@inheritdoc}
   *
   * Implement function 'validateForm'.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {}

  /**
   * {@inheritdoc}
   *
   * Implement function 'submitForm'.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {}

}
