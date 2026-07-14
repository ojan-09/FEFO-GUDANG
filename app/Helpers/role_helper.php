<?php

if (! function_exists('get_user_role')) {
    /**
     * Get the single source of truth role for the currently logged in user.
     * Includes static caching to avoid multiple database queries per request.
     *
     * @return string
     */
    function get_user_role(): string
    {
        static $role = null;

        if ($role !== null) {
            return $role;
        }

        if (! logged_in()) {
            $role = 'Guest';
            return $role;
        }

        $userId = user_id();
        $groupModel = new \Myth\Auth\Models\GroupModel();
        
        $groups = $groupModel->getGroupsForUser($userId);
        
        if (! empty($groups)) {
            // Assume single source of truth - get the first group
            $firstGroup = reset($groups);
            $role = $firstGroup['name'] ?? 'Unknown';
        } else {
            $role = 'No Role';
        }

        return $role;
    }
}
