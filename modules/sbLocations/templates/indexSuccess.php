<?php $pagerUrl = url_for('sbLocations/index'); ?>

<?php if ($pager->haveToPaginate()): ?>
  	 <?php include_partial('aPager/pager', array('pager' => $pager, 'pagerUrl' => $pagerUrl)) ?>
  <?php endif ?>

<?php foreach($pager->getResults() as $sbLocation): ?>
<div class="sb-location">
  <a href="<?php echo url_for('@sb_location?slug=' . $sbLocation['slug']); ?>"><?php echo $sbLocation['title']; ?></a>
</div>
<?php endforeach; ?>

<?php if ($pager->haveToPaginate()): ?>
  	 <?php include_partial('aPager/pager', array('pager' => $pager, 'pagerUrl' => $pagerUrl)) ?>
  <?php endif ?>