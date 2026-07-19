<?php
require 'public/index.php';
$authorize = service('authorization');
$authenticate = service('authentication');
echo "User ID: " . $authenticate->id() . "\n";
print_r($authorize->getGroupsForUser($authenticate->id()));
