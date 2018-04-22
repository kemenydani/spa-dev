<?php

namespace models;

use \core\Model as Model;
use \models\SquadMember as SquadMember;

class Squad extends Model
{
	const IMAGE_PATH = __UPLOADS__ . '/images/squad';
    const NO_LOGO_IMAGE = 'no-logo-dark.jpg';
    const NO_HOME_WALLPAPER = 'no-squad-wallpaper.jpg';

	public static $PKEY = 'id';
	public static $TABLE = 'squad';
	
	public static $COLUMNS = [
		'id',
		'name',
		'game_id',
		'header_image',
        'home_wallpaper',
        'logo',
		'position',
	];

	public function getMembers()
	{
		return SquadMember::findAll($this->getId(), 'squad_id');
	}

    public function getGame()
    {
        return Category::getGame($this->getGameId());
    }

	public function getMember($member_id)
	{
		return SquadMember::getOne([
			'squad_id' => $this->getId(),
			'id' => $member_id
		]);
	}

	public function getId()
	{
		return $this->getProperty('id');
	}

    public function getName()
    {
        return $this->getProperty('name');
    }

    public function getGameId()
    {
        return $this->getProperty('game_id');
    }

    public function getHeaderImage()
    {
        return $this->getProperty('header_image');
    }

    public function formatHeaderImage()
    {
        return self::IMAGE_PATH . DIRECTORY_SEPARATOR . $this->getHeaderImage();
    }

    public function getHomeWallpaper()
    {
        return $this->getProperty('home_wallpaper');
    }

    public function pathHomeWallpaper()
    {
        return self::IMAGE_PATH . DIRECTORY_SEPARATOR . $this->getHomeWallpaper();
    }

    public function requestHomeWallpaper()
    {
        return '/squad_home_wallpaper/' . $this->getHomeWallpaper();
    }
	
	public function requestHeaderImage()
	{
		return '/squad_header_image/' . $this->getHeaderImage();
	}
    
    public function getLogo()
    {
        return $this->getProperty('logo');
    }

    public function formatLogo()
    {
        return self::IMAGE_PATH . DIRECTORY_SEPARATOR . $this->getLogo();
    }

    public function requestLogo()
    {
        return '/squad_logo/' . $this->getLogo();
    }

    public function getPosition()
    {
        return $this->getProperty('position');
    }
}
