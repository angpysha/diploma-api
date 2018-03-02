<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 28.02.2018
 * Time: 9:44
 */

namespace app\controllers;


interface ISensorController
{
    function actionAdd();
    function actionUpdate($id);
    function actionDelete($id);
    function actionSearch();
    function actionGet($id);
    function actionFirst();
    function actionLast();

}