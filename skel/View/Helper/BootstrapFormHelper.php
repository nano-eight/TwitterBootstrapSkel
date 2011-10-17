<?php
App::uses('AppHelper', 'View/Helper');

class BootstrapFormHelper extends AppHelper {

	public $helpers = array('Html', 'Form');

	public function input($name, $options = array()) {
		$default = array(
			'type' => null,
			'label' => null,
			'before' => null, // to convert .input-prepend
			'after' => null, // to convert .help-inline
			'div' => array(
				'class' => 'input',
			),
		);
		$options = Set::merge($default, $options);

		$out = array();

		$label = $options['label'];
		$out[] = $this->Form->label($name, $label);

		$options['label'] = false;
		$divOptions = $options['div'];
		$options['div'] = false;
		$options['error'] = array(
			'wrap' => 'span',
			'class' => 'help-block',
		);
		if ($options['after']) {
			$options['after'] = $this->Html->tag('span', $options['after'], array(
				'class' => 'help-inline',
			));
		}
		if ($options['before']) {
			$options = $this->_prepend($options['before'], $options);
		}

		$form = $this->Form->input($name, $options);
		if (!empty($options['multiple']) && $options['multiple'] === 'checkbox') {
			$form = $this->_multipleCheckbox($form);
		}
		$out[] = $this->Html->div($divOptions['class'], $form, $divOptions);

		$errorClass = '';
		if ($this->Form->error($name)) {
			$errorClass = ' error';
		}
		return $this->Html->div('clearfix' . $errorClass, implode("\n", $out));
	}

	protected function _prepend($before, $options) {
		$before = $this->Html->tag('span', $before, array('class' => 'add-on'));
		$options['before'] = '<div class="input-prepend">' . $before;
		$options['after'] .= '</div>';
		return $options;
	}

	protected function _multipleCheckbox($out) {
		if (!preg_match_all('/<div[^>]+>(<input type="checkbox"[^>]+>)(<label[^>]+>)([^<]*)(<\/label>)<\/div>/m', $out, $matches)) {
			return $out;
		}
		if (!preg_match('/<input type="hidden"[^>]+>/m', $out, $match)) {
			return $out;
		}
		$hidden = $match[0];
		$lines = array();
		foreach ($matches[0] as $key => $value) {
			$line = $matches[2][$key] . $matches[1][$key] . '&nbsp;';
			$line .= $this->Html->tag('span', $matches[3][$key]) . $matches[4][$key];
			$lines[] = $this->Html->tag('li', $line);
		}
		$out = $hidden;
		$out .= $this->Html->tag('ul', implode("\n", $lines), array('class' => 'inputs-list'));
		return $out;
	}
}
