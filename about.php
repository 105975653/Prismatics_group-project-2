<?php
// about.php (dynamic)
require_once("settings.php");

// page vars for includes
$page_title   = "About | Prismatics";
$current_page = "about";

$conn = @mysqli_connect($DB_HOST, $DB_USER, $DB_PASS);
if (!$conn) { die("<p>❌ Could not connect to MySQL server.</p>"); }
@mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $DB_NAME");
mysqli_select_db($conn, $DB_NAME);

/* Create table if missing */
$create_sql = "
CREATE TABLE IF NOT EXISTS about_members (
  id INT AUTO_INCREMENT PRIMARY KEY,
  display_order TINYINT DEFAULT 0,
  full_name VARCHAR(100) NOT NULL,
  student_id VARCHAR(20) DEFAULT NULL,
  assignment1_contrib VARCHAR(255) DEFAULT '',
  assignment2_contrib VARCHAR(255) DEFAULT '',
  quote_native TEXT NOT NULL,
  quote_translation TEXT NOT NULL,
  favorite_languages VARCHAR(255) DEFAULT '',
  hidden_note TEXT DEFAULT '',
  note_color VARCHAR(7) DEFAULT '#333333',
  photo VARCHAR(255) DEFAULT '',
  dream_job VARCHAR(100) DEFAULT '',
  coding_snack VARCHAR(100) DEFAULT '',
  hometown VARCHAR(100) DEFAULT ''
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
mysqli_query($conn, $create_sql);


/* Fetch members ordered */
$members = mysqli_query($conn, "SELECT * FROM about_members ORDER BY display_order ASC, id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include 'head.inc'; ?>
</head>
<body>

<?php include 'header.inc'; ?>

<!-- Group meta remains static -->
<div>
  <section id="group-info" class="group-flex">
    <h2 id="dont-show">Group Details</h2>
    <div class="group-details">
      <ul>
        <li>Group Name - Prismatics
          <ul><li>Class: Tuesday 12:30 - 2:30</li></ul>
        </li>
      </ul>
    </div>
    <div>
      <p class="student-id">
        Farhan - 105975653 <br>
        Thisum - 105736999 <br>
        Rahat - 105952504 <br>
        Chamathka - 105704983
      </p>
    </div>
  </section>
</div>

<!-- Team photo stays static -->
<section>
  <h2>Our Team</h2>
  <figure>
    <img src="images/group_picture.jpg" title="Prismatics team" alt="Prismatics Team Photo" class="group-photo">
    <figcaption>The Prismatics Team — united by innovation.</figcaption>
  </figure>
</section>

<!-- Member Contributions (dynamic) -->
<section id="Membercontrib">
  <h2>Member Contributions and Quotes</h2>
  <p style="text-align:center; font-style: italic; color:#555;">
    Hover below to reveal a hidden message from each member.
  </p>

  <?php if ($members && mysqli_num_rows($members) > 0): ?>
    <?php
      $rows = [];
      mysqli_data_seek($members, 0);
      while ($m = mysqli_fetch_assoc($members)):
        $rows[] = $m;
    ?>
      <div class="member-card">
        <dl>
          <dt><strong><?php echo htmlspecialchars($m['full_name']); ?></strong></dt>
          <dd>
            <strong>Assignment 1 Contributions:</strong> <?php echo htmlspecialchars($m['assignment1_contrib']); ?><br>
            <strong>Assignment 2 Contributions:</strong> <?php echo htmlspecialchars($m['assignment2_contrib']); ?><br>
            <em><?php echo htmlspecialchars($m['quote_native']); ?></em><br>
            <?php echo htmlspecialchars($m['quote_translation']); ?><br>
            Favourite Languages: <?php echo htmlspecialchars($m['favorite_languages']); ?><br>
          </dd>
        </dl>

        <p class="hidden-note" style="color: <?php echo htmlspecialchars($m['note_color']); ?>">
          <?php echo htmlspecialchars($m['hidden_note']); ?><br>
          (My favourite color is the color of this text)
        </p>

        <div class="member-img">
          <img src="images/<?php echo htmlspecialchars($m['photo']); ?>"
               alt="<?php echo htmlspecialchars($m['full_name']); ?>"
               title="<?php echo htmlspecialchars(pathinfo($m['photo'], PATHINFO_FILENAME)); ?>">
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p style="text-align:center;">No team members found.</p>
  <?php endif; ?>
</section>

<!-- Fun Facts (dynamic from same table) -->
<section>
  <h2>Fun Facts About Our Team</h2>
  <table class="fun-facts">
    <thead>
      <tr>
        <th>Name</th>
        <th>Dream Job</th>
        <th>Coding Snack</th>
        <th>Hometown</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?php echo htmlspecialchars($r['full_name']); ?></td>
          <td><?php echo htmlspecialchars($r['dream_job']); ?></td>
          <td><?php echo htmlspecialchars($r['coding_snack']); ?></td>
          <td><?php echo htmlspecialchars($r['hometown']); ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>

<?php include 'footer.inc'; ?>
</body>
</html>
<?php mysqli_close($conn); ?>
