<?php
namespace App\QueryField;

use App\Log;

/**
 * Base Query Field Class
 * @package YetiForce.App
 * @license licenses/License.html
 * @author Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */
class BaseField
{

	/**
	 * @var QueryGenerator 
	 */
	protected $queryGenerator;

	/**
	 * @var \Vtiger_Field_Model 
	 */
	protected $fieldModel;

	/**
	 * @var string
	 */
	protected $fullColumnName;

	/**
	 * @var string|array 
	 */
	protected $value;

	/**
	 * @var string 
	 */
	protected $operator;

	const STRING_TYPE = ['string', 'text', 'email', 'reference'];
	const NUMERIC_TYPE = ['integer', 'double', 'currency'];
	const DATE_TYPE = ['date', 'datetime'];
	const EQUALITY_TYPES = ['currency', 'percentage', 'double', 'integer', 'number'];
	const COMMA_TYPES = ['picklist', 'multipicklist', 'owner', 'date', 'datetime', 'time', 'tree', 'sharedOwner', 'sharedOwner'];

	/**
	 * Constructor
	 * @param \App\QueryGenerator $queryGenerator
	 * @param \Vtiger_Field_Model $fieldModel
	 * @param string|array $value
	 * @param string $operator
	 */
	public function __construct(\App\QueryGenerator $queryGenerator, $fieldModel = false)
	{
		$this->queryGenerator = $queryGenerator;
		$this->fieldModel = $fieldModel;
	}

	/**
	 * Function to get combinations of string from Array
	 * @param array $array
	 * @param string $tempString
	 * @return array
	 */
	public static function getCombinations($array, $tempString = '')
	{
		$countArray = count($array);
		for ($i = 0; $i < $countArray; $i++) {
			$splicedArray = $array;
			$element = array_splice($splicedArray, $i, 1); // removes and returns the i'th element
			if (count($splicedArray) > 0) {
				if (!is_array($result)) {
					$result = [];
				}
				$result = array_merge($result, static::getCombinations($splicedArray, $tempString . ' |##| ' . $element[0]));
			} else {
				return [$tempString . ' |##| ' . $element[0]];
			}
		}
		return $result;
	}

	/**
	 * Get module name
	 * @return string
	 */
	public function getModuleName()
	{
		return $this->queryGenerator->getModule();
	}

	/**
	 * Get value
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * Set value
	 * @param string $value
	 */
	public function setValue($value)
	{
		$this->value = $value;
	}

	/**
	 * Set operator
	 * @param string $operator
	 */
	public function setOperator($operator)
	{
		$this->operator = strtolower($operator);
	}

	/**
	 * Is the field date type
	 * @return boolean
	 */
	private function isDateType()
	{
		return in_array($this->fieldModel->getFieldDataType(), [static::DATE_TYPE]);
	}

	/**
	 * Is the field numeric type
	 * @return boolean
	 */
	private function isNumericType()
	{
		return in_array($this->fieldModel->getFieldDataType(), [static::NUMERIC_TYPE]);
	}

	/**
	 * Is the field string type
	 * @return boolean
	 */
	private function isStringType()
	{
		return in_array($this->fieldModel->getFieldDataType(), [static::STRING_TYPE]);
	}

	/**
	 * Is the field equality type
	 * @return boolean
	 */
	private function isEqualityType()
	{
		return in_array($this->fieldModel->getFieldDataType(), [static::EQUALITY_TYPES]);
	}

	/**
	 * Is the field comma separated type
	 * @return boolean
	 */
	private function isCommaSeparatedType()
	{
		return in_array($this->fieldModel->getFieldDataType(), [static::COMMA_TYPES]);
	}

	/**
	 * Get column name
	 * @return string
	 */
	public function getColumnName()
	{
		if ($this->fullColumnName) {
			return $this->fullColumnName;
		}
		return $this->fullColumnName = $this->fieldModel->getTableName() . '.' . $this->fieldModel->getColumnName();
	}

	/**
	 * Get order by
	 * @return array
	 */
	public function getOrderBy($order = false)
	{
		if ($order && strtolower($order) === 'desc') {
			return [$this->getColumnName() => SORT_DESC];
		} else {
			return [$this->getColumnName() => SORT_ASC];
		}
	}

	/**
	 * Get condition
	 * @return boolean|array
	 */
	public function getCondition()
	{
		$fn = 'operator' . ucfirst($this->operator);
		var_dump($fn);
		if (method_exists($this, $fn)) {
			Log::trace("Entering to $fn in " . __CLASS__);
			return $this->$fn();
		}
		Log::error("Not found operator: $fn in  " . __CLASS__);
		return false;
	}

	/**
	 * Equals operator
	 * @return array
	 */
	public function operatorE()
	{
		return [$this->getColumnName() => $this->getValue()];
	}

	/**
	 * Not equal operator
	 * @return array
	 */
	public function operatorN()
	{
		return ['<>', $this->getColumnName(), $this->getValue()];
	}

	/**
	 * Is empty operator
	 * @return array
	 */
	public function operatorY()
	{
		return ['or',
				[$this->getColumnName() => null],
				['=', $this->getColumnName(), '']
		];
	}

	/**
	 * Is not empty operator
	 * @return array
	 */
	public function operatorNy()
	{
		return ['and',
				['not', [$this->getColumnName() => null]],
				['<>', $this->getColumnName(), '']
		];
	}
}
