<?php

namespace App\Enums;

enum VisitStatus: string
{
    case Archival = 'Архивный';
    case NotCame = 'Не пришёл';
    case Expected = 'Ожидается';
    case Came = 'Пришёл';
    case Finished = 'Окончен';
    case Canceled = 'Отменён';
}
