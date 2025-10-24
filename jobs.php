<?php
require_once("settings.php");

// Database connection (safe version)
$conn = @mysqli_connect($DB_HOST, $DB_USER, $DB_PASS);
if (!$conn) {
    die("<p>❌ Could not connect to MySQL server.</p>");
}

// Ensure database exists (creates it automatically if missing)
@mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $DB_NAME");

// Select the database
if (!@mysqli_select_db($conn, $DB_NAME)) {
    die("<p>❌ Database selection failed.</p>");
}

// Ensure jobs table exists (safe to leave here during dev)
$create_jobs_sql = "
CREATE TABLE IF NOT EXISTS jobs (
  job_id INT AUTO_INCREMENT PRIMARY KEY,
  job_ref VARCHAR(5) NOT NULL,
  job_title VARCHAR(100) NOT NULL,
  team VARCHAR(80) NOT NULL,
  location VARCHAR(60) NOT NULL,
  salary VARCHAR(60) NOT NULL,
  reports_to VARCHAR(120) NOT NULL,
  short_description TEXT NOT NULL,
  responsibilities TEXT NOT NULL,
  requirements_essential TEXT NOT NULL,
  requirements_preferable TEXT NOT NULL,
  employment_type VARCHAR(30) DEFAULT 'Full-time',
  closing_date DATE NULL
)";
@mysqli_query($conn, $create_jobs_sql);

// Fetch jobs
$sql = "SELECT * FROM jobs ORDER BY job_id ASC";
$res = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php 
    $page_title = "Jobs | Prismatics";
    include("head.inc"); 
  ?>
</head>

<body>
  <!-- header -->
<?php
  $header_title = "Prismatics Job Portal";
  $current_page = "jobs";
  include("header.inc");
?>


  <!-- Wrap everything in main-container -->
  <div id="main-container">
    <!-- Left Section: Jobs -->
    <main class="main-content">
      <section class="intro" id="intro-section">
        <h2 class="intro-title">Join Our Prismatics Team</h2>
        <p class="intro-text">
          Explore the available opportunities below and become part of our journey to create impactful digital solutions.
        </p>
      </section>

      <?php
      if ($res && mysqli_num_rows($res) > 0):
        while ($row = mysqli_fetch_assoc($res)):
          // Prepare multi-line bullets
          $resp_items = array_filter(array_map('trim', explode("\n", $row['responsibilities'])));
          $ess_items  = array_filter(array_map('trim', explode("\n", $row['requirements_essential'])));
          $pref_items = array_filter(array_map('trim', explode("\n", $row['requirements_preferable'])));
      ?>
      <!-- One Job -->
      <section class="job" id="job-<?php echo htmlspecialchars(strtolower($row['job_ref'])); ?>">
        <h2 class="job-title"><?php echo htmlspecialchars($row['team']); ?></h2>

        <article class="job-article">
          <p class="job-reference">
            <strong>Reference No:</strong> <?php echo htmlspecialchars($row['job_ref']); ?>
          </p>
          <p class="job-location-salary">
            <strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?> | 
            <strong>Salary:</strong> <?php echo htmlspecialchars($row['salary']); ?>
          </p>
          <p class="job-reporting">
            <strong>Reports to:</strong> <?php echo htmlspecialchars($row['reports_to']); ?>
          </p>

          <h3 class="job-section-title">Short Description</h3>
          <p class="job-description"><?php echo nl2br(htmlspecialchars($row['short_description'])); ?></p>

          <h3 class="job-section-title">Key Responsibilities</h3>
          <ol class="job-responsibilities">
            <?php foreach($resp_items as $it): ?>
              <li><?php echo htmlspecialchars($it); ?></li>
            <?php endforeach; ?>
          </ol>

          <h3 class="job-section-title">Requirements</h3>
          <ul class="job-requirements">
            <li>
              <strong>Essential:</strong>
              <?php echo htmlspecialchars(implode(' | ', $ess_items)); ?>
            </li>
            <li>
              <strong>Preferable:</strong>
              <?php echo htmlspecialchars(implode(' | ', $pref_items)); ?>
            </li>
          </ul>

          <!-- Apply button carries job_ref to your apply form -->
          <p style="margin-top:12px; color">
            <a class="btns" href="apply.php?jobref=<?php echo urlencode($row['job_ref']); ?>">
              Apply Now
            </a>
          </p>
        </article>
      </section>
      <?php
        endwhile;
      else:
      ?>
        <section class="job">
          <h2 class="job-title">No positions available right now</h2>
          <p>Please check back later.</p>
        </section>
      <?php endif; ?>
    </main>
  </div>

  <!-- Aside -->
  <aside>
    <h2>Why Work at Prismatics?</h2>
    <ul>
      <li>Innovative and Impactful Work: At Prismatics, you’ll be part of a team that constantly pushes the boundaries of technology and design. Every project has the potential to make a real-world impact, helping businesses and users achieve their goals through innovative digital solutions.</li>
      <li>Career Growth and Learning Opportunities: At Prismatics, your professional development comes first. From the start, you’ll be encouraged to take ownership of projects, expand your skills, and explore new ideas.</li>
      <li>Flexible Work Arrangements: Maintain a healthy work-life balance with hybrid and remote options. Flexibility allows creativity and efficiency to thrive, ensuring you can be productive while managing your personal life.</li>
      <li>Collaborative and Supportive Culture: At Prismatics, teamwork is at the heart of everything we do.</li>
    </ul>
  </aside>

  <section class="did-you-know">
    <h2>Did You Know?</h2>
    <p><strong>Product Team 🛠️:</strong> You’ll help shape features from idea to launch. Even small suggestions in our product discussions can directly influence what gets built next.</p>
    <p><strong>Designer Team 🎨:</strong> Your designs get seen and used immediately. In a startup, your work goes straight to real users without layers of bureaucracy.</p>
    <p><strong>Cross-Team Collaboration 🤝:</strong> Product and Design teams work side by side. Your input will directly affect how features look, feel, and function for our users.</p>
    <p><strong>Fast-Paced Environment ⚡:</strong> Being a startup, you’ll often see your work implemented quickly, giving you immediate results and learning opportunities.</p>
    <p><strong>Growth Opportunities 🌱:</strong> You’ll get exposure to multiple aspects of the company, from brainstorming new features to refining final designs, collaborating with different teams, and taking on meaningful responsibilities, all accelerating your overall professional growth and career development.</p>
    <p><strong>Innovation in Action 💡:</strong> Every team member’s ideas are valued—whether it’s a small improvement or a bold new feature, your creativity can directly influence the product and user experience.</p>
    <p><strong>Direct Feedback Loop 🔄:</strong> You’ll receive constructive feedback directly from teammates, product leads, and even users, helping you refine your work quickly, improve your skills consistently, and learn continuously.</p>
  </section>
  <br>

  <!-- Footer -->
<?php include("footer.inc"); ?>

</body>
</html>
<?php mysqli_close($conn); ?>
