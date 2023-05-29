<?php


namespace App\Models\Api;

class Student extends \App\Models\Student
{
    public function getRouteKeyName(): string
    {
        return 'id';
    }
}
