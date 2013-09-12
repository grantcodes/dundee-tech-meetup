<?php // Not in use yet ?>
<?php get_header(); ?>
<h1>single</h1>
    <main class="main-content" role="main">
        <?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>

        <article class="post">
            <header>
                <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
            </header>

            <?php the_content(); ?>

        </article>

        <?php endwhile; ?>

        <?php endif; ?>
        <nav class="page-nav"><?php previous_posts_link('&laquo; previous');?><?php next_posts_link('next &raquo;');?></nav>
    </main>

<?php get_footer(); ?>