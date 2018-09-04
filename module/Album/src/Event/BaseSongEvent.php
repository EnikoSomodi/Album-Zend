<?php
namespace Album\Event;

use Zend\EventManager\Event;

/**
 * Base Song Event.
 *
 * @abstract
 * @see Event
 */
abstract class BaseSongEvent extends Event
{
    /**
     * @var Song
     */
    protected $song;

    /**
      * Constructor.
      *
      * @param  String  $name
      * @param  String  $target
      * @param  String  $params
      */
    public function __construct($name = null, $target = null, $params = null)
    {
        parent::__construct($name, $target, $params);

        $this->setName($this->getEventName());
        $this->song = $this->getParam('song');
    }

    abstract public function getEventName();
}
?>
