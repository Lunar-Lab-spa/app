<?php
use Symfony\Config\TwigConfig;

return static function (TwigConfig $twig): void {
    $twig->global('sections')->value(\App\Controller\HomeController::SECTIONS);
};