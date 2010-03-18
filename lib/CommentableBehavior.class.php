<?php 
class Commentable extends Doctrine_Template
{
  public function setTableDefinition()
  {
    if($this->getOption('with_count', 'false'))
    {
      $this->hasColumn("comment_count", 'integer');
    }
    $this->addListener(new CommentableListener());
    $this->_table->unshiftFilter(new CommentableFilter());
  }

  public function initializeCommentsHolder()
  {
    if ($this->getInvoker()->state() === Doctrine_Record::STATE_TCLEAN)
    {
      $this->getInvoker()->mapValue('_comments', new Doctrine_Collection("Comment"));
    }
    else
    {
      $this->getInvoker()->mapValue('_comments', $this->retrieveComments());
    }
  }
  
  public function retrieveComments()
  {    
    return Doctrine::getTable("Comment")->retrieveCommentsForObject($this->getInvoker());
  }
  
  public function getCommentsCount()
  {
    return Doctrine::getTable("Comment")->retrieveCommentCountForObject($this->getInvoker());
  }
  
  public function getCommentsAsTree(Doctrine_Query $query = null)
  {
    $comments = Doctrine::getTable("Comment")->retrieveCommentsForObject($this->getInvoker(), $query, Doctrine::HYDRATE_ARRAY);
    return CommentableToolkit::recHierarchy($comments);
  }
  
  
  
}