<?php
require_once __DIR__ . '/db.php';

try {
  $members = db()->query(
    'SELECT Student_ID, Name, Contribution_part1, Contribution_part2,
            Quote, Quote_translation, Favourite_language, Hidden_note,
            Photo_path, Dream_job, Coding_snack, Hometown
     FROM about
     ORDER BY Student_ID'
  )->fetchAll();
} catch (Throwable $e) {
  $members = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Prismatics — a startup strengthening its product and design team through tech talent. Find out about our mission, open roles and how to apply.">
  <meta name="author" content="Prismatics Group Project">
  <title> About | Prismatics</title>
  <link rel="icon" type="image/png" href="images/icon.png">
  <link rel="stylesheet" href="styles/styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
  <style>
    .db-badge{display:inline-block;background:#1f2937;border:1px solid #263244;border-radius:8px;padding:4px 8px}
    .wrap{white-space:pre-line}
    .member-card{display:flex;gap:16px;align-items:flex-start;background:#111827;border:1px solid #1f2937;border-radius:12px;padding:16px;margin:12px 0}
    .member-img img{width:120px;height:120px;object-fit:cover;border-radius:12px;border:1px solid #1f2937}
    .fun-facts{width:100%;border-collapse:collapse}
    .fun-facts th,.fun-facts td{padding:10px;border-bottom:1px solid #1f2937}
    .nav-menu{list-style:none;display:flex;gap:12px;margin:0;padding:0}
    .nav-menu a{background:#1f2937;border-radius:10px;padding:8px 10px;text-decoration:none}
    header#main-header{border-bottom:1px solid #1f2937;padding:12px 16px}
    .container{max-width:980px;margin:0 auto;padding:16px}
    body{margin:0;background:#0b0f14;color:#e8eaed;font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Arial,sans-serif}
  </style>
</head>
<body>

<header id="main-header">
  <div class="header-content">
    <h1>Prismatics About Page</h1>
  </div>
  <p id="top-para">Strengthening our product and design team through tech talent</p>

  <!-- Navigation bar -->
  <nav>
    <img src="images/logo3.png" alt="Prismatics Logo" title="Prismatics Logo" class="logo">
    <ul class="nav-menu">
      <li><a href="index.php">Home</a></li>
      <li><a href="jobs.php">Jobs</a></li>
      <li><a href="apply.php">Apply</a></li>
      <li><a href="about.php" class="active-link">About</a></li>
    </ul>
  </nav>
</header>

<main class="container">
  <section id="group-info" class="group-flex">
    <h2 id="dont-show">Group Details</h2>
    <div class="group-details">
      <ul>
        <li>Group Name - Prismatics
          <ul><li>Class: Tuesday 12:30 - 2:30</li></ul>
        </li>
      </ul>
    </div>

    <?php if ($members): ?>
      <div>
        <p class="student-id">
          <?php foreach ($members as $m): ?>
            <?= htmlspecialchars($m['Name']) ?> - <?= htmlspecialchars($m['Student_ID']) ?><br>
          <?php endforeach; ?>
        </p>
      </div>
    <?php endif; ?>
  </section>

  <!-- Group Photo -->
  <section>
    <h2>Our Team</h2>
    <figure>
      <img src="images/group_picture.jpg" title="Prismatics team" alt="Prismatics Team Photo" class="group-photo">
      <figcaption>The Prismatics Team — united by innovation.</figcaption>
    </figure>
  </section>

  <!-- Dynamic: Contributions table (Project 1 + 2) -->
  <section class="db-contrib">
    <h2>Member Contributions (Loaded from Database)</h2>
    <p class="db-badge">Source: jobs_db.about</p>

    <?php if (!$members): ?>
      <p>No records yet. Add rows in phpMyAdmin → jobs_db → about.</p>
    <?php else: ?>
      <table class="fun-facts">
        <thead>
          <tr>
            <th style="width:120px;">Student ID</th>
            <th style="width:240px;">Name</th>
            <th>Project 1</th>
            <th>Project 2</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($members as $m): ?>
            <tr>
              <td><?= htmlspecialchars($m['Student_ID']) ?></td>
              <td><?= htmlspecialchars($m['Name']) ?></td>
              <td class="wrap"><?= nl2br(htmlspecialchars($m['Contribution_part1'] ?? '')) ?></td>
              <td class="wrap"><?= nl2br(htmlspecialchars($m['Contribution_part2'] ?? '')) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </section>

  <!-- Dynamic: Member cards (quotes, fav language, hidden notes, photo) -->
  <section id="Membercontrib">
    <h2>Member Contributions and Quotes</h2>
    <p style="text-align:center;font-style:italic;color:#bbb;">Hover below to reveal a hidden message from each member.</p>

    <?php foreach ($members as $m): ?>
      <div class="member-card">
        <div class="member-img">
          <img src="<?= htmlspecialchars($m['Photo_path'] ?: 'images/icon.png') ?>"
               alt="<?= htmlspecialchars($m['Name']) ?>"
               title="<?= htmlspecialchars($m['Name']) ?>">
        </div>
        <div>
          <dl>
            <dt><strong><?= htmlspecialchars($m['Name']) ?></strong></dt>
            <dd>
              Contributions: Project 1 &amp; 2 (see table above)<br>
              <?php if (!empty($m['Quote'])): ?>
                <em><?= htmlspecialchars($m['Quote']) ?></em><br>
              <?php endif; ?>
              <?php if (!empty($m['Quote_translation'])): ?>
                Translation: <?= htmlspecialchars($m['Quote_translation']) ?><br>
              <?php endif; ?>
              <?php if (!empty($m['Favourite_language'])): ?>
                Favourite Language: <?= htmlspecialchars($m['Favourite_language']) ?><br>
              <?php endif; ?>
            </dd>
          </dl>

          <?php if (!empty($m['Hidden_note'])): ?>
            <p class="hidden-note"><?= htmlspecialchars($m['Hidden_note']) ?></p>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </section>

  <!-- Dynamic: Fun Facts -->
  <section>
    <h2>Fun Facts About Our Team</h2>
    <?php if (!$members): ?>
      <p>No records yet.</p>
    <?php else: ?>
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
          <?php foreach ($members as $m): ?>
            <tr>
              <td><?= htmlspecialchars($m['Name']) ?></td>
              <td><?= htmlspecialchars($m['Dream_job'] ?? '') ?></td>
              <td><?= htmlspecialchars($m['Coding_snack'] ?? '') ?></td>
              <td><?= htmlspecialchars($m['Hometown'] ?? '') ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </section>
</main>

<footer>
  <hr id="fotterhr">
  <p id="footerp">
    <a href="https://github.com/105975653/Prismatics_group-project.git" target="_blank">GitHub Repository</a> |
    <a href="mailto:info@companyname.com">Email Us</a>
  </p>
  <p id="copy">&copy; <?= date('Y') ?> Prismatics</p>
</footer>
</body>
</html>
