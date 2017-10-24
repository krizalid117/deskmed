<?php

namespace App\Http\Controllers;

use Symfony\Component\Console\Output\ConsoleOutput;

class GlobalController {

    public static function log($msj) {
        $output = new ConsoleOutput();
        $output->writeln("<info>$msj</info>");
    }

}