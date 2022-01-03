<h2><?= esc($title); ?></h2>

<?php if (! empty($posts) && is_array($posts)) : ?>

    <?php foreach ($posts as $post): ?>

        <h3><?= esc($post['title']); ?></h3>

        <div class="main">
            <?= ($post['content']); ?>
        </div>
        <p><a href="<?php if ($post['post_type'] == 'post'): echo blog_url(esc($post['slug'], 'url')); else: {
                echo site_url(esc($post['slug'], 'url'));
            } endif ?>">View article</a></p>

    <?php endforeach; ?>

<?php else : ?>

    <h3>No Posts</h3>

    <p>Unable to find any Posts for you.</p>

<?php endif ?>