<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\ClProperty;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$comments_number = get_comments_number();
$has_thumbnail   = has_post_thumbnail() ? ' has-thumbnail' : ' has-no-thumbnail';
$post_class      = 'blog-box grid-style is-date';
$post_class      .= $has_thumbnail;
?>

<div class="col">
    <article id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?>>
		<?php if ( has_post_thumbnail() ):
			$thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'rdtheme-size2' );
			?>
            <div class="post-img is-date">
                <a href="<?php the_permalink(); ?>">
                    <div class="thumb-bg" style="background-image:url(<?php echo esc_url( $thumb_url ) ?>)">
                    </div>
                </a>
				<?php edit_post_link( 'Edit' ); ?>
            </div>
			<?php if ( RDTheme::$options['blog_date'] ): ?>
            <div class="thumbnail-date">
                <div class="popup-date">
					<?php
					printf( "<span class='day'>%s</span><span class='month'>%s</span>",
						esc_html( get_the_time( 'd' ) ),
						esc_html( get_the_time( 'M' ) )
					);
					?>
                </div>
            </div>
		<?php endif; ?>
		<?php endif; ?>
        <div class="post-content">

            <div class="post-meta is_dots">

                <ul class="list-inline">
					<?php if ( RDTheme::$options['blog_author_name'] ) : ?>
                        <li class="author-meta">
                        <i class="icon-rt-icon-user-line"></i><span class="author vcard">
                            <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
                                <?php
                                echo esc_html__( 'by ', 'clproperty' ) . get_the_author();
                                ?>
                            </a>
                        </span>
                        </li>
					<?php endif; ?>

					<?php if ( RDTheme::$options['blog_cat_visibility'] && has_category() ): ?>
                        <li class="category-meta">
                        <i class="fa-solid fa-tag"></i><span class="posted-in">
                        <?php echo wp_kses_post( get_the_category_list( esc_html_x( ', ', 'Used between list items, there is a space after the comma.', 'clproperty' ) ) );
                        ?>
                    </span>
                        </li>
					<?php endif; ?>

					<?php if ( RDTheme::$options['blog_archive_reading_time'] ) : ?>
                        <li class="reading-time">
                        <i class="fa-solid fa-clock"></i><a href="#" data-toggle="tooltip"
                               data-original-title="<?php echo esc_attr__( 'Reading Time', 'clproperty' ) ?>"><?php echo esc_html( Helper::reading_time_count( get_the_content() ) ); ?></a>
                        </li>
					<?php endif; ?>

                </ul>

            </div>

            <h3 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h3>

            <div class="read-more-grid-btn">
                <a href="<?php the_permalink(); ?>" class="">
					<?php echo esc_html__( 'Read More', 'clproperty' ); ?>
                    <i class="arrow-btn"></i>
                </a>
            </div>
        </div>
    </article>
</div>