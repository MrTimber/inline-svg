<?php
include dirname(__DIR__).'/src/autoloader.php';

use InlineSvg\Template;
use InlineSvg\Sources\FileSystem;

$svg = new Template(new FileSystem(__DIR__.'/icons', [
    'pad' => 'gaming',
]));
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Demo</title>

		<style type="text/css">
			span {
				display: inline-block;
				border: solid 1px black;
			}
			span svg {
				display: block;
			}
			/* base */
			svg .icon1 {
				fill: black;
			}
			svg .icon2 {
				fill: green;
			}
			svg:hover .icon1 {
				fill: green;
			}

			/* icon-red */
			svg.icon-red path.icon2 {
				fill: red;
			}
			svg.icon-red:hover path.icon1 {
				fill: red;
			}

			/* outros */
			.mini svg {
				width: 32px;
				height: 32px;
			}
			.red svg path.icon1,
			.red svg path.icon2 {
				fill: red;
			}
			.blue svg path.icon1,
			.blue svg path.icon2 {
				fill: blue;
			}
			.yellow-red svg path.icon1 {
				fill: yellow;
			}
			.yellow-red svg path.icon2 {
				fill: red;
			}
		</style>
	</head>
	<body>

	<?= $svg->get('days')->withAttributes([
        'class' => 'icon-red',
        'width' => 20,
        'height' => 20,
    ])
    ?>

	<span class="blue">
	blue
	<?= $svg->get('pad'); ?>
	</span>

	<span class="red">
	red
	<?= $svg->get('pad'); ?>
	</span>

	<span class="mini">
	mini
	<?= $svg->get('pad'); ?>
	</span>

	<span class="yellow-red">
	yellow-red
	<?= $svg->get('pad'); ?>
	</span>

	<?= $svg->getTemplate(); ?>

	</body>
</html>