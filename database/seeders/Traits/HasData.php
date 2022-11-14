<?php

namespace Database\Seeders\Traits;

use Symfony\Component\Yaml\Yaml;

trait HasData
{
    public function getData(string $filename): array
    {
        $path = database_path('data/' . $filename . '.yaml');

        $data = Yaml::parseFile($path);

        return $data;
    }
}
