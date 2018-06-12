<?php

namespace controllers\api;

use models\Setting;
use models\SettingCollection;
use Slim\Http\Request;
use Slim\Http\Response;
use core\DB as DB;

class SettingController
{

    public function getFetchSettings(Request $request, Response $response, $args)
    {
        $Settings = Setting::getAll();

        $settingArray = [];

        if($Settings) $settingArray = (new SettingCollection($Settings))->getProperties();

        return $response->withStatus(200)->withJson($settingArray);
    }

    public function postUpdateSetting(Request $request, Response $response, $args)
    {
        $formData = $request->getParsedBodyParam('data');

        /* @var \models\Setting $Setting */
        $Setting = null;

        $props = [];

        if(array_key_exists('codename', $formData)) $Setting = Setting::find($formData['codename'], 'codename');

        if($Setting && array_key_exists('val', $formData))
        {
            $Setting->setProperty('val', $formData['val']);
            $Setting->save();
            $props = $Setting->getProperties();
        }

        // TODO:: try to update here

        return $response->withStatus(200)->withJson($props);
    }

}