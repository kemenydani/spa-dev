<?php

namespace controllers\api;

use models\SquadMember;

class SquadMemberController extends ModelController
{
    public function __construct()
    {
        parent::__construct( new SquadMember() );
    }

}