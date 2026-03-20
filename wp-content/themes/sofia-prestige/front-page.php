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
    <?php get_template_part('ui/blocks/consultation/consultation'); ?>
    <?php get_template_part('ui/blocks/faq/faq'); ?>
    <?php get_template_part('ui/blocks/blog/blog'); ?>
    <?php get_template_part('ui/blocks/contacts/contacts'); ?>
    <?php get_template_part('ui/blocks/partners/partners'); ?>
    <?php get_template_part('ui/blocks/seo-content/seo-content'); ?>
</main>

<?php get_footer(); ?>
