<?php
namespace App\Helper;
trait NormalizeOptions
{
    public function normalizeOptions($options): string {
        if (is_string($options)) {
            $options = json_decode($options, true);
        }
        ksort($options);
        return json_encode($options);
    }
}
