<?php $proximitySearchForm = isset($proximitySearchForm) ? $sf_data->getRaw('proximitySearchForm') : new sbLocationProximitySearchForm(); ?>
<?php $page = isset($page) ? $sf_data->getRaw('page') : aTools::getCurrentPage(); ?>

<form id="sb-location-proximity-search" class="sb-location-proximity-search" method="get" action="<?php echo $page['slug']; ?>#sb-location-proximity-search">
<?php echo $proximitySearchForm; ?>
<input type="submit" value="Search" />
</form>