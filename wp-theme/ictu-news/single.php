<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
  <div class="container header-inner">
    <a class="brand" href="<?php echo esc_url(home_url('/')); ?>">
      <span class="brand-mark">ICTU</span>
      <span class="brand-name">
        NEWS
        <small>TIN TỨC VÀ CÔNG NGHỆ</small>
      </span>
    </a>
    <nav class="main-nav" aria-label="Menu chính">
      <a href="<?php echo esc_url(home_url('/')); ?>">
        Trang chủ
      </a>
    </nav>
  </div>
</header>

<main class="container ictu-article-page">
<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>

    <article <?php post_class('ictu-article'); ?>>

      <a class="ictu-back" href="<?php echo esc_url(home_url('/')); ?>">
        ← Quay lại trang chủ
      </a>

      <div class="ictu-article-meta">
        <?php the_category(' · '); ?>
        <span>
          <?php echo esc_html(get_the_date('d/m/Y')); ?>
        </span>
      </div>

      <h1 class="ictu-article-title">
        <?php the_title(); ?>
      </h1>

      <?php if (has_post_thumbnail()) : ?>
        <div class="ictu-article-image">
          <?php the_post_thumbnail('large'); ?>
        </div>
      <?php endif; ?>

      <div class="ictu-article-content">
        <?php the_content(); ?>
        <?php wp_link_pages(); ?>
      </div>

      <div class="ictu-article-bottom">
        <a class="ictu-back" href="<?php echo esc_url(home_url('/')); ?>">
          ← Xem các tin tức khác
        </a>
      </div>

    </article>

  <?php endwhile; ?>
<?php endif; ?>
</main>

<footer class="site-footer">
  <div class="container footer-inner">
    <strong>ICTU NEWS</strong>
    <p>
      © <?php echo esc_html(wp_date('Y')); ?>
      ICTU News. Kết nối tri thức, lan tỏa công nghệ.
    </p>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
