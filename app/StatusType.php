<?php


namespace App;


abstract class StatusType
{
    const REGISTERED = 0;
    const WAITING_CHECKIN = 1;
    const CHECKIN = 2;
    const WAITING_SIMULATION = 3;
    const SIMULATION = 4;
    CONST SENT_NUMBER = 5;
    CONST YOU_WON = 6;
    CONST WITHDREW_GIFT = 7;
}
