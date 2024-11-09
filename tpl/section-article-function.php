<?php
if (iro_opt("article_function")) {
?>
    <footer class="post-footer">
        <?php
        global $post;
        $post_meta_license = get_post_meta($post->ID, 'license', true);
        $license = $post_meta_license ? $post_meta_license : iro_opt("article_lincenses");
        $license_link;
        $license_icon;
        $license_desc;

        if ($license != "0") {
            if ($license == "1") {
                $license = "cc-by-nc-sa";
            }
            if ($license === "cc0") {
                $license_link = "https://creativecommons.org/publicdomain/zero/1.0/";
                $license_desc = sprintf(__("This article is licensed under %s", "sakurairo"), "CC0 1.0");
                $license_icon = array("fa-creative-commons-zero");
            } else {
                $variant = substr($license, 3);
                $license_link = "https://creativecommons.org/licenses/$variant/4.0/";
                $license_desc = sprintf(__("This article is licensed under %s", "sakurairo"), "CC " . strtoupper($variant) . " 4.0");
                $license_icon = array_map(function ($v) {
                    return "fa-creative-commons-$v";
                }, explode("-", $variant));
            }
            // 根据WP设置的语言填充链接
            // 不存在该语言翻译时会默认显示英文
            $locale = get_locale();
        ?>
            <a class="post-license" href="<?= $license_link . "deed." . $locale ?>" target="_blank" rel="nofollow" title="<?= $license_desc ?>">
                <i class="fa-brands fa-creative-commons"></i>
                <?php
                foreach ($license_icon as $icon_class) {
                    echo '<i class="fa-brands ' . $icon_class . '"></i>';
                }
                ?>
            </a>
        <?php
        }
        ?>

        <?php the_reward(); ?>
        <section class="author-profile">
            <?php
            $author_id = get_the_author_meta('ID');
            $author_url = esc_url(get_author_posts_url($author_id));
            $author_name = get_the_author();
            ?>
            <div class="info" itemprop="author" itemscope="" itemtype="http://schema.org/Person">
                <a href="<?= $author_url; ?>" class="profile gravatar">
                    <img class="fa-spin" style="--fa-animation-duration: 15s;" src="<?php echo get_avatar_profile_url(); ?>" itemprop="image" alt="<?= $author_name; ?>" height="30" width="30">
                </a>
            </div>
            <div class="meta">
                <a href="<?= $author_url; ?>" itemprop="url" rel="author"><?= $author_name; ?></a>
            </div>
            <?php
            if (iro_opt('author_profile_quote') == '1') {
                $author_description = get_the_author_meta('description');
                if (empty($author_description)) {
                    $author_description = __('This author has not provided a description.', 'sakurairo');
                }
                // 对描述内容进行转义，防止XSS攻击
    			$safe_description = htmlspecialchars($author_description, ENT_QUOTES, 'UTF-8');
            ?>
                <div class="desc">
                    <i class="fa-solid fa-feather" aria-hidden="true"></i>
                    <?php echo nl2br($safe_description);?>
                </div>
            <?php } ?>
        </section>
        <div class="post-modified-time">
            <i class="fa-solid fa-calendar-day" aria-hidden="true"></i><?php _e('Last updated on ', 'sakurairo');
                                                                        echo get_the_modified_time('Y-m-d'); ?>
        </div>
        <div class="post-tags">
            <?php if (has_tag()) {
                echo '<i class="fa-solid fa-tag" aria-hidden="true"></i> ';
                the_tags('', ' ', ' ');
            } else {
                echo '<i class="fa-solid fa-tag" aria-hidden="true"></i> ';
                _e('Nothing~', 'sakurairo');
            } ?>
        </div>
    </footer><!-- .entry-footer -->
<?php
}
