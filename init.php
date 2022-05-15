<?php
// Modifyed based on andyt's version (https://tt-rss.org/oldforum/viewtopic.php?f=22&t=1401)
class Instapaper extends Plugin
{

    private $host;


    public function about()
    {
        return array(
                1.0,
                'Share via Instapaper',
                'andyt',
               );

    }//end about()


    public function init($host)
    {
        $this->host = $host;

        $host->add_hook($host::HOOK_ARTICLE_BUTTON, $this);

    }//end init()


    public function get_js()
    {
        return file_get_contents(__DIR__.'/instapaper.js');
    }//end get_js()

    function get_css() {
        return file_get_contents(__DIR__ . "/instapaper.css");
    }


    public function hook_article_button($line)
    {
        return "<i class='icon-instapaper' onclick='shareArticleToInstapaper({$line["id"]})' style='cursor : pointer' title=\"".__('Read it Later')."\">Instapaper</i>";
    }//end hook_article_button()

    public function getInfo()
    {
        $id = $_REQUEST['id'];

        $st = $this->pdo->prepare(
            '
            SELECT title, link FROM ttrss_entries, ttrss_user_entries
            WHERE id=? and ref_id=id AND owner_uid=?
        '
        );

        $st->execute([$id, $_SESSION['uid']]);
        if ($result = $st->fetch()) {
            $title = truncate_string(strip_tags($result['title']), 100, '...');
            $link  = $result['link'];

            print json_encode(
                array(
                 'title' => $title,
                 'link'  => $link,
                 'id'    => $id,
                 'uid'   => $_SESSION['uid'],
                )
            );
        } else {
            print json_encode(false);
        }

    }//end getInfo()


    public function api_version()
    {
        return 2;

    }//end api_version()


}//end class
