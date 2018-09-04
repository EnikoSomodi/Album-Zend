<?php
namespace Blog\Model;

interface PostCommandInterface
{
    // AIM: operate CRUD commands on DB

    public function insertPost(Post $post);
      // Persist a new post in the system, returning the inserted post with identifier

    public function updatePost(Post $post);
      // Update an existing post in the system and returns the updated post

    public function deletePost(Post $post);
      // Delete a post from the system returning a boolean value depending on the success of the operation
}
?>
