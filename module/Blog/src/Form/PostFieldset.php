<?php
namespace Blog\Form;

// Out-sourced Libraries
use Zend\Form\Fieldset;
use Zend\Hydrator\Reflection as ReflectionHydrator;

// My Libraries
use Blog\Model\Post;

class PostFieldset extends Fieldset
{
  public function init()
  {
    $this->setHydrator(new ReflectionHydrator());
    $this->setObject(new Post('', ''));

    $this->add([
        'type' => 'hidden',
        'name' => 'id',
    ]);

    $this->add([
        'type' => 'text',
        'name' => 'title',
        'options' => [
            'label' => 'Post Title',
        ],
    ]);

    $this->add([
        'type' => 'textarea',
        'name' => 'text',
        'options' => [
            'label' => 'Post Text',
        ],
    ]);
  }
}
?>
