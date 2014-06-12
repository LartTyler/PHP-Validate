<?php
	namespace dbstudios\phpvalidate;

	class Validator {
		private $fields = array();
		private $required = array();

		public function __construct(array $fields = array()) {
			$this->addFields($fields);
		}

		public function addField($name, FieldType $type, $required = true) {
			$this->fields[$name] = $type;

			if ($required)
				$this->required[] = $name;

			return $this;
		}

		public function addFields(array $fields) {
			foreach ($fields as $k => $v)
				if ($v instanceof FieldType)
					$this->addField($k, $v);
				else
					throw new Exception(sprintf('%s is not a FieldType object!', $v));

			return $this;
		}

		public function removeField($name) {
			if (!array_key_exists($name, $this->fields))
				return $this;

			unset($this->fields[$name]);

			return $this;
		}

		public function removeFields(array $names) {
			foreach ($names as $name)
				$this->removeField($name);

			return $this;
		}

		public function clear() {
			$this->fields = array();

			return $this;
		}

		public function validate(array &$userData) {
			$keys = array_keys($this->fields);
			$processed = array();

			foreach ($this->fields as $name => $fieldType)
				if (($isValid = $fieldType->isValid($userData[$name])) === false && in_array($name, $this->required))
					return false;
				else if (!$isValid)
					unset($userData[$name]);

			return true;
		}
	}
?>