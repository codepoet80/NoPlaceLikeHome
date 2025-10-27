# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

No Place Like Home is a simple, configurable web-app launcher designed for graceful degradation to support older browsers. It's a single-page PHP application with progressive enhancement features for modern browsers.

Key characteristics:
- Progressive enhancement architecture: Works on old browsers, enhances for modern ones
- Feature detection via JavaScript (XHR detection, touch screen detection)
- Responsive design supporting light/dark modes
- Access control based on visitor IP (local network, VPN, remote)

## Architecture

### Core Request Flow

1. Client requests `index.php`
2. PHP loads `config.php` (user-created from `config-example.php`)
3. PHP includes `functions.php` for utility functions
4. `getAccessLevel()` determines visitor access level based on IP analysis
5. PHP generates HTML with appropriate icons filtered by access level and protocol
6. Client loads `functions.js` and `style.css` with optional cache busting
7. JavaScript progressively enhances UI if modern features detected

### Access Control System

The access control system (implemented in `functions.php:getAccessLevel()`) categorizes visitors into three levels:

- **local**: Same subnet as server, or matching `$localDef` IP patterns
- **vpn**: Matches `$vpnDef` IP patterns (proxy/VPN gateway)
- **remote**: All other visitors

Each launcher icon can specify visibility by access level (single value or array) and protocol (http/https/any). Icons are filtered server-side during rendering.

### Feature Detection Pattern

The codebase uses JavaScript feature detection to progressively enhance:

```javascript
if (typeof detectXHR !== 'undefined' && detectXHR()) {
    // Modern browser enhancements
}
```

Modern browsers get:
- Color extraction from icons using ColorThief library
- Dynamic background colors on icons
- Caption text overlays
- Enhanced search functionality

### Icon Enhancement System

Icons undergo client-side progressive enhancement in `index.php:upgradeIcon()`:
1. Color extracted from icon using ColorThief library
2. Background color calculated via `shadeRGB()` helper
3. Caption added dynamically as text overlay
4. All within feature detection guard for older browsers

## Configuration

### Setup Process

1. Copy `config-example.php` to `config.php`
2. Customize settings and icon definitions
3. Add icon image files to `icons/` directory or reference from other paths

### Key Configuration Variables

- `$useTitle`: Page title
- `$useDomain`: Primary access domain for access level detection
- `$cacheBust`: If true, appends timestamp to CSS/JS to force reload
- `$searchPrefix`: Search engine URL prefix (e.g., "https://www.bing.com/search?q=")
- `$localDef`: Array of IP subnet prefixes for local network detection
- `$vpnDef`: Array of IP addresses/subnets for VPN detection
- `$LauncherIcons`: Array of icon configuration objects

### Icon Configuration Object Structure

```php
(object) [
    'img' => 'path/to/icon.png',           // Relative or absolute
    'caption' => 'Display Name',
    'link' => 'https://destination.url',
    'access' => 'any',                      // 'any', 'local', 'vpn', 'remote', or array
    'protocol' => 'any',                    // 'http', 'https', 'any'
    'altlink' => 'https://alt.url',         // Optional: Experimental icon swap feature
    'altimg' => 'path/to/alt-icon.png'      // Optional: Experimental icon swap feature
]
```

## Key Files

- `index.php`: Main entry point; renders HTML and handles server-side filtering
- `functions.php`: Server-side utilities (IP detection, access level determination, protocol detection)
- `functions.js`: Client-side utilities (search, feature detection, color manipulation, icon swapping)
- `config.php`: User configuration (not in repo, created from example)
- `style.css`: Responsive styles with dark mode support

## JavaScript Feature Detection

The application detects:
- XMLHttpRequest support (`detectXHR()`)
- Touch screen devices (`devicePrimarilyTouchScreen()`)
- eReaders (Kindle/Kobo) to hide search bar

## Search Bar Behavior

Search input supports smart navigation:
- Regular submit: Searches using configured search engine
- URL-like input: Navigates directly to URL
- Ctrl+Enter: Forces navigation (adds www/com if needed)
- Alt/Shift+Enter: Opens in new tab
- Hidden on eReaders
- Auto-focus disabled on touch devices

## Experimental Features

**Icon Swapping**: Alt/Option+click on icons can swap to alternate image/link if `altimg` and `altlink` configured. Implemented in `functions.js:checkIconAlt()`.

## Browser Compatibility

Designed for graceful degradation:
- Old browsers: Basic icon grid with server-side filtering
- Modern browsers: Enhanced icons, color theming, smart search
- Feature detection ensures no breaking on old clients
