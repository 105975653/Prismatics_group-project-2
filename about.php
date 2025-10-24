<?php
// Load DB helper and fetch member contributions from jobs_db.about
require_once __DIR__ . '/db.php';

try {
  $rows = db()->query(
    'SELECT Student_ID, Name, Contribution_part1, Contribution_part2 FROM about ORDER BY Student_ID'
  )->fetchAll();
} catch (Throwable $e) {
  // Fail soft so the page layout still renders
  $rows = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php 
    $page_title = "About | Prismatics";
    include("head.inc"); 
  ?>
</head>

<body>
<?php
  $header_title = "Prismatics About Page";
  $current_page = "about";
  include("header.inc");
?>


<div>
  <section id="group-info" class="group-flex">
    <h2 id="dont-show">Group Details</h2>
    <div class="group-details">
      <ul>
        <li>Group Name - Prismatics
          <ul>
            <li>Class: Tuesday 12:30 - 2:30</li>
          </ul>
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

<!-- Group Photo -->
<section>
  <h2>Our Team</h2>
  <figure>
    <img src="images/group_picture.jpg" title="Prismatics team" alt="Prismatics Team Photo" class="group-photo">
    <figcaption>The Prismatics Team — united by innovation.</figcaption>
  </figure>
</section>

<!-- DB-driven contributions table (inserted; layout around it unchanged) -->
<section class="db-contrib">
  <h2>Member Contributions (Loaded from Database)</h2>
  <p class="db-badge">Source: jobs_db.about</p>

  <?php if (!$rows): ?>
    <p>No records yet. Add rows in phpMyAdmin → jobs_db → about.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th style="width:120px;">Student ID</th>
          <th style="width:240px;">Name</th>
          <th>Project 1</th>
          <th>Project 2</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['Student_ID']) ?></td>
            <td><?= htmlspecialchars($r['Name']) ?></td>
            <td class="wrap"><?= nl2br(htmlspecialchars($r['Contribution_part1'])) ?></td>
            <td class="wrap"><?= nl2br(htmlspecialchars($r['Contribution_part2'])) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</section>

<!-- Member Contributions -->
<section id="Membercontrib">
  <h2>Member Contributions and Quotes</h2>
  <p style="  text-align: center; font-style: italic; color: #555;">  Hover below to reveal a hidden message from each member. </p>
<div class="member-card">
  <dl>
    <dt><strong>Farhan Monirul Islam</strong></dt>
    <dd>
      Applying page + CSS <br>
      <em>"কোড হলো হাস্যরসের মতো। যখন তোমাকে এটা ব্যাখ্যা করতে হয়, তখন সেটা খারাপ।"</em><br>
      Translation: Code is like humor. When you have to explain it, it’s bad. <br>
      Favourite Languages: Python, Ruby and HTML <br>
    </dd>
  </dl>

  <p id="farhan-color" class="hidden-note">
    Hidden note from Farhan: I loved working with the team — it was a chance to grow, solve challenges, and bring our ideas to life.  
    <br>(My favourite color is the color of this text)
  </p> 

  <div class="member-img">
    <img src="images/farhan.jpg" alt="Farhan Monirul Islam" title="farhan">
  </div>
</div>


  <div class="member-card">
  <dl>
    <dt><strong>Thisum Rajaratne</strong></dt>
    <dd>
      Contributions: Home Page <br>
      <em>"හොඳ නිර්මාණයක් හොඳ කේතයක් තරම්ම වැදගත්."</em>
      Translation: "Good design is just as important as good code". <br>
      Favourite Language: Ruby <br>
    </dd>
  </dl>
    <p id="thisum-color" class="hidden-note">"Hidden note from Thisum: Being part of this project was exciting and taught me new skills while creating something meaningful together. <br>(My favourite color is the color of this text) </p>

    <div class="member-img">
      <img src="images/thisum.png" alt="Thisum Rajaratne" title="thisum">
    </div>
  </div>

  <div class="member-card">
  <dl>
    <dt><strong>Chamathka Sandali Amarasekara</strong></dt>
      <dd>
      Contributions: Job Description Page <br>
      <em>"තාක්ෂණය  මිනිසුන්ගේ ජීවිත වෙනස් කළ හැකියැයි මම විශ්වාස කරනවා, සහ මම එම වෙනසකට සම්බන්ධවීමට කැමතියි."</em>
      Translation: "I believe that technology can change people's lives, and I want to be involved in that change." <br>
      Favourite Language: Python <br>
    </dd>
  </dl>
    <p id="chamathka-color" class="hidden-note">Hidden note from Chamathka: I really enjoyed contributing and collaborating. Every step showed me the value of teamwork and creativity. <br> (My favourite color is the color of this text) </p>

    <div class="member-img">
      <img src="images/chamathka.png" alt="Chamathka Sandali Amarasekara" title="chama">
    </div>
  </div>

  <div class="member-card">
  <dl>
    <dt><strong>Mahabub Hasan Rahat</strong></dt>
    <dd>
      About Page <br>
      <em>""কোডিং হলো যুক্তির মাধ্যমে প্রকাশিত সৃজনশীলতা।."</em>
      Translation: "Coding is creativity expressed in logic." <br>
      Favourite Language: Python <br>
    </dd>
  </dl>
    <p id="rahat-color" class="hidden-note">Hidden note from Rahat: This project was a rewarding journey where I learned, shared ideas, and grew alongside a great team. <br> (My favourite color is the color of this text) </p>

    <div class="member-img">
      <img src="images/rahat.jpg" alt="Mahabub Hasan Rahat" title="Rahat">
    </div>
  </div>

</section>

<!-- Fun Facts Table -->
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
      <tr>
        <td>Farhan Monirul Islam</td>
        <td>Computer Scientist</td>
        <td>Coffee</td>
        <td>Dhaka</td>
      </tr>
      <tr>
        <td>Thisum Rajaratne</td>
        <td>Farmer Far from city</td>
        <td>Chocolate</td>
        <td>kandy</td>
      </tr>
      <tr>
        <td>Chamathka Sandali Amarasekara</td>
        <td>software developer</td>
        <td>chocolate</td>
        <td>colombo</td>
      </tr>
      <tr>
        <td>Mahabub Hasan Rahat</td>
        <td>AI Engineer</td>
        <td>Pizza</td>
        <td>Dhaka</td>
      </tr>
    </tbody>
  </table>
</section>

<?php include("footer.inc"); ?>

</footer>
</body>
</html>
