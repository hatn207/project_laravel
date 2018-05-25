<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DOMDocument;
use SimpleXMLElement;
use Carbon\Carbon;

class Rss extends Model
{
    protected $table = 'rss';

    const STATUS_ACTIVE = 1;
    
    const STATUS_DELETE = 0;
    
    const ACTION_CREATE = 1;
    
    const ACTION_UPDATE = 2;
    /*
     * xml type
     */
    const TYPE_IS_RSS = 1; 
    
    const TYPE_IS_ATOM = 2;
    
    const TYPE_IS_RDF = 3;

    /**
     * action create rss
     */
    public function create_rss($param, $action) {
        $current_date = Carbon::now();

        $val_rss = array();
        $feed_string = $param['feed'];
        
        switch ($param['type']) {
            case self::TYPE_IS_RDF :               
                $doc = new DOMDocument('1.0', 'utf-8');
                $doc->loadXML($feed_string);
                if ($doc->getElementsByTagName('channel')->length > 0) {
                    foreach ($doc->getElementsByTagName('channel') as $node) {
                        if ($node->getElementsByTagName('title')->length) {
                            $val_rss['title'] = $node->getElementsByTagName('title')->item(0)->nodeValue;
                        }
                    }
                }
                
                $get_item = $this->get_item_is_rdf($feed_string);
                $val_rss['item'] = $get_item;
                break;
            case self::TYPE_IS_RSS :
                $doc = new DOMDocument('1.0', 'utf-8');
                $doc->loadXML($feed_string);
                if ($doc->getElementsByTagName('channel')->length > 0) {
                    foreach ($doc->getElementsByTagName('channel') as $node) {
                        if ($node->getElementsByTagName('title')->length) {
                            $val_rss['title'] = $node->getElementsByTagName('title')->item(0)->nodeValue;
                        }
                    }
                }
                
                $get_item = $this->get_item_is_rss($feed_string);
                $val_rss['item'] = $get_item;
                
                break;
            case self::TYPE_IS_ATOM :
                $doc = new DOMDocument('1.0', 'utf-8');
                $doc->loadXML($feed_string);
                if ($doc->getElementsByTagName('title')->length > 0) {
                    $val_rss['title'] = $node->getElementsByTagName('title')->item(0)->nodeValue;
                }
                if ($doc->getElementsByTagName('entry')->length > 0) {
                    $val_rss['item'] = $node->getElementsByTagName('entry')->item(0)->nodeValue;
                }
                break;
        }

        $website_url = $param['website_url'];
        $strlen = strlen($website_url);
        if ($website_url[$strlen - 1] == '/') {
            $website_url = substr($website_url, 0, $strlen - 1);
        }
        
        $check_url = Rss::check_website_url($website_url);
        if (!empty($check_url) && $action == self::ACTION_CREATE) {
            try {
                $rss = Rss::find($check_url);
                $rss->status = self::STATUS_ACTIVE;
                $rss->save();

                return true;
            } catch (Exception $ex) {
                return false;
            }
        } else{
            try {
                if ($action == self::ACTION_UPDATE) {
                    $rss_articles = new RssArticle();
                    $rss_articles->create_rss_article($param['rss_id'], $param['type'], $val_rss['item'], $param['title']);
                } else{
                    $rss = new Rss();
                    $rss->status = self::STATUS_ACTIVE;
                    $rss->title = $val_rss['title'];
                    $rss->website_url = $website_url;
                    $rss->type = $param['type'];
                    $rss->save();

                    $rss_articles = new RssArticle();
                    
                    return $rss_articles->create_rss_article($rss->id, $param['type'], $val_rss['item'], $rss->title);
                }
                
                return true;
            } catch (Exception $ex) {
                return false;
            }
        }
        
    }

    // check website url
    function check_website_url($val){
        $rss = Rss::where('website_url', $val)->orWhere('status', self::STATUS_DELETE)->first();

        if (!empty($rss)){
            return $rss->id;
        } else{
            return null;
        }
    }

    // delete rss
    function delete_rss($id){
        $rss = Rss::find($id);
        $rss->status = self::STATUS_DELETE;
        $rss->save();
        
        return true;
    }

    // get content type is rss
    function get_item_is_rss($feed_string){
        $current_date = Carbon::now();
        $doc = new DOMDocument('1.0', 'utf-8');
        $doc->loadXML($feed_string);
        $feed = array();
        foreach ($doc->getElementsByTagName('item') as $node) {
            if ($node->getElementsByTagName('encoded')->length > 0) {
                $encoded = $node->getElementsByTagName('encoded')->item(0)->nodeValue;
            } else {
                $encoded = null;
            }
            if ($node->getElementsByTagName('pubDate')->length > 0) {
                $pub_date = $node->getElementsByTagName('pubDate')->item(0)->nodeValue;
            } else {
                $pub_date = strtotime($current_date);
            }
            if ($node->getElementsByTagName('description')->length > 0) {
                $description = $node->getElementsByTagName('description')->item(0)->nodeValue;
            } else {
                $description = null;
            }
            if ($node->getElementsByTagName('link')->length > 0) {
                $link = $node->getElementsByTagName('link')->item(0)->nodeValue;
            } else {
                $link = null;
            }
            if ($node->getElementsByTagName('title')->length > 0) {
                $title = $node->getElementsByTagName('title')->item(0)->nodeValue;
            } else {
                $title = null;
            }
            $item = array(
                'title' => $title,
                'link' => $link,
                'description' => $description,
                'content' => $encoded,
                'pubDate' => $pub_date
            );
            array_push($feed, $item);
        }
        return $feed;
    }
    
    // get content type is rdf
    function get_item_is_rdf($feed_string){
        $current_date = Carbon::now();
        $doc = new DOMDocument('1.0', 'utf-8');
        $doc->loadXML($feed_string);
        $feed = array();
        foreach ($doc->getElementsByTagName('item') as $node) {
            if ($node->getElementsByTagName('date')->length > 0) {
                $pub_date = $node->getElementsByTagName('date')->item(0)->nodeValue;
            } else {
                $pub_date = strtotime($current_date);
            }
            if ($node->getElementsByTagName('description')->length > 0) {
                $description = $node->getElementsByTagName('description')->item(0)->nodeValue;
            } else {
                $description = null;
            }
            if ($node->getElementsByTagName('link')->length > 0) {
                $link = $node->getElementsByTagName('link')->item(0)->nodeValue;
            } else {
                $link = null;
            }
            if ($node->getElementsByTagName('title')->length > 0) {
                $title = $node->getElementsByTagName('title')->item(0)->nodeValue;
            } else {
                $title = null;
            }

            $item = array(
                'title' => $title,
                'link' => $link,
                'description' => $description,
                'pubDate' => $pub_date
            );
            array_push($feed, $item);
        }
        return $feed;
    }

    //validate url live
    public function is_url_live($array) {
        $isLive = array();
        if (@get_headers($array, 1) !== false) {
            $headers = get_headers($array, 1);
            $isLive = array_filter($headers, function ($value) {
                if ($value == 'HTTP/1.1 200 OK' or $value == 'HTTP/1.0 200 OK') {
                    return $value;
                }
            });
        }

        if (!empty($isLive)) {
            return true;
        } else {
            return false;
        }
    }

    //validate XML
    public function is_xml($xml) {
        libxml_use_internal_errors(true);

        $doc = new DOMDocument('1.0', 'utf-8');
        $doc->loadXML($xml);

        $errors = libxml_get_errors();

        if (empty($errors)) {
            return true;
        } else {
            libxml_clear_errors();
            return false;
        }
    }
    
    // type is rdf 
    public function is_rdf($feedxml) {
        $feed = new SimpleXMLElement($feedxml);

        if ($feed->item && $feed->channel) {
            return true;
        } else {
            return false;
        }
    }
    
    // type is rss 
    function is_rss($feedxml) {
        $feed = new SimpleXMLElement($feedxml);

        if ($feed->channel->item) {
            return true;
        } else {
            return false;
        }
    }

    // type is atom
    function is_atom($feedxml) {
        $feed = new SimpleXMLElement($feedxml);

        if ($feed->entry) {
            return true;
        } else {
            return false;
        }
    }

    // search key in array
    function search_key_arr($key, $data) {
        $result = array_search($key, array_keys($data));

        if (is_bool($result) === true && !$result) {
            return false;
        } else {
            return true;
        }
    }
}
