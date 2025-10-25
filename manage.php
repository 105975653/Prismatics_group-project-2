<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: login.php");
    exit();
}

require_once("settings.php");

// Database connection
$conn = @mysqli_connect($DB_HOST, $DB_USER, $DB_PASS);
if (!$conn) {
    die("<p>❌ Could not connect to database.</p>");
}

// Create DB if missing
@mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $DB_NAME");
mysqli_select_db($conn, $DB_NAME);

// Ensure EOI table exists
$create_eoi_sql = "
CREATE TABLE IF NOT EXISTS eoi (
  eoi_id INT AUTO_INCREMENT PRIMARY KEY,
  job_ref VARCHAR(5) NOT NULL,
  first_name VARCHAR(20) NOT NULL,
  last_name VARCHAR(20) NOT NULL,
  dob VARCHAR(20) NOT NULL,
  gender VARCHAR(10) NOT NULL,
  street VARCHAR(40) NOT NULL,
  suburb VARCHAR(40) NOT NULL,
  state VARCHAR(10) NOT NULL,
  postcode VARCHAR(4) NOT NULL,
  country VARCHAR(40) NOT NULL,
  email VARCHAR(50) NOT NULL,
  phone VARCHAR(15) NOT NULL,
  workstyle VARCHAR(15),
  startdate DATE,
  skills VARCHAR(200),
  htmllevel INT,
  csslevel INT,
  jslevel INT,
  designlevel INT,
  otherskills TEXT,
  resume VARCHAR(255),
  `references` TEXT,
  newsletter VARCHAR(50),
  status ENUM('New','Current','Final') DEFAULT 'New'
)";
mysqli_query($conn, $create_eoi_sql);

// Logout handling
if (isset($_POST["logout"])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Delete EOIs by job reference
if (isset($_POST['delete_btn'])) {
    $jobref = mysqli_real_escape_string($conn, $_POST['delete_jobref']);
    mysqli_query($conn, "DELETE FROM eoi WHERE job_ref='$jobref'");
    echo "<p class='msg success'>✅ All EOIs for job reference <strong>$jobref</strong> have been deleted.</p>";
}

// Update EOI status
if (isset($_POST['update_status'])) {
    $status = $_POST['status'];
    $eoi_id = intval($_POST['eoi_id']);
    mysqli_query($conn, "UPDATE eoi SET status='$status' WHERE eoi_id=$eoi_id");
    echo "<p class='msg success'>✅ Status updated successfully.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php 
    $page_title = "Manage EOIs | Prismatics";
    include 'head.inc'; 
  ?>
</head>

<body id="manage-page">
  <!-- Header Section -->
  <h1 class="welcome-text">👋 Welcome, <?php echo $_SESSION["username"]; ?> (HR Manager)</h1>

  <!-- Logout Button -->
  <form method="post" action="" class="logout-form">
    <button type="submit" name="logout" class="logout-btn">Logout</button>
  </form>
<main>

  <!-- Search & Sort Section -->
  <section id="search-section">
    <h2>Search and Manage EOIs</h2>
    <form method="get" action="" id="search-form">
      <label for="jobref">Job Reference:</label>
      <input type="text" name="jobref" id="jobref">
      <button type="submit" name="search_by_ref" class="btn">Search</button>

      <label for="firstname">Applicant Name:</label>
      <input type="text" name="firstname" id="firstname" placeholder="First">
      <label for="lastname">Applicant Name:</label>
      <input type="text" name="lastname" id="lastname" placeholder="Last">
      <button type="submit" name="search_by_name" class="btn">Search</button>

      <label for="sort">Sort By:</label>
      <select name="sort" id="sort">
        <option value="eoi_id">EOI Number</option>
        <option value="job_ref">Job Reference</option>
        <option value="last_name">Last Name</option>
        <option value="status">Status</option>
      </select>
      <button type="submit" name="sort_btn" class="btn">Sort</button>

      <!-- List All EOIs Button -->
      <button type="submit" name="list_all" class="btn list-all">List All EOIs</button>
    </form>
  </section>

  <!-- Table Output -->
  <section id="results-section">
  <?php
  $query = "SELECT * FROM eoi";
  $conditions = [];

  // Search filters
  if (isset($_GET['search_by_ref']) && !empty($_GET['jobref'])) {
      $conditions[] = "job_ref LIKE '%" . mysqli_real_escape_string($conn, $_GET['jobref']) . "%'";
  }
  if (isset($_GET['search_by_name'])) {
      if (!empty($_GET['firstname'])) $conditions[] = "first_name LIKE '%" . mysqli_real_escape_string($conn, $_GET['firstname']) . "%'";
      if (!empty($_GET['lastname'])) $conditions[] = "last_name LIKE '%" . mysqli_real_escape_string($conn, $_GET['lastname']) . "%'";
  }

  if (!empty($conditions)) {
      $query .= " WHERE " . implode(" AND ", $conditions);
  } elseif (isset($_GET['list_all'])) {
      $query = "SELECT * FROM eoi"; // show everything
  }

  // Sorting
  if (isset($_GET['sort_btn']) && !empty($_GET['sort'])) {
      $allowed = ['eoi_id', 'job_ref', 'last_name', 'status'];
      if (in_array($_GET['sort'], $allowed)) {
          $query .= " ORDER BY " . $_GET['sort'];
      }
  }

  $result = mysqli_query($conn, $query);

  // Display results
  if (mysqli_num_rows($result) > 0): ?>
    <table id="eoi-table">
      <tr>
        <th>EOI Number</th>
        <th>Job Ref</th>
        <th>Name</th>
        <th>Email</th>
        <th>Status</th>
        <th>Update Status</th>
      </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td><?php echo $row['eoi_id']; ?></td>
        <td><?php echo $row['job_ref']; ?></td>
        <td><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>
        <td><?php echo $row['email']; ?></td>
        <td><?php echo $row['status']; ?></td>
        <td>
<form method="post" action="" class="update-form" aria-label="Update EOI Status">
  <input type="hidden" name="eoi_id" value="<?php echo $row['eoi_id']; ?>">

  <label for="status_<?php echo $row['eoi_id']; ?>" class="visually-hidden">
    Status for EOI <?php echo $row['eoi_id']; ?>
  </label>
  <select name="status" id="status_<?php echo $row['eoi_id']; ?>">
    <option value="New" <?php if($row['status'] == 'New') echo 'selected'; ?>>New</option>
    <option value="Current" <?php if($row['status'] == 'Current') echo 'selected'; ?>>Current</option>
    <option value="Final" <?php if($row['status'] == 'Final') echo 'selected'; ?>>Final</option>
  </select>

  <button type="submit" name="update_status" class="btn update-btn">Update</button>
</form>

        </td>
      </tr>
    <?php endwhile; ?>
    </table>
  <?php else: ?>
    <p class="msg">No EOIs found.</p>
  <?php endif; ?>
  </section>

  <!-- Delete Section -->
  <section id="delete-section">
    <h3>Delete EOIs by Job Reference</h3>
    <form method="post" action="" id="delete-form">
      <label for="delete_jobref">Job Reference:</label>
      <input type="text" name="delete_jobref" id="delete_jobref" required>
      <button type="submit" name="delete_btn" class="btn delete-btn">Delete</button>
    </form>
  </section>
  </main>

</body>
</html>
