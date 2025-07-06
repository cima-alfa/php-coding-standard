<?php

namespace CimaAlfaCSFixers;

enum PCRE: string
{
    case Replace = 'preg_replace';
    case ReplaceCallback = 'preg_replace_callback';
}
