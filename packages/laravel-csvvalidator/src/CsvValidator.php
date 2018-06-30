<?php namespace Sukohi\CsvValidator;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CsvValidator {
	/**
     * Validation rules
     *
     * @var array $columns
     */
	private $rules;

	/**
     * Spreadsheet data collection;
     *
     * @var Collection  $collection
     */
	private $collection = [];

	/**
     * Validation errors;
     *
     * @var array $columns
     */
	private $errors = [];

	public function make($path, $rules, $encoding = 'UTF-8')
    {
		$this->setRules($rules);

		$this->collection = app('excel')->load($path, null, $encoding)->get();

		return $this;
	}

	public function fails()
    {
		$errors = [];

		$this->collection->each(function ($data, $key) use(&$errors) {
            $validator = Validator::make($data->all(), $this->rules);

            if($validator->fails()) {
                $errors[$key + 2] = $validator->errors()->all();
            }
        });

		$this->errors = $errors;

		return !empty($this->errors);
	}

	public function getErrors()
    {
		return $this->errors;
	}

	public function data()
    {
		return $this->collection;
	}

	private function setRules($rules)
    {
		$this->rules = $rules;
	}
}