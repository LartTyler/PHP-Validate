<?php
	namespace dbstudios\phpvalidate;

	use \Closure;
	use dbstudios\phpenum\Enum;

	class FieldType extends Enum {
		private $validator;

		public function __construct(Closure $validator) {
			$this->validator = $validator;
		}

		public function isValid($str) {
			$v = $this->validator;
			return $v($str);
		}

		public static function init() {
			parent::register('TEXT', function($str) {
				return strlen($str) > 0;
			});
			parent::register('NUMBER', function($str) {
				return is_numeric($str);
			});
			parent::register('INTEGER', function($str) {
				return is_numeric($str) && strpos($v, '.') === false;
			});
			parent::register('DECIMAL', function($str) {
				return is_numeric($str);
			});
			parent::register('EMAIL', function($str) {
				return preg_match('/^.+@.+\..+$/', $v) === 1 ? true : false;
			});
			parent::register('POSTAL', function($str) {
				return strlen(str_replace(array(' ', '.', '_', '-'), '', $v)) >= 5;
			});
			parent::register('PHONE', function($str) {
				return strlen(preg_replace('/[^\d]/', '', $v)) >= 9;
			});
			parent::register('NOOP', function($str) {
				return true;
			});

			parent::stopRegistration();
		}

		public static function valueOf($str) {
			return parent::valueOf(strtoupper($str));
		}
	}

	FieldType::init();
?>