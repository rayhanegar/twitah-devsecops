<?php include __DIR__ . '/layout/header.php'; ?>
<?php include __DIR__ . '/layout/sidebar.php'; ?>

<main>
  <h2>Tweets</h2>

    <!-- 🔍 Search Form -->
  <form method="GET" action="index.php" style="margin-bottom: 20px;">
    <input type="text" name="q" placeholder="Search tweets..." 
           value="<?= isset($_GET['q']) ? $_GET['q'] : '' ?>" 
           style="padding:8px; width:60%; border-radius:20px; border:1px solid #ccc;">
    <button type="submit" style="background:#1da1f2; color:white; border:none; padding:8px 16px; border-radius:20px; cursor:pointer;">
      Search
    </button>
  </form>

  <?php if ($tweets && $tweets->num_rows > 0): ?>
    <?php while ($row = $tweets->fetch_assoc()): ?>
      <div class="tweet">
        <strong>
          <a href="index.php?action=profile&id=<?= $row['id']; ?>" 
            style="text-decoration:none; color:#000;">
            @<?= $row['username']; ?>
          </a>
        </strong><br>
        <!-- intentionally NOT escaping content to allow XSS demo -->
        <p><?= $row['content']; ?></p>
        <?php if (!empty($row['image_url'])): ?>
          <!-- no validation on image_url; will render whatever path is stored -->
          <img src="<?= $row['image_url']; ?>" alt="tweet image">
        <?php endif; ?>
        <small><?= $row['created_at']; ?></small>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No tweets yet.</p>
  <?php endif; ?>
</main>
