<?php

namespace App\Core\Domain;

use DateTime;

class PersonalRecord
{

    public int $id = null;

    public int $user_id = null;

    public int $movement_id = null;

    public float $value = null;

    public DateTime $date = null;

}