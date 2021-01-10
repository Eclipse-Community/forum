<?php

if($forum['id'] == Settings::pluginGet('forumid'))
{
	$rndDescs = file_get_contents("./plugins/randomforum/descs.txt");
	$rndDescs = explode("\n", $rndDescs);

	$forum['title'] = $rndTitles[array_rand($rndTitles)];
	$forum['description'] = $rndDescs[array_rand($rndDescs)];
}

?>
