<!-- Common Header + Navigation -->
<header>
  <div>
    <h1><?php echo $header_title ?? "Prismatics"; ?></h1>
  </div>
  <p id="top-para">Strengthening our product and design team through tech talent</p>

  <nav>
    <img src="images/logo3.png" alt="Prismatics logo" title="Prismatics Logo" class="logo">
    <ul class="nav-menu">
      <li><a href="index.php" class="<?php echo ($current_page=='home' ? 'active-link' : ''); ?>">Home</a></li>
      <li><a href="jobs.php" class="<?php echo ($current_page=='jobs' ? 'active-link' : ''); ?>">Jobs</a></li>
      <li><a href="apply.php" class="<?php echo ($current_page=='apply' ? 'active-link' : ''); ?>">Apply</a></li>
      <li><a href="about.php" class="<?php echo ($current_page=='about' ? 'active-link' : ''); ?>">About</a></li>

      <?php if (!isset($_SESSION)) session_start(); ?>
      <?php if (!isset($_SESSION['username'])): ?>
      <li><a href="login.php" class="<?php echo ($current_page=='login' ? 'active-link' : ''); ?>">Manager Portal</a></li>
      <?php else: ?>
      <li><a href="manage.php" class="<?php echo ($current_page=='manage' ? 'active-link' : ''); ?>">Manage EOIs</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>
