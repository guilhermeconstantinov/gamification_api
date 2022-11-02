<?php


namespace App;


abstract class StatusType
{
    const REGISTERED = 0;
    const VALIDATION_CODE = 1;
    const WAITING_CHECKIN = 2;
    const CHECKIN = 3;
    const WAITING_SIMULATION = 4;
    const SIMULATION = 5;
    const SENT_NUMBER = 6;
    const YOU_WON = 7;
    const WITHDREW_GIFT = 8;
}
