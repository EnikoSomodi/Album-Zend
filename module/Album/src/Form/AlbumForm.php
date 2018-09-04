<?php
namespace Album\Form;

use Zend\Form\Form;

/**
 * Album Form.
 *
 * @see Form
 */
class AlbumForm extends Form
{
    /**
      * Constructor.
      *
      * @param String $name
      */
    public function __construct($name = null)
    {
        parent::__construct('album');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);

        $this->add([
            'name'    => 'title',
            'type'    => 'text',
            'options' => [
                'label' => 'Title',
            ],
        ]);

        $this->add([
            'name'    => 'artist',
            'type'    => 'text',
            'options' => [
                'label' => 'Artist',
            ],
        ]);

        $this->add(array(
            'name'    => 'imagepath',
            'type'    => 'File',
            'options' => array ('label' => 'Picture',),
        ));

        $this->add([
            'name'       => 'submit',
            'type'       => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}
?>
