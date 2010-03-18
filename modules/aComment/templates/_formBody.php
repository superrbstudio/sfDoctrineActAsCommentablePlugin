<?php echo $form->renderHiddenFields() ?>
<?php echo $form->renderGlobalErrors() ?>
<?php if(isset($form['title'])): ?>
  <p><?php echo $form['title']->renderRow() ?></p>
  <p><?php echo $form['title']->renderError() ?></p>
<?php endif ?>
<?php if(isset($form['name'])): ?>
  <p><?php echo $form['name']->renderRow() ?></p>
<?php endif ?>
<?php if(isset($form['email'])): ?>
  <p><?php echo $form['email']->renderRow() ?></p>
<?php endif ?>
<?php if(isset($form['website'])): ?>
  <p><?php echo $form['website']->renderRow() ?></p>
<?php endif ?>
<p><?php echo $form['text']->renderRow() ?></p>