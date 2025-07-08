<?php
/**
 * Root redirect for VMS eProc System
 * Redirects users to the unified pengadaan application
 * (Previously redirected to main - now consolidated into pengadaan)
 */

// Redirect to unified pengadaan application
header('Location: /pengadaan/');
exit();
?> 