<?php
	/** @var mixed $schemaType */
	/** @var mixed $headContents */
	/** @var mixed $bodyContents */
?>
<!DOCTYPE html>
<html lang="en" itemscope itemtype="https://schema.org/<?= $schemaType; ?>">
	<head>
		<?= $headContents; ?>
	</head>
	<body>
		<?= $bodyContents; ?>
	</body>
</html>
