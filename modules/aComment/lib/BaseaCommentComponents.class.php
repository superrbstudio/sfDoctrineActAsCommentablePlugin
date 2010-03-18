<?php

class BaseaCommentComponents extends sfComponents
{
  public function executeComments()
  {
    $this->maxDepth = sfConfig::get("app_sfDoctrineActAsCommentable_".get_class($this->object), 1);
    $q = Doctrine::getTable('Comment')->createQuery()->addOrderBy('created_at DESC');
    $this->comments = $this->object->getCommentsAsTree($q);
  }
  
  public function executeCommentForm()
  {
    $this->form = new CommentPostForm(get_class($this->object), $this->object->getId());
  }
}
