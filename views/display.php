<?php

if($repos) {

	echo "<ul>";
	foreach($repos as $key => $repo)
	{
	    if($repo->fork != 1)
	    {
	        echo '<li><a href="'.$repo->html_url.'">'.$repo->name.'</a></li>';
	    }
	}
	echo "</ul>";

}

?>