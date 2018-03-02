<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 02.03.2018
 * Time: 22:20
 */

namespace app\modules\v1\controllers;


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