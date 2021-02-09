<?php

/**
 * @file
 * Contains \Drupal\album\Form\AlbumForm.
 **/

namespace Drupal\album\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;
// use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\album\AlbumService;

class AlbumForm extends FormBase {

    /**
     * Guzzle\Client instance.
     *
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;

    /**
     * Construct of AlbumBlock class.
     */
    public function __construct(AlbumService $http_client) {
        $this->httpClient = $http_client;
    }

    public static function create(ContainerInterface $container) {
        // Instantiates this form class.
        return new static(
          // Load the service required to construct this class.
          $container->get('album.album')
        );
      }

    public function getFormId() {
        return 'album_simple_form';
    }

    /**
    * {@inheritdoc}
    */

    public function buildForm(array $form, FormStateInterface $form_state) {
        // Return array of Form API elements.
        
        $form['field_user_id'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('User ID:'),
            '#ajax' => [
                'wrapper' => 'field_albums',
                'callback' => [$this, 'validateFieldID'],
                'event' => 'change',
                'progress' => [
                    'type' => 'throbber',
                    'message' => $this->t('Verifying email..'),
                  ],
            ],
            '#prefix' => '<div class="error-messages"></div>',
        );

        $form['field_album'] = array(
            '#type' => 'select',
            '#title' => $this->t('Select album:'),
            '#options' => [],
            '#empty_option' => '- Select -',
            '#ajax' => [
                'callback' => '::getResult',
            ],
            '#prefix' => '<div id="field_albums">',
            '#suffix' => '</div>',
        );

        $form['output'] = array(
            '#type' => 'markup',
            '#suffix' => '<div class="error-messages"></div>',
            '#prefix' => '<div id="dummy-photos"></div>',
        );

        return $form;
    }

    public function validateFieldID(array &$form, FormStateInterface $form_state) {
        $response = new AjaxResponse();
        $userID = $form['field_user_id']['#value'];

        var_dump($userID);

        
        if(isset($userID)) {
            $list_albums = $this->httpClient->getAlbumsByUserID($userID);
            var_dump($list_albums);


            $form['field_album']['#options'] = $list_albums;

            var_dump($form['field_album']['#options']);
            return $form['field_album'];

        } else {
            $response->addCommand(new HtmlCommand('.error-messages', t('Please, input number!')));
            return $response;
        }
    }

    public function getResult(array &$form, FormStateInterface $form_state) {
        $response = new AjaxResponse();
        $photos = $this->httpClient->getAlbumPhotos($form_state->getValue('field_album'));
        $result = '';
        foreach ($photos as $photo) {
            $result .= '
            <h4>' . $photo['title'] . '</h4><br>
            <img src=' . $photo['thumbnailUrl'] . '>';
        }
        $response->addCommand(new HtmlCommand('#dummy-photos', $result));

        return $response;
    }

    /**
    * {@inheritdoc}
    */

    public function validateForm(array &$form, FormStateInterface $form_state) {
        // Validation covered in later recipe, required to satisfy interface
        
    }
    
    /**
    * {@inheritdoc}
    */
    
    public function submitForm(array &$form, FormStateInterface $form_state) {
        // Validation covered in later recipe, required to satisfy interface
    
    }
}