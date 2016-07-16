<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>HTMLiveCode</title>
    <base href="<?= ROOT; ?>/assets/">

    <link href="css/HTMLiveCode.css" rel="stylesheet" />
    <link href="js/codemirror/codemirror.css" rel="stylesheet" />
    <link href="js/codemirror/theme/bright.css" rel="stylesheet" />
    <link href="js/codemirror/theme/dark.css" rel="stylesheet" />
    <link href="favicon.ico" rel="shortcut icon" />

    <script src="js/codemirror/codemirror.js"></script>
    <script src="js/codemirror/xml.js"></script>
    <script src="js/codemirror/javascript.js"></script>
    <script src="js/codemirror/css.js"></script>
    <script src="js/codemirror/htmlmixed.js"></script>
    <script src="js/codemirror/foldcode.js"></script>
    <script src="js/HTMLiveCode.js"></script>
    <script src="js/jslint.min.js"></script>
    <script src="js/template.js"></script>

    <script type="text/javascript">
        window.addEventListener("DOMContentLoaded", function() {
            window.liveEditor = new HTMLiveCode();
            window.liveEditor.init();
        });
    </script>
</head>

<body>
<div id="menu-bar">
    <div id="menu-column-1">
        <div class="menu-button-first dropdown-button" id="btn-dropdown-settings">
            <span>Settings</span>
            <div class="dropdown" id="dropdown-settings">
                <div class="menu-label">Theme</div>
                <div class="menu-button-active" id="btn-style-bright">Bright</div>
                <div class="menu-button" id="btn-style-dark">Dark</div>
                <div class="dropdown-separator"></div>
                <div class="menu-label">Font size</div>
                <div class="menu-button" id="btn-fontsize-increase">A+</div>
                <div class="menu-button" id="btn-fontsize-decrease">A-</div>
                <div class="menu-button" id="btn-fontsize-reset">A<sup>&oslash;</sup></div>
                <div class="dropdown-separator"></div>
                <div class="menu-label">Options</div>
                <div class="menu-button-active" id="btn-options-gutter">Gutter</div>
                <div class="menu-button-active" id="btn-options-wordwrap">Word wrap</div>
                <div class="dropdown-separator"></div>
                <div class="menu-label">Shortcuts</div>
                <p class="labeltext">
                    <span class="shortcut">ALT + M</span><span>Toggle menu bar</span>
                    <span class="shortcut">ALT + T</span><span>Toggle theme</span>
                    <span class="shortcut">ALT + I</span><span>Increase font size</span>
                    <span class="shortcut">ALT + O</span><span>Decrease font size</span>
                    <span class="shortcut">ALT + 0</span><span>Reset font size</span>
                    <span class="shortcut">ALT + G</span><span>Toggle gutter</span>
                    <span class="shortcut last">ALT + W</span><span class="last">Toggle word wrap</span>
                </p>
            </div>
        </div>
        <div class="menu-separator"></div>
        <div class="menu-label" id="label-imagepath">Image proxy path</div>
        <input class="menu-input" id="txt-imagepath" value="" title="ONLY WORKS WHEN HTMLIVECODE IS USED LOCALLY: Use this path to point to local image files (works for 'src' attribute of images and CSS 'background-image' urls). Don't use quotes here." />
    </div>
    <div id="menu-column-2">
        <div class="menu-separator"></div>
        <div class="menu-label">Reset</div>
        <div class="menu-button" id="btn-reset-code">Code</div>
        <div class="menu-button" id="btn-reset-settings">Settings</div>
        <div class="menu-separator"></div>
        <div class="menu-button-single dropdown-button" id="btn-dropdown-othertools">
            <span>Other tools</span>
            <div class="dropdown" id="dropdown-othertools">
                <p>There are other tools which are built on top of <strong>HTMLiveCode</strong>. Click the links below to switch to an other tool.</p>
                <p><a href="/canvas-animation-playground" class="menu-button" id="btn-style-darks">Canvas Animation Playground</a></p>
            </div>
        </div>
        <div class="menu-separator"></div>
        <div class="menu-button-single dropdown-button" id="btn-dropdown-info">
            <span>?</span>
            <div class="dropdown" id="dropdown-info">
                <p><strong>HTMLiveCode</strong> is a real-time HTML/CSS/JavaScript code editor which is made for rapid prototyping.</p>
                <p>Click on the gutter to fold a tag range in and out.</p>
                <p>The editor makes use of HTML5's localStorage and has been optimized for modern browsers. Internet Explorer is supported from version 9 on.</p>
                <p class="last-paragraph">The project was developed by <a href="http://matthiasschuetz.com">Matthias Schuetz</a> and is available on <a href="https://github.com/matthias-schuetz/HTMLiveCode">GitHub</a>.</p>
            </div>
        </div>
    </div>
    <div id="logo"></div>
</div>
<div id="imagepath-tooltip" class="tooltip">
    <div id="imagepath-tooltip-arrow" class="tooltip-arrow"></div>
    <p>ONLY WORKS WHEN HTMLIVECODE IS USED LOCALLY: Use this path to point to local image files (works for "src" attribute of images and CSS "background-image" urls). Don't use quotes here.</p>
    <p>For example if you enter "C:/Path/" and you set an "src" attribute to "button.png", HTMLiveCode will virtually change the "src" attribute to "file:///C:/Path/button.png".</p>
</div>
<div id="intro-tooltip" class="tooltip">
    <div id="intro-tooltip-arrow" class="tooltip-arrow"></div>
    Move your cursor over the menu button to show the menu bar.
</div>
</body>
</html>