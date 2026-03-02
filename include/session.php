<?php
// include/session.php

if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_path' => '/',   // 🔑 IMPORTANT FIX
    ]);
}
