<?php
if (!defined('ABSPATH')) {
    exit;
}
/* Template Name: Front Page */

get_header();
?>

<main class="page page-home">
    <?php get_template_part('ui/blocks/hero/hero'); ?>
    <?php get_template_part('ui/blocks/Gallery/Gallery'); ?>
</main>

<?php get_footer(); ?>
