<?php
include dirname(__DIR__).'/src/autoloader.php';

use InlineSvg\Collection;
use InlineSvg\Transformers\Cleaner;

$icons = Collection::fromPath(__DIR__.'/icons', ['pad' => 'gaming']);
$icons->addTransformer(new Cleaner());
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Demo</title>

		<style type="text/css">
			svg .icon1 {
				fill: black;
			}
			svg .icon2 {
				fill: green;
			}
			svg.icon-red path.icon2 {
				fill: red;
			}

			svg:hover .icon1 {
				fill: green;
			}
			svg.icon-red:hover path.icon1 {
				fill: red;
			}
		</style>
	</head>
	<body>

	<?= $icons->get('days')->withAttributes([
        'class' => 'icon-red',
        'width' => 20,
        'height' => 20,
    ])->withA11y();
    ?>

	<?= $icons->get('pad')->withA11y(); ?>
	</body>
</html>