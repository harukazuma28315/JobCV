<?php

require_once __DIR__ . "/../models/CV.php";

class CVController
{
    public function index()
    {
        $cv = new CV();
        $list = $cv->getAll();

        include __DIR__ . "/../views/cv/index.php";
    }
}