<?php 
	require( WP_PLUGIN_DIR . "/IGUJournalManagerdd/forms/homepage.php" );

	$print = 
	'<div>
		<h2 align="center">' . $journalList['pageTitle'] . '</h2>
		</div><hr />

	<form action="options-general.php?page=IGUJournalManager&amp;action=upload" id="uploadForm" method="post" enctype="multipart/form-data">
		<div class="control-group">
			<p>
				<label class="control-label" for="csv_file">Select CSV File to Upload</label>
			</p>
			<p>
			<div class="controls">
				<input type="hidden" name="uploadForm" value="submit">
				<input class="input-file" name="csv_file" type="file">
				<input type="submit" id="uploadFormButton" value="Upload File" />
			</div>
			</p>
		</div>
	</form>';
	
	echo $print;

?>
