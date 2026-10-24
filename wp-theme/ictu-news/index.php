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
      <span class="brand-name">NEWS<small>TIN TỨC VÀ CÔNG NGHỆ</small></span>
    </a>
    <nav class="main-nav">
      <?php wp_nav_menu(['theme_location' => 'primary', 'container' => false, 'fallback_cb' => 'wp_page_menu']); ?>
    </nav>
  </div>
</header>

<section class="hero">
  <div class="container hero-content">
    <span class="eyebrow">ICTU NEWS · CẬP NHẬT MỖI NGÀY</span>
    <h1>Kết nối tri thức,<br><span>lan tỏa công nghệ</span></h1>
    <p>Tin tức mới nhất về công nghệ, giáo dục và đời sống.</p>
    <a class="hero-button" href="#tin-moi">Khám phá tin tức →</a>
  </div>
</section>

<main class="container news-section" id="tin-moi">
  <div class="section-heading">
    <div>
      <span class="eyebrow">ĐỪNG BỎ LỠ</span>
      <h2>Tin tức mới nhất</h2>
    </div>
    <span class="section-note">Thông tin nổi bật dành cho bạn</span>
  </div>

  <div class="news-grid">
    <?php
    $news_query = new WP_Query([
      'post_type' => 'post',
      'post_status' => 'publish',
      'posts_per_page' => 9,
      'ignore_sticky_posts' => true
    ]);

    if ($news_query->have_posts()) :
      while ($news_query->have_posts()) : $news_query->the_post();
    ?>
      <article <?php post_class('news-card'); ?>>
        <a class="card-image" href="<?php the_permalink(); ?>">
          <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('large'); ?>
          <?php else : ?>
            <div class="image-placeholder">ICTU</div>
          <?php endif; ?>
        </a>
        <div class="card-content">
          <div class="card-meta">
            <span><?php
              $categories = get_the_category();
              echo $categories ? esc_html($categories[0]->name) : 'Tin tức';
            ?></span>
            <time><?php echo esc_html(get_the_date('d/m/Y')); ?></time>
          </div>
          <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
          <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></p>
          <a class="read-more" href="<?php the_permalink(); ?>">Đọc bài viết →</a>
        </div>
      </article>
    <?php
      endwhile;
      wp_reset_postdata();
    else :
      echo '<p>Hiện chưa có bài viết nào được đăng.</p>';
    endif;
    ?>
  </div>
</main>

<footer class="site-footer">
  <div class="container footer-inner">
    <strong>ICTU NEWS</strong>
    <p>© <?php echo esc_html(wp_date('Y')); ?> ICTU News. Kết nối tri thức, lan tỏa công nghệ.</p>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
