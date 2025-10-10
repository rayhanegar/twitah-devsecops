<?php include __DIR__ . '/layout/header.php'; ?>
<?php include __DIR__ . '/layout/sidebar.php'; ?>

<main>
  <h2>Add Tweet</h2>

  <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

  <div class="tweet-add">
    <form method="POST" action="index.php?action=storeTweet" enctype="multipart/form-data">
      <textarea name="content" rows="4" placeholder="Write your tweet..." required></textarea>
      <br><br>
      <label>Image (optional):</label>
      <input type="file" name="image" accept="image/*">
      <br><br>
      <button type="submit">Post</button>
    </form>
  </div>
</main>
