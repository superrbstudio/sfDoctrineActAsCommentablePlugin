<?php

/**
 * PluginComment tests.
 */
include dirname(__FILE__).'/../../../bootstrap/unit.php';
$databaseManager = new sfDatabaseManager($configuration);

$t = new lime_test(0);

$model = "model";

$object = new $model();
$object->getCommentsCount();
$object->initializeCommentsHolder();
$object->getCommentsCount();