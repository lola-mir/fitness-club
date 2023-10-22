<?php
/**
 * The template part for displaying post
 *
 * @package VW Yoga Fitness 
 * @subpackage vw_yoga_fitness
 * @since VW Yoga Fitness 1.0
 */
?>
<?php 
  $vw_yoga_fitness_archive_year  = get_the_time('Y'); 
  $vw_yoga_fitness_archive_month = get_the_time('m'); 
  $vw_yoga_fitness_archive_day   = get_the_time('d'); 
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('inner-service'); ?>>
  <div class=" post-main-box wow zoomInDown delay-1000" data-wow-duration="2s">
    <?php
      if ( ! is_single() ) {
        // If not a single post, highlight the gallery.
        if ( get_post_gallery() ) {
          echo '<div class="entry-gallery">';
            echo ( get_post_gallery() );
          echo '</div>';
        };
      };
    ?>
    <div class="new-text">
      <h2 class="section-title"><a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo the_title_attribute(); ?>"><?php the_title();?><span class="screen-reader-text"><?php the_title(); ?></span></a></h2>
      <?php if( get_theme_mod( 'vw_yoga_fitness_toggle_postdate',true) == 1 || get_theme_mod( 'vw_yoga_fitness_toggle_author',true) == 1 || get_theme_mod( 'vw_yoga_fitness_toggle_comments',true) == 1 || get_theme_mod( 'vw_yoga_fitness_toggle_time',true) == 1) { ?>
        <div class="post-info">
          <?php if(get_theme_mod('vw_yoga_fitness_toggle_postdate',true)==1){ ?>
            <i class="<?php echo esc_attr(get_theme_mod('vw_yoga_fitness_toggle_postdate_icon','fas fa-calendar-alt')); ?>"></i><span class="entry-date"><a href="<?php echo esc_url( get_day_link( $vw_yoga_fitness_archive_year, $vw_yoga_fitness_archive_month, $vw_yoga_fitness_archive_day)); ?>"><?php echo esc_html( get_the_date() ); ?><span class="screen-reader-text"><?php echo esc_html( get_the_date() ); ?></span></a></span>
          <?php } ?>

          <?php if(get_theme_mod('vw_yoga_fitness_toggle_author',true)==1){ ?>
            <span><?php echo esc_html(get_theme_mod('vw_yoga_fitness_meta_field_separator', '|'));?></span> <i class="<?php echo esc_attr(get_theme_mod('vw_yoga_fitness_toggle_author_icon','far fa-user')); ?>"></i><span class="entry-author"><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' )) ); ?>"><?php the_author(); ?><span class="screen-reader-text"><?php the_author(); ?></span></a></span>
          <?php } ?>

          <?php if(get_theme_mod('vw_yoga_fitness_toggle_comments',true)==1){ ?>
            <span><?php echo esc_html(get_theme_mod('vw_yoga_fitness_meta_field_separator', '|'));?></span> <i class="<?php echo esc_attr(get_theme_mod('vw_yoga_fitness_toggle_comments_icon','fas fa-comments')); ?>"></i><span class="entry-comments"><?php comments_number( __('0 Comment', 'vw-yoga-fitness'), __('0 Comments', 'vw-yoga-fitness'), __('% Comments', 'vw-yoga-fitness') ); ?> </span>
          <?php } ?>

          <?php if(get_theme_mod('vw_yoga_fitness_toggle_time',true)==1){ ?>
            <span><?php echo esc_html(get_theme_mod('vw_yoga_fitness_meta_field_separator', '|'));?></span> <i class="<?php echo esc_attr(get_theme_mod('vw_yoga_fitness_toggle_time_icon','fas fa-clock')); ?>"></i><span class="entry-time"><?php echo esc_html( get_the_time() ); ?></span>
          <?php } ?>
          <hr>
        </div>
      <?php } ?>
      <div class="entry-content">
        <p>
          <?php $vw_yoga_fitness_theme_lay = get_theme_mod( 'vw_yoga_fitness_excerpt_settings','Excerpt');
          if($vw_yoga_fitness_theme_lay == 'Content'){ ?>
            <?php the_content(); ?>
          <?php }
          if($vw_yoga_fitness_theme_lay == 'Excerpt'){ ?>
            <?php if(get_the_excerpt()) { ?>
             <?php $vw_yoga_fitness_excerpt = get_the_excerpt(); echo esc_html( vw_yoga_fitness_string_limit_words( $vw_yoga_fitness_excerpt, esc_attr(get_theme_mod('vw_yoga_fitness_excerpt_number','30')))); ?> <?php echo esc_html(get_theme_mod('vw_yoga_fitness_excerpt_suffix',''));?>
            <?php }?>
          <?php }?>
        </p>
      </div>
      <?php if( get_theme_mod('vw_yoga_fitness_blog_button_text','Read More') != ''){ ?>
        <div class="content-bttn">
          <a class="view-more" href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_theme_mod('vw_yoga_fitness_blog_button_text',__('Read More','vw-yoga-fitness')));?><i class="<?php echo esc_attr(get_theme_mod('vw_yoga_fitness_blog_button_icon','fa fa-angle-right')); ?>"></i><span class="screen-reader-text"><?php echo esc_html(get_theme_mod('vw_yoga_fitness_blog_button_text',__('Read More','vw-yoga-fitness')));?></span></a>
        </div>
      <?php } ?>
    </div>
  </div>
</article>