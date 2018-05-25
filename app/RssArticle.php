<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DOMDocument;
use SimpleXMLElement;
use Carbon\Carbon;
use App\Trend;

class RssArticle extends Model
{
    protected $table = 'rss_articles';

    const DEFAULT_STATUS = 0;
    
    const STATUS_PUBLIC = 1;
    /*
     * xml type
     */
    const TYPE_IS_RSS = 1; 
    
    const TYPE_IS_ATOM = 2;
    
    const TYPE_IS_RDF = 3;

    public function trends()
    {
        return $this->morphToMany('App\Trend', 'trendable');
    }

    /**
     * get og image
     */
    public function get_tag_og_image($url){
        $meta_og_img = null;
        //validate url live
        $rss = new Rss();
        if (!$validation_URL_live = $rss->is_url_live($url)) {
            return $meta_og_img;
        } else{
            $sites_html = file_get_contents($url);

            $html = new DOMDocument();
            @$html->loadHTML($sites_html);
            
            //Get all meta tags and loop through them.
            foreach($html->getElementsByTagName('meta') as $meta) {
                //If the property attribute of the meta tag is og:image
                if($meta->getAttribute('property')=='og:image'){ 
                    //Assign the value from content attribute to $meta_og_img
                    $meta_og_img = $meta->getAttribute('content');
                    break;
                }
            }
            if (!$validation_URL_img = $rss->is_url_live($meta_og_img)){
                $meta_og_img = null;
            } else{
                return $meta_og_img;
            }
        }
    }

    /**
     * action create rss
     */
    public function create_rss_article($id_rss, $type, $val_rss, $website_name) {
        $current_date = Carbon::now();
        $get_val = array();
        $get_val['title'] = null;
        $get_val['link'] = null;
        $get_val['description'] = null;
        $get_val['content'] = null;
        $get_val['pubDate'] = null;
        for ($i = 0; $i < count($val_rss); $i++) {
            switch ($type) {
                case self::TYPE_IS_RDF :
                    if ($this->search_key_arr('title', $val_rss[$i])) {
                        $get_val['title'] = $val_rss[$i]['title'];
                    }
                    if ($this->search_key_arr('link', $val_rss[$i])) {
                        $get_val['link'] = $val_rss[$i]['link'];
                    }
                    if ($this->search_key_arr('description', $val_rss[$i])) {
                        $get_val['description'] = $val_rss[$i]['description'];
                    }
                    if ($this->search_key_arr('pubDate', $val_rss[$i])) {
                        $get_val['pubDate'] = strtotime($val_rss[$i]['pubDate']);
                    }
                    $get_tag_img = $this->get_tag_img($get_val['description']);
                    if (!empty($get_tag_img)){
                        if (getimagesize($get_tag_img)){
                            $size = getimagesize($get_tag_img);
                            if (($size[0] > 20) && ($size[1] > 20) ){
                            $get_val['img'] = $get_tag_img;
                            } else{
                                $get_tag_og_img = $this->get_tag_og_image($get_val['link']);

                                if (!empty($get_tag_og_img)) {
                                    if (getimagesize($get_tag_og_img)) {
                                        $size = getimagesize($get_tag_og_img);
                                        if (($size[0] > 20) && ($size[1] > 20)) {
                                            $get_val['img'] = $get_tag_og_img;
                                        } else {
                                            $get_val['img'] = self::noimage();
                                        }
                                    } else {
                                        $get_val['img'] = self::noimage();
                                    }
                                } else {
                                    $get_val['img'] = self::noimage();
                                }
                            }
                        } else{
                            $get_val['img'] = self::noimage();                       
                        }
                    } else{
                        $get_tag_og_img = $this->get_tag_og_image($get_val['link']);
                        
                        if (!empty($get_tag_og_img)){
                            if (getimagesize($get_tag_og_img)) {
                                $size = getimagesize($get_tag_og_img);
                                if (($size[0] > 20) && ($size[1] > 20)) {
                                    $get_val['img'] = $get_tag_og_img;
                                } else {
                                    $get_val['img'] = self::noimage();
                                }
                            } else {
                                $get_val['img'] = self::noimage();
                            }
                        } else{
                            $get_val['img'] = self::noimage();
                        }
                    }
                    break;
                case self::TYPE_IS_RSS :
                    if ($this->search_key_arr('title', $val_rss[$i])) {
                        $get_val['title'] = $val_rss[$i]['title'];
                    }
                    if ($this->search_key_arr('link', $val_rss[$i])) {
                        $get_val['link'] = $val_rss[$i]['link'];
                    }
                    if ($this->search_key_arr('description', $val_rss[$i])) {
                        $get_val['description'] = $val_rss[$i]['description'];
                    }
                    if ($this->search_key_arr('content', $val_rss[$i])) {
                        if (!empty($val_rss[$i]['content'])){
                            $get_val['content'] = $val_rss[$i]['content'];
                        }
                    }
                    if ($this->search_key_arr('pubDate', $val_rss[$i])) {
                        $get_val['pubDate'] = date('Y-m-d', strtotime($val_rss[$i]['pubDate']));
                    }
                    $get_tag_img = $this->get_tag_img($get_val['content']);
                    if (!empty($get_tag_img)){
                        if (@getimagesize($get_tag_img)){
                            $size = getimagesize($get_tag_img);
                            if (($size[0] > 20) && ($size[1] > 20) ){
                            $get_val['img'] = $get_tag_img;
                            } else{
                                $get_val['img'] = self::noimage();
                            }
                        } else{
                            $get_val['img'] = self::noimage();
                        }
                    } else{
                        $get_tag_img_description = $this->get_tag_img($get_val['description']);
                        if (@getimagesize($get_tag_img_description)){
                            $size = getimagesize($get_tag_img_description);
                            if (($size[0] > 20) && ($size[1] > 20) ){
                            $get_val['img'] = $get_tag_img_description;
                            } else{
                                $get_val['img'] = self::noimage();
                            }
                        } else{
                            $get_val['img'] = self::noimage();
                        }
                    }
                    
                    if ($get_val['img'] == self::noimage()) {
                        $get_tag_og_img = $this->get_tag_og_image($get_val['link']);

                        if (!empty($get_tag_og_img)) {
                            if (getimagesize($get_tag_og_img)) {
                                $size = getimagesize($get_tag_og_img);
                                if (($size[0] > 20) && ($size[1] > 20)) {
                                    $get_val['img'] = $get_tag_og_img;
                                } else {
                                    $get_val['img'] = self::noimage();
                                }
                            } else {
                                $get_val['img'] = self::noimage();
                            }
                        } else {
                            $get_val['img'] = self::noimage();
                        }
                    }
                    break;
                case self::TYPE_IS_ATOM :
                    if ($this->search_key_arr('title', $val_rss[$i])) {
                        $get_val['title'] = $val_rss[$i]['title'];
                    }
                    
                    if (count($val_rss[$i]['link']) < 2){
                        if ($this->search_key_arr('@attributes', $val_rss[$i]['link'])) {
                            if ($this->search_key_arr('href', $val_rss[$i]['link']['@attributes'])) {
                                $get_val['link'] = $val_rss[$i]['link']['@attributes']['href'];
                            }
                        }
                    } else{
                        if ($this->search_key_arr('@attributes', $val_rss[$i]['link'][0])) {
                            if ($this->search_key_arr('href', $val_rss[$i]['link'][0]['@attributes'])) {
                                $get_val['link'] = $val_rss[$i]['link'][0]['@attributes']['href'];
                            }
                        }
                    }
                    
                    if ($this->search_key_arr('published', $val_rss[$i])) {
                        $get_val['pubDate'] = strtotime($val_rss[$i]['published']);
                    } else{
                        $get_val['pubDate'] = strtotime($current_date);
                    }
                    
                    if ($this->search_key_arr('summary', $val_rss[$i])) {
                        $get_val['description'] = $val_rss[$i]['summary'];
                    }
                    
                    if ($this->search_key_arr('content', $val_rss[$i])) {
                        if (!empty($val_rss[$i]['content'])){
                            $get_val['content'] = $val_rss[$i]['content'];
                        }
                    }
                    $get_tag_img = $this->get_tag_img($get_val['description']);
                    if (!empty($get_tag_img)){
                        if (getimagesize($get_tag_img)){
                            $size = getimagesize($get_tag_img);
                            if (($size[0] > 20) && ($size[1] > 20) ){
                            $get_val['img'] = $get_tag_img;
                            } else{
                                $get_tag_og_img = $this->get_tag_og_image($get_val['link']);

                                if (!empty($get_tag_og_img)) {
                                    if (getimagesize($get_tag_og_img)) {
                                        $size = getimagesize($get_tag_og_img);
                                        if (($size[0] > 20) && ($size[1] > 20)) {
                                            $get_val['img'] = $get_tag_og_img;
                                        } else {
                                            $get_val['img'] = self::noimage();
                                        }
                                    } else {
                                        $get_val['img'] = self::noimage();
                                    }
                                } else {
                                    $get_val['img'] = self::noimage();
                                }
                            }
                        } else{
                            $get_val['img'] = self::noimage();                       
                        }
                    } else{
                        $get_tag_og_img = $this->get_tag_og_image($get_val['link']);
                        
                        if (!empty($get_tag_og_img)){
                            if (getimagesize($get_tag_og_img)) {
                                $size = getimagesize($get_tag_og_img);
                                if (($size[0] > 20) && ($size[1] > 20)) {
                                    $get_val['img'] = $get_tag_og_img;
                                } else {
                                    $get_val['img'] = self::noimage();
                                }
                            } else {
                                $get_val['img'] = self::noimage();
                            }
                        } else{
                            $get_val['img'] = self::noimage();
                        }
                    }
                    break;
            }
            
            $check_rss_articles = RssArticle::check_rss_articles($get_val['link'], $id_rss);
            if (empty($check_rss_articles)){
                $get_val['description'] = $this->remove_tag_img_first($get_val['description']);
                // $get_val['description'] = $get_val['description'] ? : '';
                $rss_articles = new RssArticle();
                $rss_articles->status = self::DEFAULT_STATUS;
                $rss_articles->pub_date = $get_val['pubDate'] ? : $current_date;
                $rss_articles->website_name = $website_name;
                $rss_articles->website_url = $get_val['link'] ? : '';
                $rss_articles->title = $get_val['title'] ? : '';
                $rss_articles->headword = $get_val['description'] ? : '';
                $rss_articles->image = $get_val['img'];
                $rss_articles->thumb = $get_val['img'];
                $rss_articles->type_xml = $type;
                $rss_articles->rss_id = $id_rss;
                $rss_articles->save();

                // search all trend
                $trends = Trend::where('status', Trend::STATUS_ACTIVE)->get();
                $trend_ids = [];
                foreach($trends as $trend){
                    if ((strpos(strtolower($get_val['title']), strtolower($trend->content)) || strpos(strtolower($get_val['description']), strtolower($trend->content))) !== false) {
                        $trend_ids[] = $trend->id;
                    }
                }

                // save table trendables
                $rss_articles->trends()->sync($trend_ids);
            }

            
        }
        
        return true;
        
    }

    // check rss articles unique
    public function check_rss_articles($website_url, $rss_id){
        $rss_article = RssArticle::where('website_url', $website_url)
            ->where('rss_id', $rss_id)
            ->first();
        
        return $rss_article;
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

    // get tag img
    function get_tag_img($param) {
        preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $param, $output);
        
        if (!empty($output)){
            if (!empty($output[1])){
                return $output[1];
            }
        } else{
            return null;
        }
    }

    // remove tag img the first
    function remove_tag_img_first($param) {
        $replace = preg_replace("/<img[^>]+\>/i", "", $param,1);
        // remove tag a is null
        $replace = preg_replace("/<a[^>]*><\\/a[^>]*>/", "", $replace);
        
        if (!empty($replace)){
            return $replace;
        } else{
            return null;
        }
    }

    public static function noimage() {
        $noimage = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
        $noimage .= $_SERVER["HTTP_HOST"] . '/images/admin/new_default.png';
        return $noimage;
    }

}
