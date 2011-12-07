<ul class="a-ui a-controls">
	<?php if ($form->isNew()): ?>
		<?php echo $helper->linkToSaveAndList($form->getObject(), array(  'params' =>   array(  ),  'class_suffix' => 'save_and_list',  'label' => 'Save and list',)) ?>					  		
		<?php echo $helper->linkToList(array(  'params' =>   array(  ),  'class_suffix' => 'list',  'label' => 'Back to list',)) ?>					  		<?php echo $helper->linkToDelete($form->getObject(), array(  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>				
	<?php else: ?>
		<?php echo $helper->linkToSave($form->getObject(), array(  'params' =>   array(  ),  'class_suffix' => 'save_and_list',  'label' => 'Save and list',)) ?>					  		
		<?php echo $helper->linkToList(array(  'params' =>   array(  ),  'class_suffix' => 'list',  'label' => 'Back to list',)) ?>					  		
		<?php echo $helper->linkToDelete($form->getObject(), array(  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>			
	<?php endif; ?>
</ul>
<?php a_js_call('sbLocationsSetupFormChangeDetection()') ?>