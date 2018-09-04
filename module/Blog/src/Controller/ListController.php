<?php
namespace Blog\Controller;

// Out-sourced Libraries
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use InvalidArgumentException;

// My Libraries
use Blog\Model\PostRepositoryInterface;

class ListController extends AbstractActionController
{
  private $postRepository;

  public function __construct(PostRepositoryInterface $postRepository)
  {
      $this->postRepository = $postRepository;
  }

  public function indexAction()
  {
      return new ViewModel([
          'posts' => $this->postRepository->findAllPosts(),
      ]);

      // Same as: return ['post' => $this->postRepository->findAllPosts()];
  }

  public function detailAction()
  {
      $id = $this->params()->fromRoute('id');

      try {
          $post = $this->postRepository->findPost($id);
      } catch (\InvalidArgumentException $ex) {
          return $this->redirect()->toRoute('blog');
      }

      return new ViewModel([
          'post' => $post,
      ]);
  }
}
?>
