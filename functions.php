<?php
/***
* KEYBOARD TEXT ENCODING
*
* Using a 4 * 10 matrix of keys from a QWERTY keyboard, this program
* encodes provided text by flipping characters horizontally, vertically,
* or shifting them by the provided integer (be it positive or negative).
*
* To run offline (assuming you have PHP installed)
* 1. Open a terminal window in this files directory
* 2. php -S localhost:8000
* 3. Navigate to http://localhost:8000/ in a browser
*
* Denis Nossevitch
* 2017-06-11
**/


	// The QWERTY matrix used for encoding
	const MATRIX = [
		["1", "2", "3", "4", "5", "6", "7", "8", "9", "0"],
		["q", "w", "e", "r", "t", "y", "u", "i", "o", "p"],
		["a", "s", "d", "f", "g", "h", "j", "k", "l", ";"],
		["z", "x", "c", "v", "b", "n", "m", ",", ".", "/"]
	];

	if (isset($_POST)) {
		flip($_POST['string'], $_POST['action'], $_POST['offset']);
	}

	/**
	* Find position
	*
	* This function finds the position of the provided character, if
	* it exists in the matrix. Position is returned as (i, j) in an array.
	* Where i is the row, and j is the column.
	*
	* @param 	string	$char	Character we're searching for
	* @return					If character exists, return position array - else return false
	*/
	function findPosition($char) {

		foreach (MATRIX as $ikey => $m) {

			// Search for the char in this array, get j (column) position
			$j = array_search($char, $m, TRUE);

			// If the char was found...
			if ($j !== false) {

				$i = $ikey;

				// Position of the character in the MATRIX
				return [
					'i'	=> $i,
					'j' => $j
				];

			}
		}

		return false;

	}

	/**
	* Flip string
	*
	* This function manages the string encoding. It accepts the string, the
	* requested encoding method, and an integer with which to perform 'shift'.
	*
	* @param 	string	$string 	Text to be encoded
	* @param 	string 	$direction 	Encoding method to be used
	* @param 	int 	$n 			Integer used for shift method
	* @return 						The encoded text
	*/
	function flip($string, $direction, $n = false) {

		if (empty($string)) {
			return '';
		}

		$string = strtolower($string);	// Just in case, even though we assume lowercase
		$encoded = '';	// Encoded string
		$cache = [];	// Array to store conversions, save cycles
		$raw = str_split($string);	// Convert unencoded string to array of characters
		foreach ($raw as $r) {

			if (isset($cache[$r])) {

				// If character in cache, get conversion
				$char = $cache[$r];

			} else {

				if ($direction == 'horizontal') {
					$char = horizontalFlip($r);
				} elseif ($direction == 'vertical') {
					$char = verticalFlip($r);
				} elseif ($direction == 'shift') {

					if (empty($n)) {
						// make an error;
					}
					$char = shiftString($r, $n);

				}

				// Save character conversion
				$cache[$r] = $char;

			}

			// Add new char to encoded string
			$encoded .= $char;
		}

		echo $encoded;
		return $encoded;

	}

	/**
	* Horizontal Flip
	*
	* This function flips the provided character, with the character in its
	* opposite horizontal position. Effectively changing its j position in (i, j),
	* where 'i' is row position, and 'j' is column position (base zero).
	*
	* @param	str	$r	Character to encode
	* @return	str		Encoded character result
	*/
	function horizontalFlip ($r) {

		// Find position
		$pos = findPosition($r);

		if (!$pos) {

			// Character not in MATRIX, remains the same
			$char = $r;

		} else {

			// Flip j in (i, j) - new j = row arr length minus position of j
			$i = $pos['i'];
			$j = count(MATRIX[$i]) - ($pos['j'] + 1);

			// Get char at new position
			$char = MATRIX[$i][$j];

		}

		return $char;

	}

	/**
	* Vertical Flip
	*
	* This function flips provided characted with the character in the opposite
	* vertical position. Effectively changing its i position in (i, j), where
	* 'i' is row position, and 'j' is column position (base zero).
	*
	* @param	str	$r	character to encode
	* @return	str		Encoded character result
	*/
	function verticalFlip ($r) {

		// Find position
		$pos = findPosition($r);
		$caps = false;

		if (!$pos) {

			// Character not in MATRIX, remains the same
			$char = $r;

		} else {

			// Flip only i in (i, j)
			$j = $pos['j'];
			$i = (count(MATRIX) - 1) - $pos['i'];

			// Get char at new position
			$char = MATRIX[$i][$j];

		}

		return $char;

	}

	/**
	* Shift
	*
	* This function replaces the provided character with the character at
	* position n away from the original character position.
	*
	* @param 	str	$string 	String to encode
	* @param 	int	$n		How many positions to 'shift' characters by
	*/
	function shiftString ($r, $n) {

		// Get position, if character not in matrix return it as is
		$pos = findPosition($r);
		if (!$pos) {
			return $r;
		}

		// Get count of elements in matrix
		$size = 0;
		foreach (MATRIX as $m) {
			$size = $size + count($m);
		}

		// Modulo of size shifts by only what is needed
		$n = $n % $size;

		if ($n == 0) {
			// NOTE: IF THE OFFSET IF 0 OR MULITPLE OF MATRIX SIZE

			// Character remains the same
			return $r;

		} elseif ($n > 0) {

			// NOTE: IF OFFSET IS VALID POSITIVE INTEGER

			// Number of steps to end of row
			$rem = count(MATRIX[$pos['i']]) - ($pos['j'] + 1);

			if ($rem > $n) {

				// Shift contained within this row, j increases by n
				$j = $pos['j'] + $n;
				$char = MATRIX[$pos['i']][$j];

			} elseif ($rem < $n) {

				// Offset left after subtracting steps until end of row
				$n = $n - $rem;

				// Find how many rows we shift by
				$cols = count(MATRIX[$pos['i']]);	// total columns
				$rows = intval($n / $cols);			// how many rows we shift by
				$validRows = ($rows % count(MATRIX)) + 1;	// at least by one row
				if ($n % $cols == 0) {
					$validRows = $validRows - 1;
				}

				// New row position
				$i = (($pos['i'] + $validRows) % 4);

				// How many columns do we shift from [0] in new row?
				if ($n % $cols == 0) {
					$j = $cols - 1;
				} else {
					$j = ($n % $cols) - 1;
				}

				// Shifted character
				// echo '['.$i.']['.$j.']'."\n";
				$char = MATRIX[$i][$j];

			}

		} elseif ($n < 0) {

			// NOTE: IF OFFSET IS VALID NEGATIVE INTEGER

			// Remaining columns to left of character position until start of row
			$rem = $pos['j'];

			if (abs($n) > $rem) {

				// Offset looks for character in another row
				$n = $n + $rem;

				// How many rows do we shift up (looped) by?
				$cols = count(MATRIX[$pos['i']]);	// total columns
				$rows = intval($n / $cols);
				$totalRows = count(MATRIX);			// total rows
				$validRows = ($rows % $totalRows);
				if ($n % $cols !== 0) {
					// nowhere left to shift in row, move to next row
					$validRows = $validRows - 1;
				}


				// New row position
				if ($pos['i'] + $validRows < 0) {

					// If past first row, continue up from last row
					$iShift = $pos['i'] + $validRows;
					$i = count(MATRIX) - abs($iShift);

				} else {

					// regular row calculation
					$i = $pos['i'] - abs($validRows);

				}

				// How many columns do we shift from the last row position?
				if (($n % $cols) !== 0) {
					$lastCol = $cols;
					$jShift = $n % $cols;
					$j = $lastCol + ($n % $cols);	// adding -ve number!
				} else {
					$j = 0;
				}

				// Shifted characted
				$char = MATRIX[$i][$j];


			} elseif (abs($n) <= $rem) {

				// Offset contained within the same row as original character
				$j = $pos['j'] + $n;
				$char = MATRIX[$pos['i']][$j];

			}

		}

		return $char;

	}

?>
