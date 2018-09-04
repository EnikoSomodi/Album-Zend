<?php
namespace Album\Controller;

use Zend\EventManager\EventManager;
use Zend\Mvc\Controller\AbstractActionController;

use Album\Model\Song;
use Album\Event\SongCreatedEvent;
use Album\Event\SongDeletedEvent;
use Album\Form\SongForm;
use Album\Model\SongTable;
use Album\Service\SongService;

/**
 * Song Controller.
 *
 * @see AbstractActionController
 */
class SongController extends AbstractActionController
{
    private $songTable;

    /**
      * Constructor.
      *
      * @param  SongTable    $songTable
      * @param  EventManager $eventManager
      * @param  SongService  $songService
      */
    public function __construct(
        SongTable $songTable,
        EventManager $eventManager,
        SongService $songService
    ) {
        $this->songTable        = $songTable;
        $this->eventManager     = $eventManager;
        $this->songService      = $songService;
    }

    /**
      * Add Song Action.
      *
      * @return Form
      */
    public function addsongAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('album', ['action' => 'add']);
        }

        $form = new SongForm();
        $form->get('submitbtn')->setValue('Add song');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return['form' => $form];
        }

        $song = new Song();

        $form->setInputFilter($song->getInputFilter());

        $post = array_merge_recursive(
            $request->getPost()->toArray(),
            $request->getFiles()->toArray()
        );

        $form->setData($post);

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $song->exchangeArray($form->getData());

        $song->album_id = $id;

        $this->songTable->saveSong($song);

        $this->eventManager->triggerEvent(new SongCreatedEvent(
            null,
            $this,
            ['song' => $song]
        ));
        return $this->redirect()->toRoute('album',
            ['action' => 'detail', 'id' => $id]);
    }

    /**
      * Edit Song Action.
      *
      * @return
      */
    public function editsongAction()
    {
        $id     = (int) $this->params()->fromRoute('id', 0);
        $songId = (int) $this->params()->fromRoute('song_id', 0);

        if (0 === $songId) {
            throw new RuntimeException(sprintf(
                'Could not find song with identifier %d',
                $songId
            ));
        }

        try {
            $song = $this->songTable->fetchSongById($songId);
        } catch (\Exception $exception) {
            return $this->redirect()->toRoute('album', ['action' => 'index']);
        }

        $form = new SongForm();
        $form->bind($song);
        $form->get('submitbtn')->setAttribute('value', 'Edit song');

        $request = $this->getRequest();

        $viewData = ['id' => $id, 'song_id' => $songId, 'form' => $form];

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($song->getInputFilter());

        if ($request->getPost() && $request->getFiles()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
              $form->setData($post);
        } else {
            if (!$request->getFiles()) {
                $form->setData($request->getPost());
            } else {
                $form->setData($request->getFiles());
            }
        }

        if (!$form->isValid()) {
            return $viewData;
        }

        $this->songTable->saveSong($song);

        return $this->redirect()->toRoute('album', [
            'action' => 'detail',
            'id'     => $id
        ]);
    }

    /**
      * Delete Song Action.
      *
      * @return
      */
    public function deletesongAction()
    {
        $id     = (int) $this->params()->fromRoute('id', 0);
        $songId = (int) $this->params()->fromRoute('song_id', 0);

        $song = $this->songTable->fetchSongById($songId);

        if (0 === $songId) {
            throw new RuntimeException(sprintf(
                'Could not find song with identifier %d',
                $songId
            ));
        }

        $request = $this->getRequest();

        if($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $songId = (int) $request->getPost('id');
                $this->songTable->deleteSong($songId);
                $this->eventManager->triggerEvent(new SongDeletedEvent(
                    null,
                    $this,
                    ['song' => $song]
                ));
            }
            return $this->redirect()->toRoute('album',
                ['action' => 'detail', 'id' => $id]);
        }

        return [
            'song'  => $this->songTable->fetchSongById($songId),
            'album' => $this->songService->getAlbumOfSong($song),
        ];
    }
}
?>
