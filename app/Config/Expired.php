<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Expired extends BaseConfig
{
    /**
     * Thresholds for determining notification priorities (in days).
     */
    public $criticalDays = 0;   // Less than 0 days (Expired)
    public $highDays     = 30;  // 0 to 30 days
    public $warningDays  = 90;  // 31 to 90 days

    /**
     * Specific reminder milestones (in days).
     */
    public $reminderDays = [90, 60, 30, 14, 7, 3, 1, 0];
}
