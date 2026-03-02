<?php
// Load the engine
require 'include/load.php';

// Destroy all session data
session_unset();
session_destroy();

// Redirect to login page
redirect('sign-in.php');
