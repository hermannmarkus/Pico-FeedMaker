# Pico-FeedMaker

A basic feed plugin for Pico. Generates several feeds of all pages with a date. The plugin is based on:

- [Pico-RSS-Plugin](https://github.com/gilbitron/Pico-RSS-Plugin)
- [Pico_Sitemap](https://github.com/DaveKin/Pico_Sitemap)
- [Pico_Dummy Plugin](https://github.com/picocms/Pico/blob/master/plugins/DummyPlugin.php)

## About

I [forked](https://github.com/MattByName/Pico-RssMaker) the plugin from [Matt Barnard](https://github.com/MattByName). The goal is to provide a full valid RSS Feed and to make the plugin extensible.

Right now the plugin generates three kindes of feeds:

- A RSS feed at `domain.tld/?feed.rss`
- A JSON feed at `domain.tld/?feed.json`
- A Microblog JSON feed at `domain.tld/?microblog.json`

Pages with the template _microblog_ are added to a special json feed. Those posts are available so you can add them for your [micro.blog](https://micro.blog).

# Installation

## With git

1. Go to your Pico Plugins folder
2. `git clone https://github.com/hermannmarkus/Pico-FeedMaker.git FeedMaker`
3. Add the following lines to your config.yaml:
- `FeedMaker.enabled: true`
- `FeedMaker.site_description: $description`
4. Replace `$description` with the description of your feed.

## Without git

1. Download [the master branch](https://github.com/hermannmarkus/Pico-FeedMaker/archive/master.zip) as a zip file.
2. Add the contents of the zip file to your Pico plugins folder and rename the folder to `FeedMaker`.
3. Add the following lines to your config.yaml:
- `FeedMaker.enabled: true`
- `FeedMaker.site_description: $description`
4. Replace `$description` with the description of your feed.

# Compatibility

I've not extensively tested the plugin, but it's worked fine on the following installations.

- 2.0.4
