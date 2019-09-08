<?php

$plugins_spec = getenv('TTRSS_PLUGINS');

if (empty($plugins_spec)) {
    exit;
}

echo "Adding custom plugins...\n";

function git($args) {
    passthru("git $args 2>&1", $ret_val);
    return $ret_val;
}

$plugins = [];
foreach (explode(',', $plugins_spec) as $plugin_spec) {
    $plugin_spec = trim($plugin_spec);
    if (empty($plugin_spec)) {
        continue;
    }

    if (preg_match('/^([^:]+):([^#]+)(#(.+))?$/', $plugin_spec, $matches)) {
        $name = $matches[1];
        $repo_url = $matches[2];
        $repo_branch = $matches[4] ?? '';
        echo "Installing '$name'...\n";
        $branch_argument = $repo_branch ? "--branch '$repo_branch'" : '';
        $ret_val = git("clone $branch_argument --depth 1 --recurse-submodules '$repo_url' 'tt-rss/plugins.local/$name'");
        if ($ret_val != 0) {
            exit($ret_val);
        }
    } else {
        echo "Error parsing '$plugin_spec'\n";
        exit(1);
    }
}
