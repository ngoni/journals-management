<?php

	require( WP_PLUGIN_DIR . "/IGUJournalManagerdd/forms/homepage.php" );
	
	if( $journalList['journals'] === null){;}//row not found nothing to display try and display erro message data not found ??does not make sense
	else{
		$print = 
		'<div>
			<h2 align="center">' . $journalList['pageTitle'] . '</h2>
		</div><hr />

		<div id="errors"></div>
		<div id="response_area"></div>
		<div>
			<form onsubmit="validate();return;" action="options-general.php?page=IGUJournalManager&amp;action=' . $journalList['formAction'] . '" method="post">
				<table id="edicolumnable" class="edicolumnable">';
				
				foreach( $journalList['journals'] as $jnl ){
					foreach( $jnl as $column => $value ){
						switch($column){
							case "id":
								$print .= '<input type="hidden" id='.$column.' name='.$column.' value="'.$value.'" />';
							break;
							case "print_issn":
								$print .= '<tr><td><label for='.$column.'><strong>'.$column.'</strong></label></td>';
								$print .= '<td><input type="text" id='.$column.' size="8" name='.$column.' value="'.$value.'" /></td></tr>';
							break;
							case "e_issn":
								$print .= '<tr><td><label for='.$column.'><strong>'.$column.'</strong></label></td>';
								$print .= '<td><input type="text" id='.$column.' size="8" name='.$column.' value="'.$value.'" /></td></tr>';
							break;
							case "since":
								$print .= '<tr><td><label for='.$column.'><strong>'.$column.'</strong></label></td>';
								$print .= '<td><input type="text" id='.$column.' size="4" name='.$column.' value="'.$value.'" /></td></tr>';
							break;
							case "isi":
								$print .= '<tr><td><label for='.$column.'><strong>'.$column.'</strong></label></td>';
								$print .= '<td><input type="text" id='.$column.' size="4" name='.$column.' value="'.$value.'" /></td></tr>';
							break;
							default:
								$print .= '<tr><td><label for='.$column.'><strong>'.$column.'</strong></label></td>';
								$print .= '<td><input type="text" id='.$column.' size="50" name='.$column.' value="'.$value.'" /></td></tr>';
							break;
						}
					}
				}
				$print .=
				'</table><div>
					<input type="submit" name="saveChanges" value="Save Changes" />
				</div>
			</form>
		<div>';
		
		echo $print;
	}
?>
