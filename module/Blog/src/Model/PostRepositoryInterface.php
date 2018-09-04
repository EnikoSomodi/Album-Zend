<?php
namespace Blog\Model;

interface PostRepositoryInterface
{
    // AIM: retrieve data from the DB

    public function findAllPosts();
          // Return an array(Post[]) of all the Post instances(entries/blog posts) that we can iterate over

    public function findPost($id);
          // Return a single blog post(Post) identified by $id
}
?>
