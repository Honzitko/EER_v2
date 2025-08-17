			$orientation = strtoupper($orientation[0]);
			if ($file[0] === '*') {
				switch (strtoupper($fitbox[1])) {
				switch (strtoupper($fitbox[0])) {
			define('PHP_VERSION_ID', (($version[0] * 10000) + ($version[2] * 100) + $version[4]));
			define('PHP_VERSION_ID', (($version[0] * 10000) + ($version[2] * 100) + $version[4]));
			if ((ord($icc[36]) != 0x61) OR (ord($icc[37]) != 0x63) OR (ord($icc[38]) != 0x73) OR (ord($icc[39]) != 0x70)) {
					$trns = array(ord($t[1]));
					$trns = array(ord($t[1]), ord($t[3]), ord($t[5]));
		if ($dest[0] != 'F') {
					$header = (ord($font[0]) == 128);
		if ($color[0] != '#') {
		if ($file[0] === '@') { // image from string
			if (($line == '') OR ($line[0] == '%')) {
					switch ($attrib[0]) {
							if ($attrib[1] == ':') { // pseudo-element
					if ($element[0] == '/') {
				if ($element[0] == '/') {
							if (strtolower($dom[$key]['style']['font-weight'][0]) == 'n') {
							} elseif (strtolower($dom[$key]['style']['font-weight'][0]) == 'b') {
						if (isset($dom[$key]['style']['font-style']) AND (strtolower($dom[$key]['style']['font-style'][0]) == 'i')) {
									if ($dec[0] == 'u') {
									} elseif ($dec[0] == 'l') {
									} elseif ($dec[0] == 'o') {
							$dom[$key]['align'] = strtoupper($dom[$key]['style']['text-align'][0]);
								if ($dom[$key]['attribute']['size'][0] == '+') {
								} elseif ($dom[$key]['attribute']['size'][0] == '-') {
					if (($dom[$key]['value'][0] == 'h') AND (intval($dom[$key]['value'][1]) > 0) AND (intval($dom[$key]['value'][1]) < 7)) {
							$headsize = (4 - intval($dom[$key]['value'][1])) * 2;
						$dom[$key]['align'] = strtoupper($dom[$key]['attribute']['align'][0]);
					if ($tag['attribute']['src'][0] === '@') {
						if ($imglink[0] == '#') {
		if ($file[0] === '@') { // image from string
						if (!$this->empty_string($this->svgdir) AND (($img[0] == '.') OR (basename($img) == $img))) {
