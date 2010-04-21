<?php

class BaseaCommentComponents extends sfComponents
{
  public function executeComments()
  {
    $this->maxDepth = sfConfig::get("app_sfDoctrineActAsCommentable_".get_class($this->object), 1);
    $q = Doctrine::getTable('Comment')->createQuery()->addOrderBy('created_at DESC');
    if(isset($this->namespace))
      $q->andWhere('namespace = ?', $this->namespace);
    $this->comments = $this->object->getCommentsAsTree($q);
  }
  
  public function executeCommentForm()
  {
    $this->form = new CommentPostForm(array(), array(
      'commentable_model' => get_class($this->object),
      'commentable_id' => $this->object->getId(),
      'namespace' => $this->namespace
    ));
  }
}
