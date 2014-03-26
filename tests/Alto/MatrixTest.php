<?php

namespace Alto;

class MatrixTest extends \PHPUnit_Framework_TestCase {

	public function testCreateMatrix() {
		$data = [
			[1, 3],
			[2, 5]
		];

		$matrix = new Matrix($data);

		$this->assertEquals(1, $matrix->getAt(0, 0));
		$this->assertEquals(3, $matrix->getAt(0, 1));
		$this->assertEquals(2, $matrix->getAt(1, 0));
		$this->assertEquals(5, $matrix->getAt(1, 1));
	}

	public function testCreateMatrixWithRowsOfDifferentSize() {
		$this->setExpectedException('\Alto\Matrix\Exception\InvalidDataException', 'Rows in data array are not all of the same size');

		$data = [
			[1, 3],
			[2, 5, 4]
		];

		$matrix = new Matrix($data);
	}

	public function testCreateMatrixWithInvalidRows() {
		$this->setExpectedException('\Alto\Matrix\Exception\InvalidDataException', 'A row contained in the data is not an array');

		new Matrix(['invalid', [1]]);
	}

	public function testCreateMatrixWithEmptyColumns() {
		$this->setExpectedException('\Alto\Matrix\Exception\InvalidDataException', 'Data is empty (no data in columns)');

		new Matrix([[], []]);
	}

	public function testCreateMatrixWithNonNumericValues() {
		$this->setExpectedException('\Alto\Matrix\Exception\InvalidDataException', 'Values in the matrix are not all of numeric type');

		new Matrix([[1, 2], [3, 'invalid']]);
	}

	public function testGetDataWithOutOfBoundIndex() {
		$this->setExpectedException('\Alto\Matrix\Exception\IndexOutOfBoundException');

		$data = [
			[1, 3],
			[2, 5]
		];

		$matrix = new Matrix($data);

		$matrix->getAt(-1, 0);
	}

	public function testSetValueWithIndexOutOfBound() {
		$this->setExpectedException('\Alto\Matrix\Exception\IndexOutOfBoundException');

		$data = [
			[1, 3],
			[2, 5]
		];

		$matrix = new Matrix($data);
		$matrix->setAt(1, 2, 0);
	}

	public function testSetANonNumericValue() {
		$this->setExpectedException('\Alto\Matrix\Exception\InvalidDataException', 'Value is not a numeric');

		$data = [
			[1, 3],
			[2, 5]
		];

		$matrix = new Matrix($data);
		$matrix->setAt(1, 1, 'invalid');
	}

	public function testSetValue() {
		$data = [
			[1, 3],
			[2, 5]
		];

		$matrix = new Matrix($data);
		$matrix->setAt(1, 1, 55);

		$this->assertEquals(55, $matrix->getAt(1, 1));
	}

	public function testGetNumberOfRowsAndColumns() {
		$data = [
			[1, 3],
			[2, 5],
			[50, 30]
		];

		$matrix = new Matrix($data);
		$this->assertEquals(3, $matrix->getNumRows());
		$this->assertEquals(2, $matrix->getNumColumns());
	}

	public function testMultiplyByNumber() {
		$data = [
			[1, 3],
			[2, 5],
			[50, 30]
		];

		$expect = [
			[  2,  6],
			[  4, 10],
			[100, 60]
		];

		$matrix = new Matrix($data);
		$new_matrix = $matrix->multiply(2);

		$this->assertTrue($matrix !== $new_matrix);
		$this->assertEquals($expect, $new_matrix->getData());
	}

}