<?php

namespace Tripteki\User\Contracts\Repository\Admin;

use Tripteki\Repository\Contracts\Allable;
use Tripteki\Repository\Contracts\Getable;
use Tripteki\Repository\Contracts\Createable;
use Tripteki\Repository\Contracts\Updateable;
use Tripteki\Repository\Contracts\Deleteable;

interface IUserRepository extends Allable, Getable, Createable, Updateable, Deleteable
{
    //
};
