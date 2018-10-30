<?php

namespace App\Services;

class AdminService
{
    /**
     * AdminService constructor.
     */
    public function __construct()
    {
    }

    /**
     * check permission for show or hide link
     *
     * @param  string $role
     * @param  $link
     * @return boolean
     */
    public static function checkPermission($role, $link)
    {
        if (in_array($role, ['system', 'admin'])) {
            return $link;
        } else {
            return '';
        }
    }
}
