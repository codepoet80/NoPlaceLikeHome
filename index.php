<?php
    include("config.php");
    include("functions.php");
    $accessLevel = getAccessLevel($localDef, $vpnDef, $useDomain);
    $cacheId = "";
    if ($cacheBust)
        $cacheId = time();
?>
<html>
<head>
    <title><?php echo $useTitle; ?></title>
    <link rel="stylesheet" href="style.css?<?php echo $cacheId; ?>">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/color-thief/2.3.0/color-thief.umd.js"></script>
    <script src="functions.js?<?php echo $cacheId; ?>"></script>
    <script>
        var searchPrefix = "<?php echo($searchPrefix); ?>";
        var colorThief = new ColorThief();
        function upgradeIcon(img) {
            if (typeof detectXHR !== 'undefined' && detectXHR()) {
                img.insertAdjacentHTML("afterend", "<span class='caption'>" + img.title + "</span>")
                //Set icon background color
                var useColor = colorThief.getColor(img)+"";
                useColor = useColor.split(",")
                useColor = shadeRGB(useColor[0],useColor[1],useColor[2]);
                img.style.backgroundColor = "rgb(" + useColor + ", 0.6)";
            }
        }
    </script>
    <meta name='viewport' content='height=device-height'>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
</head>
<body>
<table width=100% height=100% border=0 cellpadding=0 cellspacing=0>
    <tr>
        <td width=100% height=100% align="center" valign="middle">
            <form action="http://www.frogfind.com" onsubmit="event.preventDefault(); submitSearch();">
                <div class="searchBar" id="searchBar">
                    <input id="searchQueryInput" type="text" name="q" placeholder="Search" value="" onload="initSearch()" onkeypress="return keyChecker(event)" />
                    <button id="searchQuerySubmit" type="submit">
                    <svg style="width:24px;height:24px;" viewBox="0 0 28 12"><path fill="#666666" d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z" />
                    </svg>
                    </button>
                </div>
            </form>
            <div id="container">
                <!-- icon grid -->
<?php
    $iconCount = 1;
    foreach ($LauncherIcons as $LauncherIcon) {
        if (($LauncherIcon->access == "any" || $LauncherIcon->access == $accessLevel || (is_array($LauncherIcon->access) && in_array($accessLevel, $LauncherIcon->access))) &&
            ($LauncherIcon->protocol == "any" || $LauncherIcon->protocol == getProtocol())
        ) {
            echo "<span class='item' id='item$iconCount' onmousedown='checkIconAlt(event, this)'";
            if (isset($LauncherIcon->altlink))
		echo " altlink='" . htmlspecialchars($LauncherIcon->altlink, ENT_QUOTES, 'UTF-8') . "'";
            if (isset($LauncherIcon->altimg))
		echo " altimg='" . htmlspecialchars($LauncherIcon->altimg, ENT_QUOTES, 'UTF-8') . "'";
	    echo ">";
            echo "<a id='itemLink$iconCount' href='" . htmlspecialchars($LauncherIcon->link, ENT_QUOTES, 'UTF-8') . "'>";
            echo "<img src='" . htmlspecialchars($LauncherIcon->img, ENT_QUOTES, 'UTF-8') . "' id='icon$iconCount' class='icon' onload='upgradeIcon(this)' onerror='this.onerror = null; this.src=\"./oops.png\"' title='" . htmlspecialchars($LauncherIcon->caption, ENT_QUOTES, 'UTF-8') . "' width='64' border='0'/>";
            echo "</a>";
            echo "</span>\r\n";
        }
        $iconCount++;
    }
?>
            </div>
            <div class="footer">Your IP has been logged as: <?php echo htmlspecialchars(getUserIpAddr(), ENT_QUOTES, 'UTF-8') . ". Your access level is: " . htmlspecialchars($accessLevel, ENT_QUOTES, 'UTF-8') ?></div>
        </td>
    </tr>
</table>
</body>
</html>
