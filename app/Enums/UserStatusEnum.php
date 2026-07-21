<?php
namespace App\Enums;
enum UserStatusEnum: string
{
    const ACTIVE = "active";
    const INACTIVE  = "inactive";
    const BLOCKED = "blocked";
}
