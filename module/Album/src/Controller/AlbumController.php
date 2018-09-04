<?php
namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Album\Form\AlbumForm;
use Album\Model\Album;
use Album\Model\AlbumTable;
use Album\Model\SongTable;

/**
 * Album Controller.
 *
 * @see AbstractActionController
 */
class AlbumController extends AbstractActionController
{
    private $table;

    /**
      * Constructor.
      *
      * @param AlbumTable $albumTable
      * @param SongTable  $songTable
      */
    public function __construct(AlbumTable $albumTable, SongTable $songTable)
    {
        $this->albumTable   = $albumTable;
        $this->songTable    = $songTable;
    }

    /**
      * Index Action.
      *
      * @return ViewModel
      */
    public function indexAction()
    {
        $paginator = $this->albumTable->fetchAll(true);

        $page = (int) $this->params()->fromQuery('page', 1);
        $page = ($page < 1) ? 1 : $page;

        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(5);

        return new ViewModel(['paginator' => $paginator]);
    }

    /**
      * Add Action.
      *
      * @return Form
      */
    public function addAction()
    {
        $form = new AlbumForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return['form' => $form];
        }

        $album = new Album();

        $form->setInputFilter($album->getInputFilter());

        $post = array_merge_recursive(
            $request->getPost()->toArray(),
            $request->getFiles()->toArray()
        );

        $form->setData($post);

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $album->exchangeArray($form->getData());

        $this->albumTable->saveAlbum($album);

        return $this->redirect()->toRoute('album');
    }

    /**
      * Edit Action.
      *
      * @return
      */
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('album', ['action' => 'add']);
        }

        try {
            $album = $this->albumTable->getAlbum($id);
        } catch (\Exception $exception) {
            return $this->redirect()->toRoute('album', ['action' => 'index']);
        }

        $img = $album->imagepath;

        $form = new AlbumForm();
        $form->bind($album);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();

        $viewData = ['id' => $id, 'form' => $form];

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($album->getInputFilter());

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

        if (!$album->imagepath) {
            $album->imagepath = $img;
        }

        $this->albumTable->saveAlbum($album);

        return $this->redirect()->toRoute('album', [
              'action' => 'detail',
              'id'     => $id
        ]);
    }

    /**
      * Delete Action.
      *
      * @return
      */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('album');
        }

        $request = $this->getRequest();

        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->albumTable->deleteAlbum($id);

                return $this->redirect()->toRoute('album');
            }

            return $this->redirect()->toRoute('album', [
                  'action' => 'detail',
                  'id'     => $id
            ]);
        }

        return [
            'id'    => $id,
            'album' => $this->albumTable->getAlbum($id),
        ];
    }

    /**
      * Create Action.
      *
      * @return null|void
      */
    public function createAction()
    {
        $albums = $this->albumTable->fetchAll();

        if (count($albums) == 0) {
            return null;
        }

        $filename = uniqid('file_') .'.csv';

        $csvfile = fopen ("./module/Album/files/$filename", "w");

        foreach ($albums as $album) {
            $album->title  = trim($album->title);
            $album->artist = trim($album->artist);

            $row = $album->id .'-'. $album->title .'-'. $album->artist.',';

            fputcsv($csvfile, explode(",", $row));
        }

        fseek($csvfile, 0);
        header('Content-Type: application/csv');
        header("Content-type: text/csv");
        header('Content-Disposition: attachment; filename="'.$filename.'";');
        fpassthru($csvfile);

        fclose($csvfile);
    }
    /**
      * Download CSV file Action.
      *
      * @return ViewModel
      */
    public function downAction()
    {
        $message = 'Downloading CSV file ...';

        return new ViewModel([
            'message' => $message,
        ]);
    }

    /**
      * Detail Action.
      *
      * @return ViewModel|
      */
    public function detailAction()
    {
        $id = (int) $this->params()->fromRoute('id');

        try {
            $album = $this->albumTable->getAlbum($id);
        } catch (\Exception $exception) {
            return $this->redirect()->toRoute('album', ['action' => 'index']);
        }

        $songs = $this->songTable->fetchByAlbum($album);

        return new ViewModel([
            'album' => $album,
            'songs' => $songs
        ]);
    }
}
?>
