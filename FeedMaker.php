<?php

/**
 * FeedMaker - basic RSS feed generator for Pico
 *
 * @author  Matt Barnard
 * @author  Markus Hermann
 * @link    https://github.com/hermannmarkus/Pico-FeedMaker
 * @license http://opensource.org/licenses/MIT The MIT License
 * @version 1.9
 */
final class FeedMaker extends AbstractPicoPlugin
{

    //variable declarations

    private $feedType = null;  // boolean to determine if the user has typed example.com/feed
    private $feedTitle = '';    // title of the feed will be the site title
    private $baseURL = '';      // this will hold the base url
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
            header("Content-Type: application/rss+xml; charset=UTF-8");

            $twig = $this->getTwig();
            $feedPages = array();

            foreach ($pages as $key => $page) {
                if (array_key_exists("date", $page)) {
                    $rawMD = $this->getPico()->prepareFileContent($page['raw_content']);
                    $rawContent = $this->getPico()->getParsedown()->parse($rawMD);
                    $page['content'] = $rawContent;
                    array_push($feedPages, $page);
                }
            }

            if ($this->feedType == "rss") {
                $content = $twig->render("/rss.twig", array(
                    "pages" => $feedPages,
                    "baseURL" => $this->baseURL,
                    "siteDescription" => $this->siteDescription,
                    "title" => $this->feedTitle,
                ));
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
}