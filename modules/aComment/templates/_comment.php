<?php use_helper("jQuery") ?>
<div class="comment" id="comment-<?php echo $comment['id']?>">
<?php if(sfConfig::get("app_sfDoctrineActAsCommentable_gravatar", false)): ?>
<?php echo CommentableToolkit::gravatarTag($comment['email'], 50) ?>
<?php endif ?>
<h4><?php echo $comment['title'] ?> posted by <u><?php echo $comment['name'] ?></u></h4>
<h3><?php echo $comment['text'] ?></h3>

<?php if(isset($comment['__children']) && count($comment['__children']) > 0 && $comment['level'] < $maxDepth): ?>
  <div class="child-comments" id="comment<?php echo $comment['id']?>-children" style="margin-left:50px">
  <?php foreach($comment['__children'] as $child): ?>
    <?php include_partial('aComment/comment', array('comment' => $child, 'maxDepth' => $maxDepth)) ?>
  <?php endforeach ?>
  <div id="comment-reply-<?php echo $comment['id'] ?>">
  <?php echo jq_link_to_remote("Reply", array(
    'url' => '@comment_reply?id='.$comment['id'],
    'update' => 'comment-reply-'. $comment['id'],
    'method' => "GET"
    )); ?>
  </div>
  </div>
<?php else: ?>
  <?php if($comment['level'] == 0): ?>
    <div class="child-comments" id="comment-<?php echo $comment['id']?>-children" style="margin-left:50px">
    <div id="comment-reply-<?php echo $comment['id'] ?>">
      
    </div>
    
    </div>
    <?php echo jq_link_to_remote("Reply", array(
        'url' => '@comment_reply?id='.$comment['id'],
        'update' => 'comment-reply-'. $comment['id'],
        'method' => "GET"
        )); ?>
  <?php endif ?>
<?php endif ?>
</div>