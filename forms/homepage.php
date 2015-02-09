<?php

	echo '

	<div class="navbar">
    <div class="navbar-inner">
      <div class="container">
        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
        <a class="brand">IGU Journals Manager</a>
        <div class="nav-collapse">
 	<ul class="nav">
	<li><a href="options-general.php?page=IGUJournalManager&amp;action=view">View Journals </a></li>
	<li><a href="options-general.php?page=IGUJournalManager&amp;action=new">Add New Journals </a></li>
	</ul>
          <form id="navform" class="navbar-search pull-right" action="?page=IGUJournalManager&amp;action=search" method="post">
            <input type="text" id="search" name="search" class="search-query span2" placeholder="Search" />
		<select id="filter" class="navbar-form-select" name="filter" style="margin: 0px; padding: 0px; height: 22px;">
			<option value="all">All</option>
			<option value="name_of_journal">Journal Name</option>
			<option value="country">Country</option>
			<option value="issn">ISSN</option>
			<option value="city_of_publication">City Published</option>
			<option value="editor">Editor</option>
			<option value="language">Language</option>
			<option value="isi_category">ISI Category</option>
		</select>
		<input type="submit" name="submit" value="Search" />
          </form>
        </div><!-- /.nav-collapse -->
      </div>
    </div><!-- /navbar-inner -->
  </div><!-- /navbar -->

	';
?>
