<?php use_helper('jQuery') ?>
<?php echo jq_form_remote_tag(array(
    'url' => 'aComment/comment?commentable_id='.$object['id'].'&commentable_model='.get_class($object),
    'update' => get_class($object).'-'.$object['id']."comment-form",
    ));  ?>
    <?php //echo form_tag('aComment/comment?commentable_id='.$pk_blog_post['id'].'&commentable_model='.get_class($pk_blog_post)) ?>
  <?php include_partial('aComment/formBody', array('form' => $form)) ?>
<input type="submit">
</form>