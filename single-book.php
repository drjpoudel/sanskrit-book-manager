<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <?php
    // --- Get all the custom data from the plugin ---
    $pdf_url      = get_post_meta(get_the_ID(), '_sbm_pdf_url', true);
    $file_size    = get_post_meta(get_the_ID(), '_sbm_file_size', true);
    $total_pages  = get_post_meta(get_the_ID(), '_sbm_total_pages', true);
    $pub_year     = get_post_meta(get_the_ID(), '_sbm_pub_year', true);
    $editor_name  = get_post_meta(get_the_ID(), '_sbm_editor_name', true);
    $printer_name = get_post_meta(get_the_ID(), '_sbm_printer_name', true);
    $print_place  = get_post_meta(get_the_ID(), '_sbm_print_place', true);
    $source_name  = get_post_meta(get_the_ID(), '_sbm_source_name', true);
    $source_url   = get_post_meta(get_the_ID(), '_sbm_source_url', true);
    $buy_url      = get_post_meta(get_the_ID(), '_sbm_buy_url', true);

    // --- Get taxonomy data ---
    $languages  = get_the_term_list(get_the_ID(), 'language', '', ', ', '');
    $authors    = get_the_term_list(get_the_ID(), 'author', '', ', ', '');
    $publishers = get_the_term_list(get_the_ID(), 'publisher', '', ', ', '');
    ?>

    <div class="container">
        <div class="book-header">
            <?php if (has_post_thumbnail()) : ?>
                <figure><img class="book-cover" src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>" /></figure>
            <?php endif; ?>
            
            <div>
                <h1 class="book-title"><?php the_title(); ?></h1>
                
                <?php if (get_the_excerpt()) : // Optional short description ?>
                    <p class="description"><?php the_excerpt(); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="book-details">
            <?php if ($languages) : ?><strong>Language:</strong> <?php echo $languages; ?><br><?php endif; ?>
            <?php if ($publishers) : ?><strong>Publisher:</strong> <?php echo $publishers; ?><br><?php endif; ?>
            <?php if ($pub_year) : ?><strong>Year of Publication:</strong> <?php echo esc_html($pub_year); ?><br><?php endif; ?>
            <?php if ($file_size) : ?><strong>File Size:</strong> <?php echo esc_html($file_size); ?><br><?php endif; ?>
            <?php if ($total_pages) : ?><strong>Total Pages:</strong> <?php echo esc_html($total_pages); ?><br><?php endif; ?>
            <?php if ($authors) : ?><strong>Author:</strong> <?php echo $authors; ?><br><?php endif; ?>
            <?php if ($editor_name) : ?><strong>Editor:</strong> <?php echo esc_html($editor_name); ?><br><?php endif; ?>
            <?php if ($printer_name) : ?><strong>Printer:</strong> <?php echo esc_html($printer_name); ?><br><?php endif; ?>
            <?php if ($print_place) : ?><strong>Place of Printing:</strong> <?php echo esc_html($print_place); ?><br><?php endif; ?>
            <?php if ($source_name && $source_url) : ?>
                <strong>Source:</strong> <a href="<?php echo esc_url($source_url); ?>" target="_blank" rel="noopener"><?php echo esc_html($source_name); ?></a>
            <?php elseif ($source_name) : ?>
                <strong>Source:</strong> <?php echo esc_html($source_name); ?>
            <?php endif; ?>
        </div>
        
        <div class="button-frame">
            <?php if ($pdf_url) : ?>
                <a class="button-link" href="<?php echo esc_url($pdf_url); ?>" download><button class="download-button">Download</button></a>
            <?php endif; ?>

            <?php if ($buy_url) : ?>
                <a class="button-link" href="<?php echo esc_url($buy_url); ?>" target="_blank" rel="noopener"><button class="buy-button">Buy Now</button></a>
            <?php endif; ?>
        </div>

        <div class="description">
            <?php the_content(); // This is the main, long description from the WordPress editor. ?>
        </div>
    </div>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
