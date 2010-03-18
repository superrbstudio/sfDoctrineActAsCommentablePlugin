<?php use_helper('jQuery') ?>
<?php echo jq_form_remote_tag(array(
    'url' => '@comment_reply?id='.$parentComment['id'],
    'update' => 'comment-reply-'. $parentComment['id']
    )); ?>
  <?php include_partial('aComment/formBody', array('form' => $form)) ?>
  <input type="submit">
</form>