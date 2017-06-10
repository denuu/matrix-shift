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
	function findPosition($string) {

		foreach ($matrix as $ikey => $m) {

			// Search for the char in this array, get j (column) position
			$j = array_search($r, $m);

			// If the char was found...
			if ($j) {

				$i = $ikey;

				// Stop search
				break;

			}

		}

		if (!$j) {
			return false;
		}

		return [$i, $j];

	}

	/**
	* Horizontal Flip
	*
	* This function flips each character in the provided string with the character in the
	* opposite horizontal position. Effectively changing its j position in (i, j),
	* where 'i' is row position, and 'j' is column position (base zero). Returns
	* the string with swapped letters.
	*
	* @param	str	$string		String to encode
	* @return	str			Encoded string result
	*/
	function horizontalFlip ($string) {

		// Save character conversions
		$cache = [];

		// Convert string into array of characters
		$raw = explode(',', $string);

		foreach ($raw as $r) {

			$pos = findPosition($r);

			// Flip only j in (i, j)
			$j = count($m) - ($j + 1);

			// Get char at new position
			$char = $matrix[$i][$j];
		}

	}

	/**
	* Vertical Flip
	*
	* This function flips each character in the provided string with the character in
	* the opposite vertical position. Effectively changing its i position in (i, j),
	* where 'i' is row position, and 'j' is column position (base zero). Returns the
	* string with swapped letters.
	*
	* @param	str	$string		String to encode
	* @return	str			Encoded string result
	*/
	function verticalFlip ($string) {

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
