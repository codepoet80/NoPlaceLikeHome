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
    document.getElementById('searchQueryInput').visibility = 'visible';
    document.getElementById('searchQueryInput').value = ''; 
    document.getElementById('searchQueryInput').focus();
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
            if (searchText.indexOf("http://") == -1 && searchText.indexOf("https://"))
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
    var urlHints = ['www.', '.com', '.net', '.org', '.tv', '.info', 'http:', 'https:', 'ftp:'];
    for (var i = 0; i < urlHints.length; i++) {
        if (value.indexOf(urlHints[i]) > -1) {
            return true;
        }
    }
    return false;
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