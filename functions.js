function detectXHR() {
    if (typeof new XMLHttpRequest().responseType === 'string' || typeof new XMLHttpRequest() === 'object') {
        try {
            var xhr = new XMLHttpRequest();
            return true;
        } catch (ex) {
            return false;
        }
    }
    return false;
}

/* Search Bar */
document.onreadystatechange = function () {
    if (document.readyState == "complete") {
        initSearch();
    }
}
function initSearch() {
    var searchBar = document.getElementById('searchBar');
    var searchBox = document.getElementById('searchQueryInput');
    if( /Kindle|Kobo/i.test(navigator.userAgent) ) {
        //Don't show search on eReaders
        searchBar.style.display = 'none';
    } else {
        searchBar.style.display = 'inline';
        searchBox.value = '';
        if(!devicePrimarilyTouchScreen())
            document.getElementById('searchQueryInput').focus();
        else
            document.getElementById('searchQueryInput').blur();
    }
}
function submitSearch(e) {
    if (location.protocol == 'https' || detectXHR()) {
        doNavigate(null, false);
        return false;
    } else {
        console.log("searching with retro search!");
        return true;
    }
}
function doNavigate(e, force)
{
    var searchText = document.getElementById("searchQueryInput").value;
    if (searchText != "")
    {
        var isURL = urlChecker(searchText);
        if (isURL || force || (e != null && e.ctrlKey))
        {
            if (!isURL)
                searchText = "http://www." + searchText + ".com";
            if (searchText.indexOf("http://") == -1 && searchText.indexOf("https://") == -1)
                searchText = "http://" + searchText;
            document.getElementById("searchQueryInput").value = searchText;
        }
        else
            searchText = searchPrefix + encodeURIComponent(searchText);

        if (e != null && (e.altKey || e.shiftKey))
            window.open(searchText, "_blank");
        else {
            console.log("search submit navigating to: " + searchText);
            document.location = searchText;
        }
    }
    else
    {
        document.getElementById("searchQueryInput").focus();
    }
}
function keyChecker(e){
    var keynum;
    if(window.event)
    { // IE
        keynum = e.keyCode;
    } else if(e.which)
    { // Netscape/Firefox/Opera
        keynum = e.which;
    }
    if (keynum == 13 || keynum == 10)
    {
        if(e.ctrlKey)
            doNavigate(e, true);
        else
            doNavigate(e);
    }
}
function urlChecker(value)
{
    if (!value || typeof value !== 'string') return false;

    value = value.trim().toLowerCase();

    // Protocol check
    if (/^(https?|ftp|file):\/\//.test(value)) {
        return true;
    }

    // localhost or IP address
    if (value.startsWith('localhost') || /^\d{1,3}\.\d{1,3}/.test(value)) {
        return true;
    }

    // www prefix
    if (value.startsWith('www.')) {
        return true;
    }

    // Domain pattern: has dot, not at start/end, followed by 2+ letter TLD
    // Matches: "example.com", "sub.domain.io", "site.co.uk"
    // Avoids: "info about", ".hidden", "file.txt"
    if (/^[a-z0-9][-a-z0-9]*\.[a-z0-9][-a-z0-9.]*\.[a-z]{2,}$/i.test(value) ||
        /^[a-z0-9][-a-z0-9]*\.[a-z]{2,}$/i.test(value)) {
        return true;
    }

    return false;
}
function devicePrimarilyTouchScreen() {
    var touchscreen = false;
    //if it has a touchscreen
    if ('ontouchstart' in document.documentElement) {
        touchscreen = true;
    }
    //unless its Windows
    if (window.navigator.userAgent.indexOf("Windows")!= -1) {
        touchscreen = false;
    }
    //but definitely if its one of these
    if( /Android|webOS|iPhone|iPad|iPod|Kindle|Kobo|Opera Mini/i.test(navigator.userAgent) ) {
        touchscreen = true;
    }
    return touchscreen;
}

/* Colors */
function shadeRGB(r, g, b) {
    return (((r/0.95)+10) + "," + ((g/0.95)+10) + "," + ((b/0.95)+10));
}
function complementryRGBColor(r, g, b) {
    if (Math.max(r, g, b) == Math.min(r, g, b)) {
        return [255 - r, 255 - g, 255 - b];

    } else {
        r /= 255, g /= 255, b /= 255;
        var max = Math.max(r, g, b), min = Math.min(r, g, b);
        var h, s, l = (max + min) / 2;
        var d = max - min;
        s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
    
        switch (max) {
            case r: h = (g - b) / d + (g < b ? 6 : 0); break;
            case g: h = (b - r) / d + 2; break;
            case b: h = (r - g) / d + 4; break;
        }
    
        h = Math.round((h*60) + 180) % 360;
        h /= 360;
        
        function hue2rgb(p, q, t) {
            if (t < 0) t += 1;
            if (t > 1) t -= 1;
            if (t < 1/6) return p + (q - p) * 6 * t;
            if (t < 1/2) return q;
            if (t < 2/3) return p + (q - p) * (2/3 - t) * 6;
            return p;
        }
    
        var q = l < 0.5 ? l * (1 + s) : l + s - l * s;
        var p = 2 * l - q;
    
        r = hue2rgb(p, q, h + 1/3);
        g = hue2rgb(p, q, h);
        b = hue2rgb(p, q, h - 1/3);

        return [Math.round(r*255), Math.round(g*255), Math.round(b*255)];
    }
}

/* Icons */
function checkIconAlt(event, sender) {
    if (event.altKey && sender.getAttribute("altlink")) {

        var link = document.getElementById(sender.id.replace("item", "itemLink"));
        var newLink = sender.getAttribute("altlink");
        sender.setAttribute("altlink", link.href);
        link.href = newLink;

        var icon = document.getElementById(sender.id.replace("item", "icon"));
        if (sender.getAttribute("altimg")) {
            var newIcon = sender.getAttribute("altimg");
            sender.setAttribute("altimg", icon.src);
            icon.src = newIcon;
        }

        icon.title= "";
        event.stopPropagation();
    }
}
