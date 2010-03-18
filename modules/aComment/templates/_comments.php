<?php foreach($comments as $comment): ?>
  <?php include_partial('aComment/comment', array('comment' => $comment, 'maxDepth' => $maxDepth)) ?>
<?php endforeach ?>