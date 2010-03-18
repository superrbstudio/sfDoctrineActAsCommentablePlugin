<?php


class CommentableListener extends Doctrine_Record_Listener
{
  public function postSave(Doctrine_Event $event)
  {
    $object = $event->getInvoker();
    foreach($object->Comments as $comment)
    {
      if($comment->isNew())
      {
        $comment->setCommentableModel(get_class($object));
        $comment->setCommentableId($object->getId());
        $comment->save();
        $treeObject = Doctrine::getTable('Comment')->getTree();
        $treeObject->createRoot($comment);
      }
    }
    //$object->Comments->save();
  }
	  
	public function preDelete(Doctrine_Event $event)
	{
		 $object = $event->getInvoker();
		 Doctrine::getTable('Comment')->createQuery()
          ->delete()
          ->addWhere('commentable_id = ?', $object->id)
          ->addWhere('commentable_model = ?', get_class($object))
          ->execute();
	}
	
}