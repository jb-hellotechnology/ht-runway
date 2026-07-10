<?php
  // Single blog post (Perch Blog). The post slug comes from the {s} segment of
  // the URL, routed to this page (see SETUP.md). Render once and capture so we
  // can detect a missing post and build the <head> before the body.
  $slug      = perch_get('s');
  $post_html = perch_blog_post($slug, true);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php if ($post_html): ?>
    <?php perch_blog_post_meta($slug); ?>
  <?php else: ?>
    <title>Post not found — hellotechnology</title>
  <?php endif; ?>
  <?php perch_layout('global.head'); ?>
</head>
<body>

  <?php perch_layout('global.header'); ?>

  <main>
    <?php if ($post_html): ?>
      <?php echo $post_html; ?>
    <?php else: ?>
      <section class="section">
        <div class="container container--narrow">
          <h1 class="h1 h1--sm">Post not found</h1>
          <p class="lead mt-5">Sorry, that post doesn't exist. <a class="link" href="/blog">Back to the blog <i data-lucide="arrow-right"></i></a></p>
        </div>
      </section>
    <?php endif; ?>
  </main>

  <?php perch_layout('cta'); ?>
  <?php perch_layout('global.footer'); ?>

</body>
</html>
