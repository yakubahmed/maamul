<?php
// Setup script to add Loans menu and permissions for admin (group_id = 1)
include('inc/config.php');

function ensure_menu($name, $icon='fas fa-hand-holding-usd', $sort=60){
  global $con;
  $q = mysqli_query($con, "SELECT menu_id FROM menu WHERE menu_name='".mysqli_real_escape_string($con,$name)."'");
  if($q && mysqli_num_rows($q)>0){ $r = mysqli_fetch_assoc($q); return (int)$r['menu_id']; }
  mysqli_query($con, "INSERT INTO menu (menu_name, menu_icon, sort_by) VALUES ('$name', '$icon', $sort)");
  return mysqli_insert_id($con);
}

function ensure_submenu($menu_id, $sub_name, $url){
  global $con;
  $q = mysqli_query($con, "SELECT submenu_id FROM submenu WHERE sub_menu_name='".mysqli_real_escape_string($con,$sub_name)."'");
  if($q && mysqli_num_rows($q)>0){ $r = mysqli_fetch_assoc($q); return (int)$r['submenu_id']; }
  mysqli_query($con, "INSERT INTO submenu (menu_id, sub_menu_name, url) VALUES ($menu_id, '".mysqli_real_escape_string($con,$sub_name)."', '".mysqli_real_escape_string($con,$url)."')");
  return mysqli_insert_id($con);
}

function ensure_admin_priv($submenu_id){
  global $con;
  $q = mysqli_query($con, "SELECT prev_id FROM previlage WHERE group_id=1 AND sub_menu_id=$submenu_id");
  if($q && mysqli_num_rows($q)>0) return;
  mysqli_query($con, "INSERT INTO previlage (group_id, sub_menu_id, date, view, add, edit, delete) VALUES (1, $submenu_id, NOW(), 1,1,1,1)");
}

$menu_id = ensure_menu('Loans');
$sub1 = ensure_submenu($menu_id, 'New Money Loan', 'loans/new.php');
$sub2 = ensure_submenu($menu_id, 'Money Loan List', 'loans/list.php');

ensure_admin_priv($sub1);
ensure_admin_priv($sub2);

echo "Loans menu and admin permission configured.";

