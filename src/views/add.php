<?php include __DIR__ . '/layout/header.php'; ?>
<?php include __DIR__ . '/layout/sidebar.php'; ?>

<main>
  <h2>Add Tweet</h2>

  <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

  <div class="tweet" style="padding:20px;">
    <form method="POST" action="index.php?action=storeTweet" enctype="multipart/form-data">
      <textarea name="content" rows="4" style="width:100%;" placeholder="Write your tweet..." required></textarea>
      <br><br>
      <label>Image (optional):</label>
      <input type="file" name="image" accept="image/*">
      <br><br>
      <button type="submit" style="background:#1da1f2;color:#fff;padding:8px 12px;border:none;border-radius:6px;">Post</button>
    </form>
  </div>
</main>
