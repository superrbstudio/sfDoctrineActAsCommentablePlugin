<?php

/**
 * CommentPost form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CommentPostForm extends PluginCommentForm
{
  //Comments must be linked to a model and a id
  public function __construct($commentable_model, $commentable_id)
  {
    $this->commentable_model = $commentable_model;
    $this->commentable_id    = $commentable_id;
    
    $this->config = CommentableToolkit::getConfig($commentable_model);
    
    parent::__construct();
  }

  public function setup()
  {
    
    $userConfig = $this->config;
    $layout = $userConfig['layout'];
    $widgets['text'] = new sfWidgetFormTextarea(array(), array('rows' => 5, 'cols' => 40));
    $validators['text'] = new sfValidatorString(array('required' => true));

    if(isset($layout['name']))
    {
      $widgets['name'] = new sfWidgetFormInput();
      $validators['name'] = new sfValidatorString(
        array(
          'required' => ($layout['name'] == 'required')
        ),
        array()
      );
    }
    
    if(isset($layout['email']))
    {
      $widgets['email'] = new sfWidgetFormInput();
      $validators['email'] = new sfValidatorEmail(
        array(
          'max_length' => 50,
          'required' => ($layout['email'] == 'required')
        ),
        array(
          'max_length' => "Your email is too long.",
          'required' => "Your email is required."
        )
      );
    }
    
    if(isset($layout['website']))
    {
      $widgets['website'] = new sfWidgetFormInput();
      $validators['website'] = new sfValidatorUrl(
        array(
          'max_length' => 50,
          'required' => ($layout['name'] == 'required')
        ),
        array(
          'max_length' => "Your website address is too long.",
          'required' => "Your website address is required."
        )
      );
    }

    if (isset($layout['title']))
    {
      $widgets['title'] = new sfWidgetFormInput();
      $validators['title'] = new sfValidatorString(
        array(
          'max_length' => 100,
          'required'   => ($layout['title'] == 'required')
        ),
        array(
          'max_length' => 'The title is too long. It must be of %max_length% characters maximum.',
          'required' => 'The title is required.',
        )
      );
    }
    
    $this->setWidgets($widgets);
    $this->setValidators($validators);
    
    $this->useFields(array_keys($widgets));

    $this->widgetSchema->setNameFormat('comment[%s]');
    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
  }
  
  public function save($con = null)
  {
    if(sfContext::getInstance()->getUser()->isAuthenticated())
    {
      $this->getObject()->setUserId(sfContext::getInstance()->getUser()->getGuardUser()->getId());
      $this->getObject()->setName(sfContext::getInstance()->getUser()->getGuardUser()->getUsername());
    }
    
    $this->getObject()->setCommentableModel($this->commentable_model);
    $this->getObject()->setCommentableId($this->commentable_id);
    
    return parent::save($con);
  }

}
