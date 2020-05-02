<?php
$currentWorkingDirectory = getcwd();

$loadedPlugins  = array();
$defaultWorkerTimeout = 2;
$currentWorkerTimeout = $defaultWorkerTimeout;
function loadPluginFrom(string $directoryPath) {
    global $currentWorkingDirectory, $loadedPlugins;
    chdir($directoryPath);
    $manifestFile = fopen("manifest.json","r");
    $manifestObject = json_decode(fread($manifestFile, filesize("manifest.json")), true);
    $manifestObject["modulePath"] = $directoryPath;
    if(isset($manifestObject["name"]) && isset($manifestObject["actions"]))$loadedPlugins[$manifestObject["name"]] = $manifestObject;
    //Return to working dir

    chdir($currentWorkingDirectory);
    return $manifestObject;
}

function runPluginAction($pluginObject, string $action, string $data = NULL): ?string {
    global $currentWorkerTimeout;
    chdir($pluginObject["modulePath"]);
    $startTime = microtime(true);
    $processOutput = NULL;
    try {
        $actionCmd = $pluginObject["actions"][$action];

        $descriptor = array(
            0 => array('pipe', 'r'),
            1 => array('pipe', 'w'),
            2 => array('pipe', 'w')
        );
        $pipes = array();
        $process = proc_open($actionCmd, $descriptor, $pipes);
        if (is_resource($process)) {
            $processOutput = "";
            if($data !== NULL)fputs($pipes[0], $data. "\n");
            else fputs($pipes[0], "\n");
            stream_set_timeout($pipes[1], 0);
            stream_set_timeout($pipes[2], 0);
            $ws = $es = NULL;


            while (proc_get_status($process)["running"])
            {
                $rs = array($pipes[1]);
                if($num_changed_streams = stream_select($rs,$ws, $es, 1)){
                    if(!empty($rs))$processOutput .= fread($pipes[1],512);
                    else if (microtime(true)-$startTime > $currentWorkerTimeout) {proc_terminate($process, 9);break;}
                }
                else {
                    if (microtime(true)-$startTime > $currentWorkerTimeout) {proc_terminate($process, 9);break;}
                }
            }

            fclose($pipes[1]);
            fclose($pipes[2]);


            proc_close($process);
        }

    }
    catch (Exception $e) {
        echo "Caught exception while executing " . $action . " from " . $pluginObject["name"];
    }
    global $currentWorkingDirectory;
    chdir($currentWorkingDirectory);
    return trim($processOutput, " \t\n\r\0\x0B");
}

//tests
/*$base = loadPluginFrom("plugins/base/");
$getString = runPluginAction($base, "get-example");
$putString = runPluginAction($base, "put-get-example", "14");
assert($getString == "<h1>Gotten</h1>", "Get string checked");
assert($putString == "<h1>14</h1>", "Put string checked");*/

function verifiedExecution(&$username, $pluginName, $action, $data = NULL) {
    if(true) {
        global $loadedPlugins;
        $plugin = NULL;
        if(isset($loadedPlugins[$pluginName])){
            $plugin = $loadedPlugins[$pluginName];
        }
        if($data === NULL) {
            return runPluginAction($plugin, $action);
        }
        else {
            return runPluginAction($plugin, $action, $data);
        }
    }
}