<h1><?php echo $sbLocation['title']; ?></h1>
<?php echo $sbLocation['map_description'];  ?>
<?php if($engineSlug): ?>
<a class="sb-location-read-more" href="<?php echo url_for('@sb_location?engine-slug=' . $engineSlug . '&slug=' . $sbLocation['slug']); ?>">Read More</a>
<?php else: ?>
<a class="sb-location-read-more" href="<?php echo url_for('@sb_location?slug=' . $sbLocation['slug']); ?>">Read More</a>
<?php endif; ?>