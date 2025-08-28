<?php get_header(); ?>
<div class="container">
    <h1>All Books</h1>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <?php the_excerpt(); ?>
        </article>
        <hr>
    <?php endwhile; endif; ?>
</div>
<?php get_footer(); ?>
