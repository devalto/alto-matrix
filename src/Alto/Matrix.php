<?php

namespace Alto;

class Matrix {

	/**
	 * Raw list of numbers used by the class
	 *
	 * Rows are the first level of the array and columns are element of the 
	 * second level of arrays.
	 * 
	 * @var array
	 */
	private $_data;

	/**
	 * Number of rows in the matrix
	 * 
	 * @var int
	 */
	private $_num_rows;

	/**
	 * Number of columns in the matrix
	 * 
	 * @var int
	 */
	private $_num_cols;

	/**
	 * @param array $data List of numbers in the array
	 */
	public function __construct(array $data) {
		$this->setData($data);
	}

	/**
	 * Access the numeric data at the row anc column index
	 * 
	 * @param integer $row_index Index of the row
	 * @param integer $col_index Index of the column
	 *
	 * @return numeric
	 */
	public function getAt($row_index, $col_index) {
		$this->validateIndexBoundary($row_index, $col_index);

		return $this->_data[$row_index][$col_index];
	}

	/**
	 * Set a value at a row and a column index in the matrix
	 * 
	 * @param int $row_index
	 * @param int $col_index
	 * @param numeric $value
	 */
	public function setAt($row_index, $col_index, $value) {
		$this->validateIndexBoundary($row_index, $col_index);

		if (!is_numeric($value)) {
			throw new Matrix\Exception\NotNumericException("Value is not a numeric");
		}

		$this->_data[$row_index][$col_index] = $value;

		return $this;
	}

	/**
	 * Get the number of rows in the matrix
	 * @return int Number of rows
	 */
	public function getNumRows() {
		return $this->_num_rows;
	}

	/**
	 * Get the number of columns in the matrix
	 * @return int Number of rows
	 */
	public function getNumColumns() {
		return $this->_num_cols;
	}

	/**
	 * Multiply all values by $factor
	 * 
	 * @param  numeric $factor value used to multiply
	 * @return Matrix new matrix
	 */
	public function multiply($factor) {
		if (!is_numeric($factor)) {
			throw new Matrix\Exception\NotNumericException("Value is not a numeric");
		}

		$data = array_map(function($row) use ($factor) {
			return array_map(function($value) use ($factor) {
				return $value * $factor;
			}, $row);
		}, $this->_data);

		return new self($data);
	}

	public function addMatrix(Matrix $matrix_to_add) {
		if (!$this->isSameSize($matrix_to_add)) {
			throw new Matrix\Exception\InvalidOperationException("Matrix is not of the same size");
		}

		$total_matrix = self::createEmptyMatrix($this->getNumRows(), $this->getNumColumns());
		for ($i = 0; $i < $this->getNumRows(); $i++) {
			for ($j = 0; $j < $this->getNumColumns(); $j++) {
				$total_matrix->setAt($i, $j, $this->getAt($i, $j) + $matrix_to_add->getAt($i, $j));
			}
		}

		return $total_matrix;
	}

	/**
	 * Compares the size of two matrix
	 * 
	 * @param  Matrix  $matrix Matrix to compare to
	 * @return boolean
	 */
	public function isSameSize(Matrix $matrix) {
		return ($matrix->getNumColumns() == $this->getNumColumns() && $matrix->getNumRows() == $this->getNumRows());
	}

	/**
	 * Set the data into the matrix
	 * 
	 * @param array $data List of number in the array
	 * @return Matrix self
	 */
	public function setData(array $data) {
		// Validate the data
		$this->validateData($data);
		
		// Store rows and columns number
		$this->_num_rows = count($data);
		foreach ($data as $row) {
			$this->_num_cols = count($row);
			break;
		}

		$this->_data = $data;

		return $this;
	}

	/**
	 * Get the data array 
	 * 
	 * @return array
	 */
	public function getData() {
		return $this->_data;
	}

	/**
	 * Validate that a row and column index is valid in the matrix
	 *
	 * @param  int $row_index Row index
	 * @param  int $col_index Column index
	 * @return bool
	 */
	public function validateIndexBoundary($row_index, $col_index) {
		if (!($row_index >= 0 && $row_index < $this->_num_rows && $col_index >= 0 && $col_index < $this->_num_cols)) {
			throw new Matrix\Exception\IndexOutOfBoundException("Either the row index or the column index is out of bound");
		}

		return true;
	}

	/**
	 * Validates the data source array
	 *
	 * @pre $data should not be null
	 * @pre Each element of $data should be an array (rows)
	 * @pre Each array element of $data should be of the same size (cols)
	 * @pre Each array element of $data should contain only numeric values
	 * 
	 * @param  array  $data Data source array
	 * @return boolean
	 */
	public function validateData(array $data) {
		$num_cols = null;
		foreach ($data as $row) {
			if (!is_array($row)) {
				throw new Matrix\Exception\InvalidDataException("A row contained in the data is not an array");
			}

			if (is_null($num_cols)) {
				$num_cols = count($row);
			}

			if (count($row) == 0) {
				throw new Matrix\Exception\InvalidDataException("Data is empty (no data in columns)");
			}

			if ($num_cols != count($row)) {
				throw new Matrix\Exception\InvalidDataException("Rows in data array are not all of the same size");
			}

			foreach ($row as $value) {
				if (!is_numeric($value)) {
					throw new Matrix\Exception\NotNumericException("Values in the matrix are not all of numeric type");
				}
			}
		}
	}

	/**
	 * Creates an matrix with 0 value to all element
	 * 
	 * @param  int $num_rows    Number of rows
	 * @param  int $num_columns Number of columns
	 * @return Matrix Empty matrix
	 */
	public static function createEmptyMatrix($num_rows, $num_columns) {
		$data = array();
		for ($i = 0; $i < $num_rows; $i++) {
			$row = array();
			for ($j = 0; $j < $num_columns; $j++) {
				$row[] = 0;
			}
			$data[] = $row;
		}

		return new Matrix($data);
	}

}