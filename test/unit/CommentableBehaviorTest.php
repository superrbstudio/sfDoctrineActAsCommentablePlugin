<?php

/**
 * CommentableBehavior tests.
 */
include dirname(__FILE__).'/../bootstrap/unit.php';

$databaseManager = new sfDatabaseManager($configuration);

$t = new lime_test(13);

$model = "model";

$object = new $model();
$object->name = "Model A";

$comment = new Comment();
$comment['title'] = "This is a test comment.";
$object->Comments[] = $comment;
$object->save();

$t->comment("---CommentableFilter--");
/*
 * Tests for magic methods for CommentableFilter getters
 */
$t->comment("Testing magic getters for new objects");
$t->is($comment->isNew(), false, '$object->Comments[] method for saving comments on new objects');
$t->is($comment['commentable_model'], get_class($object), '$object->Comments[] sets proper commentable_model on save');
$t->is($comment['commentable_id'], $object['id'], '$object->Comments[] sets proper commentable_id on save');


$t->comment("Testing magic getters for new objects");
$comment2 = new Comment();
$comment2['title'] = "This is a second test comment.";
$object->Comments[] = $comment2;
$object->save();

$t->is($comment2->isNew(), false, '$object->Comments[] method for saving comments on new objects');
$t->is($comment2['commentable_model'], get_class($object), '$object->Comments[] sets proper commentable_model on save');
$t->is($comment2['commentable_id'], $object['id'], '$object->Comments[] sets proper commentable_id on save');


$t->comment('Testing magic methods with existing object from database');

$object_id = $object['id'];
$object = Doctrine::getTable($model)->findOneBy("id", $object_id);
$t->is(count($object->Comments), 2, "->Comments retrieves all Comments for object");
$object->Comments[]->title = "Test comment 3.";
$object->Comments[]->title = "Test comment 4.";
$object->Comments[]->title = "Test comment 5.";
$object->save();
$t->is(count($object->Comments), 5, "->Comments[]->title adds comments to existing object");
try{
  $object->Cwefr = "fwef";
}
catch(Doctrine_Record_UnknownPropertyException $e){
  $t->pass('->filterGet() throws error for unknown property');
}
try{
  $object->setComments(null);
}
catch(Doctrine_Record_UnknownPropertyException $e){
  $t->pass('->filterSet() throws error for unknown property');
}


/*
 * Tests for comment threading capabilities
 */
$t->comment("----Comment Threading Tests");
$comment_c1 = new Comment();
$comment_c1->title = "Comment 1 child 1";
$comment->addReply($comment_c1);
$comment->save();

$object = Doctrine::getTable($model)->findOneBy("id", $object_id);
$commentTree = $object->getCommentsAsTree();
$t->is(count($commentTree[0]['__children']), 1, '$comment->addReply adds comment reply');


$models = Doctrine::getTable("Comment")->getModels();
$t->is(count($models), 1, "CommentTable::getModels");

$t->comment('PluginCommentableListener::postDelete');
$object->delete();
$comments = Doctrine::getTable("Comment")->createQuery()
  ->where("commentable_id = ?", $object_id)
  ->execute();
$t->is(count($comments), 0, "All comments are deleted when object is deleted.");

