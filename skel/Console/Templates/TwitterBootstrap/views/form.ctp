<div class="sidebar">
	<div class="well">
		<h3><?php echo "<?php echo __('Actions');?>"; ?></h3>
		<ul>
	<?php if (strpos($action, 'add') === false): ?>
		<?php echo "<li><?php echo \$this->Html->link(__('Delete " . $singularHumanName . "', true), array('action' => 'delete', \$this->Form->value('{$modelClass}.{$primaryKey}')), null, sprintf(__('Are you sure you want to delete # %s?', true), \$this->Form->value('{$modelClass}.{$primaryKey}'))); ?></li>\n";?>
	<?php endif;?>
		<?php echo "<li><?php echo \$this->Html->link(__('List " . $pluralHumanName . "', true), array('action' => 'index'));?></li>\n";?>
	<?php
		$done = array();
		foreach ($associations as $type => $data) {
			foreach ($data as $alias => $details) {
				if ($details['controller'] != $this->name && !in_array($details['controller'], $done)) {
					echo "\t<li><?php echo \$this->Html->link(__('List " . Inflector::humanize($details['controller']) . "', true), array('controller' => '{$details['controller']}', 'action' => 'index')); ?></li>\n";
					echo "\t<li><?php echo \$this->Html->link(__('New " . Inflector::humanize(Inflector::underscore($alias)) . "', true), array('controller' => '{$details['controller']}', 'action' => 'add')); ?></li>\n";
					$done[] = $details['controller'];
				}
			}
		}
	?>
		</ul>
	</div>
</div>
<div class="content">
	<div class="columns">
		<h2><?php printf("<?php echo __('%s %s'); ?>", Inflector::humanize($action), $singularHumanName); ?></h2>
		<?php echo "<?php echo \$this->Form->create('{$modelClass}');?>\n";?>
		<fieldset>
			<legend>Form</legend>
<?php
			foreach ($fields as $field) {
				if (in_array($action, array('add', 'edit')) !== false && ($field === $primaryKey || $field === 'id')) {
					continue;
				} else {
					if (!in_array($field, array('created', 'modified', 'updated'))) {
?>
			<?php echo "<?php echo \$this->BootstrapForm->input('{$field}', array('class'=>'xxlarge')); ?>\n"; ?>
<?php
					} else {
?>
			<?php echo "<?php echo \$this->BootstrapForm->input('{$field}', array('class'=>'mini')); ?>\n"; ?>
<?php
					}
				}
			}
			if (!empty($associations['hasAndBelongsToMany'])) {
				foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) {
?>
			<?php echo "<?php echo \$this->BootstrapForm->input('{$assocName}', array('multiple' => 'checkbox', 'class'=>'xxlarge')); ?>\n"; ?>
			</div>
<?php
				}
			}
?>
		<div class="actions">
			<?php echo "\t<?php echo \$this->Form->button(__('Save', true),array('class'=>'btn primary'));?>\n"; ?>
			</div>
		</fieldset>
		<?php echo "<?php echo \$this->Form->end(); ?>\n"; ?>
	</div>
</div>
