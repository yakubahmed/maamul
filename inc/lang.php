<?php
// Simple i18n helper. Default language: Somali ('so').
if (!isset($_SESSION)) { session_start(); }

// Current language from session or default
if (empty($_SESSION['lang'])) {
    $_SESSION['lang'] = 'so';
}

$LANG = $_SESSION['lang'];

// Translation map (extend as needed)
$TRANSLATIONS = [
  'so' => [
    // Common UI
    'Updates' => 'Cusbooneysiin',
    'Check for updates' => 'Hubi cusbooneysiinta',
    'Update now' => 'Cusbooneysii hadda',
    'Profile' => 'Astaan',
    'Logout' => 'Ka bax',
    'Help Center' => 'Xarunta Caawinta',
    'No updates available. You are up to date.' => 'Cusbooneysiin ma jiraan. Wax walba waa cusub yihiin.',
    'An update is available. Click "Update now" to install.' => 'Cusbooneysiin ayaa diyaar ah. Guji "Cusbooneysii hadda" si aad u rakibto.',
    'No internet connection. Please try again.' => 'Isku xirka internetka ma jiro. Fadlan isku day mar kale.',
    // POS
    'POS' => 'POS',
    'Sale' => 'Iib',
    'Item category' => 'Nooca alaabta',
    'Search product' => 'Raadi alaab',
    'Barcode Scanner' => 'Baar-koodh',
    'Item' => 'Alaab',
    'Unit price' => 'Qiimaha sheyga',
    'Qty' => 'Tiro',
    'Amount' => 'Wadar',
    'Total' => 'Wadarta',
    'Order now' => 'Dalbo hadda',
    'Reset' => 'Tirtir',
    'Add customer' => 'Ku dar macmiil',
    'Customer name' => 'Magaca macmiilka',
    'Phone number' => 'Lambarka taleefanka',
    'Email Address' => 'Cinwaanka iimaylka',
    'Status' => 'Xaalad',
    'Select status' => 'Dooro xaalad',
    'Active' => 'Active',
    'Disabled' => 'La xiray',
    'Address' => 'Cinwaan',
    'Save customer' => 'Kaydi macmiilka',
    'Close' => 'Xir',
    'Payment Method' => 'Habka Lacag Bixinta',
    'Select Payment Method' => 'Dooro hab lacag bixinta',
    'Order Notes' => 'Faahfaahin',
    'Totals' => 'Wadarta Guud',
    'Subtotal' => 'Wadar Hoosaad',
    'Calculated from items' => 'Waxaa laga xisaabiyay alaabta',
    'Discount (all)' => 'Qiimo dhimis guud',
    'Grand Total' => 'Wadar Guud',
    'Amount Paid' => 'Lacagta la bixiyay',
    'Change' => 'Haraaga',
    'Balance Due' => 'Haraaga ku waajiba',
    'Cancel' => 'Jooji',
    'Complete Order' => 'Dhamee Dalabka',
  ],
  'en' => [
    // Common UI
    'Updates' => 'Updates',
    'Check for updates' => 'Check for updates',
    'Update now' => 'Update now',
    'Profile' => 'Profile',
    'Logout' => 'Logout',
    'Help Center' => 'Help Center',
    'No updates available. You are up to date.' => 'Ma jiro update cusub. waxaad heysata midka ugu dambeeyay.',
    'An update is available. Click "Update now" to install.' => 'An update is available. Click "Update now" to install.',
    'No internet connection. Please try again.' => 'Fadlan isku xiro internetka si aad u hesho update cusub.',
    // POS
    'POS' => 'POS',
    'Sale' => 'Sale',
    'Item category' => 'Item category',
    'Search product' => 'Search product',
    'Barcode Scanner' => 'Barcode Scanner',
    'Item' => 'Item',
    'Unit price' => 'Unit price',
    'Qty' => 'Qty',
    'Amount' => 'Amount',
    'Total' => 'Total',
    'Order now' => 'Order now',
    'Reset' => 'Reset',
    'Add customer' => 'Add customer',
    'Customer name' => 'Customer name',
    'Phone number' => 'Phone number',
    'Email Address' => 'Email Address',
    'Status' => 'Status',
    'Select status' => 'Select status',
    'Active' => 'Active',
    'Disabled' => 'Disabled',
    'Address' => 'Address',
    'Save customer' => 'Save customer',
    'Close' => 'Close',
    'Payment Method' => 'Payment Method',
    'Select Payment Method' => 'Select Payment Method',
    'Order Notes' => 'Order Notes',
    'Totals' => 'Totals',
    'Subtotal' => 'Subtotal',
    'Calculated from items' => 'Calculated from items',
    'Discount (all)' => 'Discount (all)',
    'Grand Total' => 'Grand Total',
    'Amount Paid' => 'Amount Paid',
    'Change' => 'Change',
    'Balance Due' => 'Balance Due',
    'Cancel' => 'Cancel',
    'Complete Order' => 'Complete Order',
  ],
];

function __t($key) {
  global $TRANSLATIONS, $LANG;
  if (isset($TRANSLATIONS[$LANG][$key])) return $TRANSLATIONS[$LANG][$key];
  // Fallback to English
  if (isset($TRANSLATIONS['en'][$key])) return $TRANSLATIONS['en'][$key];
  return $key;
}

?>
