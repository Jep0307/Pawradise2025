<?php
session_start();

if (isset($_SESSION['admin_email'])) {
  header("Location: tabs/dashboard.php");
  exit();
} else {
  header("Location: auth/signin.html");
  exit();
}