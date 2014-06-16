<?php
/*
 * Prepare all the view logic and define variables to be used in the view here at the beginning of the view file.
 * This will make the HTML look cleaner.
 */

$basePath = Application::$basePath;
?>

<p>
    Called <code>index</code> action inside <code>Home</code> controller.
</p>

<p>
    <a href="<?= $basePath ?>/home">
        Invoke <code>Home</code> Controller
    </a><br/><br/>
    <a href="<?= $basePath ?>/home/sampleAction">
        Invoke <code>sampleAction</code> action inside <code>Home</code> controller
    </a><br/><br/>
    <a href="<?= $basePath ?>/home/sampleAction/value1/value2">
        Invoke <code>sampleAction</code> action inside <code>Home</code> controller with parameters
    </a><br/><br/>
    <a href="<?= $basePath ?>/home/sessionDemo">
        <code>Session</code> operations demo
    </a><br/><br/>
    <a href="<?= $basePath ?>/home/hookDemo/val1/val2">
        Hooks demo
    </a><br/><br/>
    <a href="<?= $basePath ?>/home/protectedPage">
        Protected page access demo
    </a>
</p>
