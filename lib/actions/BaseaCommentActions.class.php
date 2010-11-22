<?php

class BaseaCommentActions extends sfActions
{
  public function executeComment(sfRequest $request)
  {
    //Check if we have a valid object to attach a comment to
    $this->forward404Unless($commentable_object = $this->getObjectFromRequest($request));
    $this->options = sfConfig::get("sfDoctrineActAsCommentable_models_".get_class($commentable_object));
    
    $form = new CommentPostForm(array(),array(
      'commentable_model' => $request->getParameter('commentable_model'),
      'commentable_id' => $request->getParameter('commentable_id')
    ));
    if($request->isMethod('POST'))
    { 
      $form->bind($request->getParameter('comment'));
      if($form->isValid())
      {
        $form->save();
        $treeObject = Doctrine::getTable('Comment')->getTree();
        $treeObject->createRoot($form->getObject());
        return $this->renderPartial("aComment/comment", array("comment" => $form->getObject()));
      }
    }
    $this->form = $form;
    return $this->renderPartial("aComment/form", array("form" => $form, "object" => $form->getObject()));
  }
    
  public function getObjectFromRequest(sfRequest $request)
  {
    $commentable_model  = $request->getParameter('commentable_model', false);
    $commentable_id     = $request->getParameter('commentable_id', false);
    return Doctrine::getTable($commentable_model)->findOneBy('id', $commentable_id);
  }
  
  public function executeReply(sfRequest $request)
  {
    $this->forward404Unless($parentComment = $this->getRoute()->getObject());
    $form = new CommentPostForm(array(),array(
      'commentable_model' => $parentComment['commentable_model'],
      'commentable_id' => $parentComment['commentable_id'],
      'namespace' => $parentComment['namespace']
    ));
    if($request->isMethod('POST'))
    {
      $form->bind($request->getParameter('comment'), $request->getFiles('comment'));
      if($form->isValid())
      {
        $form->save();
        $form->getObject()->getNode()->insertAsLastChildOf($parentComment);
        return $this->renderPartial('aComment/comment', array('comment' => $form->getObject()));
      }
    }
    return $this->renderPartial('aComment/replyForm', array('form' => $form, 'parentComment' => $parentComment));
  }
}
