<?php

if (!Settings::pluginGet('forumid')) continue;
if($forum['id'] == Settings::pluginGet('forumid'))
{
	$rndTitles = file_get_contents("./plugins/randomforum/titles.txt");
	$rndTitles = explode("\n", $rndTitles);
	$rndDescs = file_get_contents("./plugins/randomforum/descs.txt");
	$rndDescs = explode("\n", $rndDescs);

	$forum['title'] = $rndTitles[array_rand($rndTitles)];
	$forum['description'] = $rndDescs[array_rand($rndDescs)];
}

?>
