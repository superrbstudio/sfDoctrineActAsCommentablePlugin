<?php

class CommentableToolkit
{
  protected static $userConfiguration = array(
    'layout' => array()
  );
  
  protected static $anonymousConfiguration = array(
    'layout' => array()
  );
  
  public static function gravatarTag($email, $gravatar_size='80', $gravatar_rating='G')
  {
    $md5email = md5($email);
    $baseURL = "http://www.gravatar.com/avatar/$md5email?s=$gravatar_size";
    return "<img src='$baseURL'>";
  }
  
  public static function getConfig($commentable_model)
  {
    return sfConfig::get("app_aComment_$commentable_model", array());
  }
  
  public static function recHierarchy($collection, $lvl=0)
  {
    $trees = array();
    $flat = $collection;
    foreach($flat as $item)
    {
      $items [$item['root_id']] [$item['level']] [] = $item;
    }
    foreach($flat as $item)
    {
      $item['__children'] = array();
      if(isset($items [$item['root_id']] [$item['level']+1]))
      {
        foreach($items [$item['root_id']] [$item['level']+1] as $child)
        {
          if($item['lft'] < $child['lft'] && $item['rgt'] > $child['rgt'])
          {
            $item['__children'][] = $child;
          }
        }
      }
      if($item['level'] == $lvl)
      {
        $trees[] = $item;
      }
    }
    return $trees;
  }
}
