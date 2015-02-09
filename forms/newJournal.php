<?php
	require( WP_PLUGIN_DIR . "/journals-management/forms/homepage.php" );

	$print = 
	'<div>
		<h2 align="center">' . $journalList['pageTitle'] . '</h2>
	</div><hr />
<div id="errors"></div>
<div id="response_area"></div>
	<div>
		<form id="newForm" action="options-general.php?page=IGUJournalManager&amp;action=' . $journalList['formAction'] . '" method="post">
			<table class="editTables">';
			$print .= '<label><italic>*required</italic></label>';
			foreach( $journalList['columnHeadings'] as $column => $value ){
				switch($value){
					case "id":
						;//$print .= '<input type="hidden" id='.$value.' name='.$value.'  />';
					break;
					case "print_issn":
						$print .= '<tr><td><label for='.$value.'><strong>Print ISSN</strong></label></td>';
						$print .= '<td><input type="text" id='.$value.' size="9" maxlength="8" name='.$value.' /></td></tr>';
					break;
					case "name_of_journal":
						$print .= '<tr><td><label for='.$value.'><strong>Name of Journal *</strong></label></td>';
						$print .= '<td><input type="text" id='.$value.' size="50" name='.$value.' /></td></tr>';
					break;
					case "e_issn":
						$print .= '<tr><td><label for='.$value.'><strong>e ISSN</strong></label></td>';
						$print .= '<td><input type="text" id='.$value.' size="9" maxlength="8" name='.$value.' /></td></tr>';
					break;
					case "since":
						$print .= '<tr><td><label for='.$value.'><strong>Since</strong></label></td>';
						$print .= '<td><input type="text" id='.$value.' size="5" maxlength="4" name='.$value.' /></td></tr>';
					break;
					case "isi":
						$print .= '<tr><td><label for='.$value.'><strong>ISI</strong></label></td>';
						$print .= '<td><select id='.$value.'><option value="1">Yes</option><option value="0">No</option></select></td></tr>';
					break;
					default:
						$val = ucwords( str_replace( "_", " ", $value ) );
						$print .= '<tr><td><label for='.$value.'><strong>'.$val.'</strong></label></td>';
						$print .= '<td><input type="text" id='.$value.' size="50" name='.$value.' /></td></tr>';
					break;
				}
			}
			$print .=
			'</table>
			<div>
				<input type="submit" value="Save Changes" name="saveChanges" />
			</div>
		</form>
	<div>';
	
	echo $print;
?>
