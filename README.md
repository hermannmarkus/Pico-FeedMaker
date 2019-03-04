# Pico-FeedMaker

A basic feed plugin for Pico. Generates a RSS feed of all pages with a date. The plugin is based on:

* [Pico-RSS-Plugin](https://github.com/gilbitron/Pico-RSS-Plugin)
* [Pico_Sitemap](https://github.com/DaveKin/Pico_Sitemap)
* [Pico_Dummy Plugin](https://github.com/picocms/Pico/blob/master/plugins/DummyPlugin.php)

## About

I [forked](https://github.com/MattByName/Pico-RssMaker) the plugin from [Matt Barnard](https://github.com/MattByName). The goal is to provide a full valid RSS Feed and to make the plugin extensible.

# Installation

## With git

1. Go to your Pico Plugins folder
2. `git clone https://github.com/hermannmarkus/Pico-FeedMaker.git FeedMaker`
3. Add the following lines to your config.yaml:
- `FeedMaker.enabled: true`
- `FeedMaker.site_description: $description`
4. Replace `$description` with the description of your feed.
5. Your RSS feed URL will be example.com/?feed.rss

## Without git

1. Download [the master branch](https://github.com/hermannmarkus/Pico-FeedMaker/archive/master.zip) as a zip file.
2. Add the contents of the zip file to your Pico plugins folder and rename the folder to `FeedMaker`.
3. Add the following lines to your config.yaml:
- `FeedMaker.enabled: true`
- `FeedMaker.site_description: $description`
4. Replace `$description` with the description of your feed.
5. Your RSS feed URL will be example.com/?feed.rss

# Compatibility
I've not extensively tested the plugin, but it's worked fine on the following installations.

* 2.0.4
