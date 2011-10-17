<div class="sidebar">
	<div class="well">
		<h3><?php echo "<?php echo __('Actions');?>"; ?></h3>
		<ul>
<?php
	echo "\t\t\t<li><?php echo \$this->Html->link(__('Edit " . $singularHumanName ."', true), array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?></li>\n";
	echo "\t\t\t<li><?php echo \$this->Form->postLink(__('Delete " . $singularHumanName . "'), array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), null, __('Are you sure you want to delete # %s?', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?> </li>\n";
	echo "\t\t\t<li><?php echo \$this->Html->link(__('List " . $pluralHumanName . "', true), array('action' => 'index')); ?></li>\n";
	echo "\t\t\t<li><?php echo \$this->Html->link(__('New " . $singularHumanName . "', true), array('action' => 'add')); ?></li>\n";

	$done = array();
	foreach ($associations as $type => $data) {
		foreach ($data as $alias => $details) {
			if ($details['controller'] != $this->name && !in_array($details['controller'], $done)) {
				echo "\t\t\t<li><?php echo \$this->Html->link(__('List " . Inflector::humanize($details['controller']) . "', true), array('controller' => '{$details['controller']}', 'action' => 'index')); ?></li>\n";
				echo "\t\t\t<li><?php echo \$this->Html->link(__('New " .  Inflector::humanize(Inflector::underscore($alias)) . "', true), array('controller' => '{$details['controller']}', 'action' => 'add')); ?></li>\n";
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
        <h2><?php echo "<?php echo __('{$singularHumanName}');?>";?></h2>
    	<table class="zebra-striped">
<?php
foreach ($fields as $field) {
	$isKey = false;
	if (!empty($associations['belongsTo'])) {
		foreach ($associations['belongsTo'] as $alias => $details) {
			if ($field === $details['foreignKey']) {
				$isKey = true;
				echo "\t\t\t<tr>\n";
				echo "\t\t\t\t<td><?php echo __('" . Inflector::humanize(Inflector::underscore($alias)) . "'); ?></td>\n";
				echo "\t\t\t\t<td><?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?></td>\n";
				echo "\t\t\t</tr>\n";
				break;
			}
		}
	}
	if ($isKey !== true) {
		echo "\t\t\t<tr>\n";
		echo "\t\t\t\t<td><?php echo __('" . Inflector::humanize($field) . "'); ?></td>\n";
		echo "\t\t\t\t<td><?php echo \${$singularVar}['{$modelClass}']['{$field}']; ?></td>\n";
		echo "\t\t\t</tr>\n";
	}
}
?>
	    </table>
    </div>

<?php
if (!empty($associations['hasOne'])) :
	foreach ($associations['hasOne'] as $alias => $details): ?>
	<div class="columns">
		<h2><?php echo "<?php echo __('" . Inflector::humanize($details['controller']) . "');?>";?></h2>
		<dl>
	<?php
			foreach ($details['fields'] as $field) {
				echo "\t\t<dt><?php echo __('" . Inflector::humanize($field) . "');?></dt>\n";
				echo "\t\t<dd><?php echo \${$singularVar}['{$alias}']['{$field}'];?></dd>\n";
			}
	?>
		</dl>
		<div class="well">
			<?php echo "<?php echo \$this->Html->link(__('Edit " . Inflector::humanize(Inflector::underscore($alias)) . "', true), array('controller' => '{$details['controller']}', 'action' => 'edit', \${$singularVar}['{$alias}']['{$details['primaryKey']}']),array('class'=>'btn')); ?>\n";?>
		</div>
	</div>
	<?php
	endforeach;
endif;
if (empty($associations['hasMany'])) {
	$associations['hasMany'] = array();
}
if (empty($associations['hasAndBelongsToMany'])) {
	$associations['hasAndBelongsToMany'] = array();
}
$relations = array_merge($associations['hasMany'], $associations['hasAndBelongsToMany']);
$i = 0;
foreach ($relations as $alias => $details):
	$otherSingularVar = Inflector::variable($alias);
	$otherPluralHumanName = Inflector::humanize($details['controller']);
	?>
	<div class="columns">
		<h2><?php echo "<?php echo __('" . $otherPluralHumanName . "');?>";?></h2>
		<?php echo "<?php if (!empty(\${$singularVar}['{$alias}'])) { ?>\n";?>
		<table class="common-table zebra-striped">
			<tr>
<?php
			foreach ($details['fields'] as $field) {
				if ($field!='id'&&$field!=$singularVar.'_id')
				echo "\t\t\t\t<th><?php __('" . Inflector::humanize($field) . "'); ?></th>\n";
			}
?>
				<th width="142px"><?php echo "<?php echo __('Actions');?>";?></th>
			</tr>
<?php
		echo "\t\t\t<?php foreach (\${$singularVar}['{$alias}'] as \${$otherSingularVar}) { ?>\n";
		echo "\t\t\t<tr>\n";

				foreach ($details['fields'] as $field) {
					if ($field!='id'&&$field!=$singularVar.'_id')
					echo "\t\t\t\t<td><?php echo \${$otherSingularVar}['{$field}'];?></td>\n";
				}

				echo "\t\t\t\t<td>\n";
				echo "\t\t\t\t\t<?php echo \$this->Html->link(__('View', true), array('controller' => '{$details['controller']}', 'action' => 'view', \${$otherSingularVar}['{$details['primaryKey']}']),array('class'=>'btn small primary')); ?>\n";
				echo "\t\t\t\t\t<?php echo \$this->Html->link(__('Edit', true), array('controller' => '{$details['controller']}', 'action' => 'edit', \${$otherSingularVar}['{$details['primaryKey']}']),array('class'=>'btn small')); ?>\n";
				echo "\t\t\t\t\t<?php echo \$this->Form->postLink(__('Delete'), array('controller' => '{$details['controller']}', 'action' => 'delete', \${$otherSingularVar}['{$details['primaryKey']}']), array('class' => 'btn small danger'), __('Are you sure you want to delete # %s?', \${$otherSingularVar}['{$details['primaryKey']}'])); ?>\n";
				echo "\t\t\t\t</td>\n";
			echo "\t\t\t</tr>\n";
			echo "\t\t\t<?php } //endforeach; ?>\n";
?>
		</table>
		<?php echo "<?php } //endif; ?>\n";?>
	</div>
<?php endforeach;?>
</div>
