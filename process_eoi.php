<?php
require_once("settings.php");

// Block direct access if not POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: apply.php");
    exit();
}

// Helper function to sanitize inputs
function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Database connection
$conn = @mysqli_connect($host, $user, $pwd);
if (!$conn) die("<p>Database connection failure.</p>");
@mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $sql_db");
mysqli_select_db($conn, $sql_db);

// Create table if not exists
$create_table_sql = "
CREATE TABLE IF NOT EXISTS eoi (
  eoi_id INT AUTO_INCREMENT PRIMARY KEY,
  job_ref VARCHAR(5) NOT NULL,
  first_name VARCHAR(20) NOT NULL,
  last_name VARCHAR(20) NOT NULL,
  dob DATE NOT NULL,
  gender VARCHAR(10) NOT NULL,
  street VARCHAR(40) NOT NULL,
  suburb VARCHAR(40) NOT NULL,
  state VARCHAR(3) NOT NULL,
  postcode CHAR(4) NOT NULL,
  country VARCHAR(40) NOT NULL,
  email VARCHAR(100) NOT NULL,
  phone VARCHAR(12) NOT NULL,
  workstyle VARCHAR(10),
  startdate DATE,
  skills VARCHAR(255),
  htmllevel TINYINT,
  csslevel TINYINT,
  jslevel TINYINT,
  designlevel TINYINT,
  otherskills TEXT,
  resume VARCHAR(255),
  `references` TEXT,
  newsletter VARCHAR(100),
  submission_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  status VARCHAR(10) DEFAULT 'New'
)";
@mysqli_query($conn, $create_table_sql);

// Collect & sanitize POST data
$job_ref     = isset($_POST['jobref']) ? clean_input($_POST['jobref']) : '';
$first_name  = isset($_POST['firstname']) ? clean_input($_POST['firstname']) : '';
$last_name   = isset($_POST['lastname']) ? clean_input($_POST['lastname']) : '';
$dob_raw     = isset($_POST['dob']) ? clean_input($_POST['dob']) : '';
$gender      = isset($_POST['gender']) ? clean_input($_POST['gender']) : '';
$street      = isset($_POST['street']) ? clean_input($_POST['street']) : '';
$suburb      = isset($_POST['suburb']) ? clean_input($_POST['suburb']) : '';
$state       = isset($_POST['state']) ? clean_input($_POST['state']) : '';
$postcode    = isset($_POST['postcode']) ? clean_input($_POST['postcode']) : '';
$country     = isset($_POST['country']) ? clean_input($_POST['country']) : '';
$email       = isset($_POST['email']) ? clean_input($_POST['email']) : '';
$phone       = isset($_POST['phone']) ? clean_input($_POST['phone']) : '';
$workstyle   = isset($_POST['workstyle']) ? clean_input($_POST['workstyle']) : '';
$startdate   = !empty($_POST['startdate']) ? clean_input($_POST['startdate']) : null;
$skills      = isset($_POST['skills']) ? implode(", ", $_POST['skills']) : '';
$htmllevel   = isset($_POST['htmllevel']) ? clean_input($_POST['htmllevel']) : 0;
$csslevel    = isset($_POST['csslevel']) ? clean_input($_POST['csslevel']) : 0;
$jslevel     = isset($_POST['jslevel']) ? clean_input($_POST['jslevel']) : 0;
$designlevel = isset($_POST['designlevel']) ? clean_input($_POST['designlevel']) : 0;
$otherskills = isset($_POST['otherskills']) ? clean_input($_POST['otherskills']) : '';
$newsletter  = !empty($_POST['newsletter']) ? clean_input($_POST['newsletter']) : 'No';
$references  = isset($_POST['references']) ? clean_input($_POST['references']) : '';

// Validation
$errors = [];

// Required fields check
$required_fields = [
    'job_ref' => $job_ref,
    'first_name' => $first_name,
    'last_name' => $last_name,
    'dob' => $dob_raw,
    'gender' => $gender,
    'street' => $street,
    'suburb' => $suburb,
    'state' => $state,
    'postcode' => $postcode,
    'country' => $country,
    'email' => $email,
    'phone' => $phone
];
foreach($required_fields as $key => $value) {
    if(empty($value)) {
        $label = ucwords(str_replace('_',' ',$key));
        $errors[] = "$label is required.";
    }
}

// Format validations
if(!empty($job_ref) && !preg_match("/^[A-Za-z0-9]{5}$/", $job_ref)) $errors[] = "Job reference must be 5 alphanumeric characters.";
if(!empty($first_name) && !preg_match("/^[A-Za-z]{1,20}$/", $first_name)) $errors[] = "First name must contain letters only (max 20).";
if(!empty($last_name) && !preg_match("/^[A-Za-z]+(?: [A-Za-z]+)*$/", $last_name)) $errors[] = "Last name must contain letters only (max 20).";
if(!empty($dob_raw) && !preg_match("/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/", $dob_raw)) $errors[] = "Date of birth must be in dd/mm/yyyy format.";
if(!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";
if(!empty($phone) && !preg_match("/^\d{8,12}$/", $phone)) $errors[] = "Phone number must be 8–12 digits.";
$valid_states = ['VIC','NSW','QLD','NT','WA','SA','TAS','ACT'];
if(!empty($state) && !in_array($state, $valid_states)) $errors[] = "Invalid state selected.";
if(!empty($postcode) && !preg_match("/^\d{4}$/", $postcode)) $errors[] = "Postcode must be 4 digits.";

// Convert DOB to MySQL DATE format if valid
if(empty($errors)) {
    $dob_parts = explode("/", $dob_raw);
    $dob = $dob_parts[2] . "-" . $dob_parts[1] . "-" . $dob_parts[0];
} else {
    $dob = null;
}

// Resume upload (optional)
$resumePath = '';
$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

if (isset($_FILES['resume']) && $_FILES['resume']['error'] != UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['resume'];
    $allowedTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];
    $maxSize = 5*1024*1024; // 5MB

    if (!in_array($file['type'], $allowedTypes)) $errors[] = "Resume must be PDF or Word document.";
    if ($file['size'] > $maxSize) $errors[] = "Resume file must be <= 5MB.";

    if (empty($errors)) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = $first_name . '_' . $last_name . '_Resume_' . time() . '.' . $ext;
        $resumePath = $uploadDir . $fileName;
        move_uploaded_file($file['tmp_name'], $resumePath);
    }
}

// Show errors if exist
if (!empty($errors)) {
    echo "<style>
        body{font-family:Arial,sans-serif;background:#f0f0f0;}
        .conf-page{display:flex;justify-content:center;align-items:center;height:100vh;}
        .conf-card{background:#fff;padding:30px;border-radius:10px;box-shadow:0 0 15px rgba(0,0,0,0.2);}
        .btn{margin-top:15px;padding:10px 20px;border:none;border-radius:5px;background:#1976d2;color:#fff;cursor:pointer;}
        .btn:hover{background:#115293;}
        .btn-reset{background:#d32f2f;color:#fff;}
        .btn-reset:hover{background:#9a0007;}
    </style>";
    echo "<div class='conf-page'><div class='conf-card'>";
    echo "<h1>❌ Submission Errors</h1><ul>";
    foreach ($errors as $err) echo "<li>".htmlspecialchars($err)."</li>";
    echo "</ul><button class='btn btn-reset' onclick=\"window.location.href='apply.php'\">Go Back to Form</button>";
    echo "</div></div>";
    exit();
}

// Insert record
$query = "INSERT INTO eoi (job_ref, first_name, last_name, dob, gender, street, suburb, state, postcode, country, email, phone, workstyle, startdate, skills, htmllevel, csslevel, jslevel, designlevel, otherskills, resume, `references`, newsletter)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'sssssssssssssssssssssss',
    $job_ref, $first_name, $last_name, $dob, $gender, $street, $suburb, $state,
    $postcode, $country, $email, $phone, $workstyle, $startdate, $skills,
    $htmllevel, $csslevel, $jslevel, $designlevel, $otherskills, $resumePath,
    $references, $newsletter
);
mysqli_stmt_execute($stmt);

$eoi_id = mysqli_insert_id($conn);
$affected_rows = mysqli_stmt_affected_rows($stmt);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Application Submitted</title>
<style>
body{font-family:Arial,sans-serif;background:linear-gradient(to bottom, #f5f7fa, #c3cfe2);margin:0;padding:0;}
.conf-page{display:flex;justify-content:center;align-items:center;height:100vh;}
.conf-card{background:#fff;padding:40px 30px;border-radius:15px;box-shadow:0 5px 20px rgba(0,0,0,0.3);text-align:center;max-width:500px;width:90%;}
.conf-card h1{margin-bottom:20px;font-size:28px;}
.conf-card p{margin-bottom:15px;font-size:16px;}
.btn{margin-top:20px;padding:12px 25px;border:none;border-radius:6px;background:#1976d2;color:#fff;font-size:16px;cursor:pointer;}
.btn:hover{background:#115293;}
.btn-reset{background:#d32f2f;color:#fff;}
.btn-reset:hover{background:#9a0007;}
</style>
</head>
<body>
<div class="conf-page">
    <div class="conf-card">
        <?php if ($affected_rows > 0): ?>
            <h1>✅ Application Received!</h1>
            <p>Thank you <strong><?= htmlspecialchars($first_name) ?> <?= htmlspecialchars($last_name) ?></strong> for applying.</p>
            <p>Your Expression of Interest Number (EOI ID) is <strong><?= $eoi_id ?></strong>.</p>
            <button class="btn" onclick="window.location.href='about.php'">Check out our About page</button>
        <?php else: ?>
            <h1>❌ Submission Failed</h1>
            <p>There was an error submitting your application. Please try again.</p>
            <button class="btn btn-reset" onclick="window.location.href='apply.php'">Go Back to Form</button>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
