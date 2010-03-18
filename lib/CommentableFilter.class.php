<?php

class CommentableFilter extends Doctrine_Record_Filter
{
  
  public function filterGet(Doctrine_Record $record, $name)
  {
    if($name === "Comments")
    {
      if(!isset($record->_comments))
      {
        $record->initializeCommentsHolder();  
      }
      return $record->_comments;
    }
    throw new Doctrine_Record_UnknownPropertyException(sprintf('Unknown record property / related component "%s" on "%s"', $name, get_class($record)));
  }
  
  public function filterSet(Doctrine_Record $record, $name, $value)
  {
    throw new Doctrine_Record_UnknownPropertyException(sprintf('Unknown record property / related component "%s" on "%s"', $name, get_class($record)));
  }
  
}
