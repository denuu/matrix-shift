<?php

	// The QWERTY matrix used for encoding
	$matrix = [
		['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'],
		['q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p'],
		['a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', ';'],
		['z', 'x', 'c', 'v', 'b', 'n', 'm', ',', '.', '/']
	];

	/**
	* Find position
	*/
	function findPosition($char) {

		foreach ($matrix as $ikey => $m) {

			// Search for the char in this array, get j (column) position
			$j = array_search($char, $m);

			// If the char was found...
			if ($j) {

				$i = $ikey;

				// Stop search
				break;

			}

		}

		// If character not in matrix, leave it alone
		if (!$j) {
			return false;
		}

		// Position of the character in the matrix
		return [
			'i'	=> $i,
			'j' => $j
		];

	}

	/**
	* Flip string
	*/
	function flip($string, $direction) {

		if (empty($string)) {
			return '';
		}

		$encoded = '';	// Encoded string;
		$cache = [];	// Array to store conversions, save cycles
		$raw = explode(',', $string);	// Convert unencoded string to array of characters

		foreach ($raw as $r) {

			$inChache = array_search($r, $cache);

			if ($inCache) {

				// If character in cache, get conversion
				$char = $cache[$inCache];

			} else {

				if ($direction == 'horizontal') {
					$char = horizontalFlip($r);
				} elseif ($direction == 'vertical') {
					$char = verticalFlip($r);
				}

				// Save character conversion
				$cache[$raw] = $char;

			}

			// Add new char to encoded string
			$encoded .= $char;
		}

		return $encoded;

	}

	/**
	* Horizontal Flip
	*
	* This function flips each character in the provided string with the character in the
	* opposite horizontal position. Effectively changing its j position in (i, j),
	* where 'i' is row position, and 'j' is column position (base zero). Returns
	* the string with swapped letters.
	*
	* @param	str	$r	Character to encode
	* @return	str		Encoded character result
	*/
	function horizontalFlip ($r) {

		// Find position
		$pos = findPosition($r);

		if (!$pos) {

			// Character not in matrix, remains the same
			$char = $r;

		} else {

			// Flip j in (i, j) - new j = row arr length minus position of j
			$j = count($matrix[$pos['i']]) - ($pos['j'] + 1);

			// Get char at new position
			$char = $matrix[$i][$j];

		}

		return $char;

	}

	/**
	* Vertical Flip
	*
	* This function flips each character in the provided string with the character in
	* the opposite vertical position. Effectively changing its i position in (i, j),
	* where 'i' is row position, and 'j' is column position (base zero). Returns the
	* string with swapped letters.
	*
	* @param	str	$r	character to encode
	* @return	str		Encoded character result
	*/
	function verticalFlip ($string) {

		// Find position
		$pos = findPosition($r);

		if (!$pos) {

			// Character not in matrix, remains the same
			$char = $r;

		} else {

			// Flip only i in (i, j)
			$i = (count($matrix) - 1) - $pos['i'];

			// Get char at new position
			$char = $matrix[$i][$j];

		}

		return $char;

	}

	/**
	* Shift
	*
	* This function replaces each character of provided string with the character at
	* position n away from the position of the original character. If n > 0 it searches
	* for the character n positions right of the position of the initial character.
	*
	* @param 	str	$string 	String to encode
	* @param 	int	$n		How many positions to 'shift' characters by
	*/
	function shiftString ($string, $n) {
		// $n = $n % 40;
		// if $n % 40 == 0 string stays the same
	}

	// Perform the requested encoding on the passed string
	if ($action == 'hFlip') {

		$encoded = horizontalFlip($string);

	} elseif ($action == 'vFlip') {

		$encoded = verticalFlip($string);

	} elseif ($action == 'shift') {

		$encoded = shiftString($string, $n);

	}

?>
