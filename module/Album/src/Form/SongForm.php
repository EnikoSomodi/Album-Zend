<?php
namespace Album\Form;

use Zend\Form\Form;

/**
 * Song Form.
 *
 * @see Form
 */
class SongForm extends Form
{
    /**
      * Constructor.
      *
      * @param String $name
      */
    public function __construct($name = null)
    {
        parent::__construct('song');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);

        $this->add([
            'name' => 'album_id',
            'type' => 'hidden',
        ]);

        $this->add([
            'name'    => 'song_title',
            'type'    => 'text',
            'options' => [
                'label' => 'Song',
            ],
        ]);

        $this->add([
            'name' => 'songpath',
            'type' => 'File',
            'options' => array ('label' => 'Song sample',),
        ]);

        $this->add([
            'name'       => 'submitbtn',
            'type'       => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}
?>
