<div class="sidebar">
	<div class="well">
		<h3><?php echo "<?php echo __('Actions');?>"; ?></h3>
		<ul>
			<?php echo "<li><?php echo \$this->Html->link(__('New " . $singularHumanName . "', true), array('action' => 'add')); ?></li>\n";?>
<?php
$done = array();
foreach ($associations as $type => $data) {
	foreach ($data as $alias => $details) {
		if ($details['controller'] != $this->name && !in_array($details['controller'], $done)) {
			echo "\t\t\t<li><?php echo \$this->Html->link(__('List " . Inflector::humanize($details['controller']) . "', true), array('controller' => '{$details['controller']}', 'action' => 'index')); ?></li>\n";
			echo "\t\t\t<li><?php echo \$this->Html->link(__('New " . Inflector::humanize(Inflector::underscore($alias)) . "', true), array('controller' => '{$details['controller']}', 'action' => 'add')); ?></li>\n";
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
		<h2><?php echo "<?php echo __('{$pluralHumanName}');?>";?></h2>
		<table class="common-table zebra-striped">
			<?php
			echo "<tr>\n";
			foreach ($fields as $field) {
				echo "\t\t\t\t<th><?php echo \$this->Paginator->sort('{$field}');?></th>\n";
			}
			?>
				<th width="142px"><?php echo "<?php echo __('Actions');?>";?></th>
			</tr>
			<?php
			echo "<?php
			\$i = 0;
			foreach (\${$pluralVar} as \${$singularVar}):
				\$class = null;
				if (\$i++ % 2 == 0) {
					\$class = ' class=\"altrow\"';
				}
			?>\n";
			echo "\t\t\t<tr<?php echo \$class;?>>\n";
				foreach ($fields as $field) {
					$isKey = false;
					if (!empty($associations['belongsTo'])) {
						foreach ($associations['belongsTo'] as $alias => $details) {
							if ($field === $details['foreignKey']) {
								$isKey = true;
								echo "\t\t\t\t<td>\n\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t</td>\n";
								break;
							}
						}
					}
					if ($isKey !== true) {
						echo "\t\t\t\t<td><?php echo \${$singularVar}['{$modelClass}']['{$field}']; ?>&nbsp;</td>\n";
					}
				}

			echo "\t\t\t\t<td>\n";
			echo "\t\t\t\t\t<?php echo \$this->Html->link(__('View', true), array('action' => 'view', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class'=>'btn small primary')); ?>\n";
			echo "\t\t\t\t\t<?php echo \$this->Html->link(__('Edit', true), array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class'=>'btn small')); ?>\n";
			echo "\t\t\t\t\t<?php echo \$this->Form->postLink(__('Delete'), array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class' => 'btn small danger'), __('Are you sure you want to delete # %s?', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>\n";
			echo "\t\t\t\t</td>\n";
			echo "\t\t\t</tr>\n";

		echo "\t\t\t<?php endforeach; ?>\n";
		?>
		</table>
		<p>
		<?php echo "<?php
		echo \$this->Paginator->counter(array(
			'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}', true)
		));
		?>\n";?>
		</p>
		<?php echo "<?php echo \$this->element('pagination');?>\n";?>
	</div>
</div>
