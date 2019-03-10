<?php

/**
 * FeedMaker - basic RSS feed generator for Pico
 *
 * @author  Matt Barnard
 * @author  Markus Hermann
 * @link    https://github.com/hermannmarkus/Pico-FeedMaker
 * @license http://opensource.org/licenses/MIT The MIT License
 * @version 2.0
 */
final class FeedMaker extends AbstractPicoPlugin
{

    //variable declarations

    private $feedType = null;
    private $feedTitle = '';
    private $baseURL = '';
    /**
     * This plugin is enabled by default?
     *
     * @see AbstractPicoPlugin::$enabled
     * @var boolean
     */
    protected $enabled = false;

    protected $twig = null;

    /**
     * Triggered after Pico has loaded all available plugins
     *
     * This event is triggered nevertheless the plugin is enabled or not.
     * It is NOT guaranteed that plugin dependencies are fulfilled!
     *
     * @see    Pico::getPlugin()
     * @see    Pico::getPlugins()
     * @param  object[] &$plugins loaded plugin instances
     * @return void
     */

    public function onConfigLoaded(array &$config)
    {
        // Get site data
        $this->feedTitle = $config['site_title'];
        $this->siteDescription = $config['FeedMaker.site_description'];
        $this->baseURL = $config['base_url'];
        $this->dateFormat = $config['FeedMaker.date_format'];
    }

    /**
     * Triggered after Pico has evaluated the request URL
     *
     * @see    Pico::getRequestUrl()
     * @param  string &$url part of the URL describing the requested contents
     * @return void
     */
    public function onRequestUrl(&$url)
    {
        // If example.com/feed, then true
        if ($url == 'feed.rss') {
            $this->feedType = "rss";
        }

        if ($url == 'feed.json') {
            $this->feedType = "json";
        }

        if ($url == 'microblog.json') {
            $this->feedType = "microblog";
        }
    }


    public function onPagesLoaded(
        array &$pages,
        array &$currentPage = null,
        array &$previousPage = null,
        array &$nextPage = null
    )
    {
        // If this is the feed link, return RSS feed
        if ($this->feedType != null) {
            //Sitemap found, 200 OK
            header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
            $content = "";

            $twig = $this->getTwig();
            $feedPages = array();

            foreach ($pages as $key => $page) {
                if (array_key_exists("date", $page) && $page['date'] != "") {
                    $rawMD = $this->getPico()->prepareFileContent($page['raw_content']);
                    $rawContent = $this->getPico()->getParsedown()->parse($rawMD);
                    $page['content'] = $rawContent;
                    array_push($feedPages, $page);
                }
            }

            if ($this->feedType == "rss") {
                header("Content-Type: application/rss+xml; charset=UTF-8");
                $content = $twig->render("/rss.twig", array(
                    "pages" => $feedPages,
                    "baseURL" => $this->baseURL,
                    "siteDescription" => $this->siteDescription,
                    "title" => $this->feedTitle,
                ));
            }

            if ($this->feedType == "json") {
                header("Content-Type: application/json; charset=UTF-8");
                $content = $this->getJSONFeedContent($feedPages);
            }

            if ($this->feedType == "microblog") {
                header("Content-Type: application/json; charset=UTF-8");

                foreach ($feedPages as $key => $page) {
                    if ($page['meta']['template'] != "microblog") {
                        unset($feedPages[$key]);
                    }
                }

                $content = $this->getJSONFeedContent($feedPages, "microblog.json");
            }

            die($content);
        }
    }

    /**
     * Return an instance of the Twig_Environment so templates can be rendered
     *
     * @return Twig_Environment Object
     */
    protected function getTwig()
    {
        if ($this->twig === null) {
            $twigConfig = $this->getConfig('twig_config');
            $twigLoader = new Twig_Loader_Filesystem("plugins/FeedMaker/content");
            $this->twig = new Twig_Environment($twigLoader, $twigConfig);
        }

        return $this->twig;
    }

    /**
     * Return the json feed for given pages
     *
     * @param $pages An array pages
     * @param $feedName The name of the feed
     *
     * @return string
     */
    private function getJSONFeedContent($feedPages, $feedName = "feed.json")
    {
        $pages = array();

        foreach ($feedPages as $key => $page) {
            $date = new DateTime($page['date']);

            array_push($pages, array(
                "id" => $page['url'],
                "content_html" => $page['content'],
                "url" => $page['url'],
                "date_published" => $date->format("Y-m-d\TH:i:s+00:00")
            ));
        }

        $content = $this->getTwig()->render("/json.twig", array(
            "content" => $pages,
            "baseURL" => $this->baseURL,
            "siteDescription" => $this->siteDescription,
            "title" => $this->feedTitle,
            "feedName" => $feedName,
        ));

        return $content;
    }
}
