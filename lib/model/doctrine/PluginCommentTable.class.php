<?php
/**
 */
class PluginCommentTable extends Doctrine_Table
{
  /**
   * 
   * @return An array of models that have comments linked to them
   */
  public function getModels()
  {
    $q = $this->createQuery('c')
      ->select('c.commentable_model')
      ->groupBy('c.commentable_model');
      
    $models = $q->execute(array(), Doctrine::HYDRATE_NONE);
    foreach($models as $model)
    {
      $flatModels[$model[0]] = $model[0];
    }
    return $flatModels;
  }
  
  /**
   * Retrieves a collection of all comments attached to this model
   * @param object         $commentableModel
   * @param Doctrine_Query $q [optional]
   * @param object         $hydrateMode [optional]
   * @return 
   */   
  public function retrieveCommentsForModel($commentableModel, Doctrine_Query $q = null, $hydrateMode = Doctrine::HYDRATE_RECORD)
  {
    if(is_null($q)) $q = $this->createQuery('c');
    $rootAlias = $q->getRootAlias();
    $q->addWhere($rootAlias.".commentable_model = ?", $commentableModel);

    return $q->execute(array(), $hydrateMode);
  }
  
  /**
   * Retrieves a collection of allcomments attached to an object
   * @param object         $object
   * @param Doctrine_Query $q [optional]
   * @param object         $hydrateMode [optional]
   * @return 
   */
  public function retrieveCommentsForObject($object, Doctrine_Query $q = null, $hydrateMode = Doctrine::HYDRATE_RECORD)
  {
    if(is_null($q)) $q = $this->createQuery('c');
    $rootAlias = $q->getRootAlias();
    $q->addWhere($rootAlias.".commentable_id = ?", $object['id']);
    
    return $this->retrieveCommentsForModel(get_class($object), $q, $hydrateMode);
  }
  
  /**
   * Retrieves the number of comments for an object
   * @param object         $object
   * @param Doctrine_Query $q [optional]
   * @return 
   */
  public function retrieveCommentCountForObject($object, Doctrine_Query $q = null)
  {
    if(is_null($q)) $q = $this->createQuery('c');
    $rootAlias = $q->getRootAlias();
    $q->select("count(*) as comment_count")
      ->groupBy("commentable_model, commentable_id");
    $result = $this->retrieveCommentsForObject($object, $q);
  }
  
}