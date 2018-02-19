<?php

namespace App\Http\Controllers;

use App\Category;
use App\Collection;
use App\Helper;
use App\Post;
use App\Product;
use App\Setting;
use App\Theme;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\LaravelLocalization;

class PagesController extends Controller
{
    public function index(){
        $settings = Setting::first();
        $theme = Theme::where('active', 1)->first();
        $collections = Collection::where('publish', 1)->where('parent', 0)->orderBy('order', 'ASC')->get();
        $posts = Post::where('publish', 1)->orderBy('publish_at', 'DESC')->get();
        $home = true;
        $translate = Helper::getHomeLink();
        return view('themes.'.$theme->slug.'.pages.home', compact('settings', 'theme', 'collections', 'posts', 'home', 'translate'));
    }

    public function collections($slug){
        $settings = Setting::first();
        $theme = Theme::where('active', 1)->first();
        $parent = Collection::whereTranslation('slug', $slug)->first();
        $collections = Collection::where('parent', $parent->id)->orderBy('order', 'ASC')->get();
        $home = false;
        $translate = Collection::getCollectionLink($slug);
        if(count($collections)>0){
            return view('themes.'.$theme->slug.'.pages.collections', compact('settings', 'theme', 'parent', 'collections', 'home', 'translate'));
        }else{
            $products = $parent->product()->where('publish', 1)->get();
            if(count($products)==0){
                $parent = Collection::whereTranslation('slug', 'kitchens')->first();
                $products = $parent->product()->where('publish', 1)->get();
            }
            return view('themes.'.$theme->slug.'.pages.product', compact('settings', 'theme', 'parent', 'products', 'home', 'translate'));
        }
    }

    public function parentCollections($slug1, $slug2){
        $settings = Setting::first();
        $theme = Theme::where('active', 1)->first();
        $parent = Collection::whereTranslation('slug', $slug2)->first();
        $collections = Collection::where('parent', $parent->id)->orderBy('order', 'ASC')->get();
        $home = false;
        $translate = Collection::getParentCollectionLink($slug1, $slug2);
        if(count($collections)>0){
            return view('themes.'.$theme->slug.'.pages.collections', compact('settings', 'theme', 'parent', 'collections', 'home', 'translate'));
        }else{
            $products = $parent->product()->where('publish', 1)->get();
            if(count($products)==0){
                $parent = Collection::whereTranslation('slug', 'kitchens')->first();
                $products = $parent->product()->where('publish', 1)->get();
            }
            return view('themes.'.$theme->slug.'.pages.product', compact('settings', 'theme', 'parent', 'products', 'home', 'translate'));
        }
    }

    public function contact(){
        $settings = Setting::first();
        $theme = Theme::where('active', 1)->first();
        $home = false;
        app()->getLocale() == 'en'? $translate = url('it/contatto') : $translate = url('en/contact');
        return view('themes.'.$theme->slug.'.pages.contact', compact('settings', 'theme', 'home', 'translate'));
    }

    public function corporate(){
        $settings = Setting::first();
        $theme = Theme::where('active', 1)->first();
        $home = false;
        if(app()->getLocale() == 'en'){
            $translate = url('it/azienda');
            return view('themes.'.$theme->slug.'.pages.corporate.corporate', compact('settings', 'theme', 'home', 'translate'));
        }else{
            $translate = url('en/corporate');
            return view('themes.'.$theme->slug.'.pages.corporate.azienda', compact('settings', 'theme', 'home', 'translate'));
        }
    }

    public function partnership(){
        $settings = Setting::first();
        $theme = Theme::where('active', 1)->first();
        $home = false;
        app()->getLocale() == 'en'? $translate = url('it/partner') : $translate = url('en/partnership');
        return view('themes.'.$theme->slug.'.pages.partnership', compact('settings', 'theme', 'home', 'translate'));
    }

    public function news(){
        $settings = Setting::first();
        $theme = Theme::where('active', 1)->first();
        $home = false;
        $post = Post::first();
        $related = Post::where('id', '<>', $post->id)->where('publish', 1)->orderBy('publish_at', 'DESC')->paginate(6);
        $translate = Post::getPostLink($post);
        return view('themes.'.$theme->slug.'.pages.post', compact('settings', 'theme', 'home', 'related', 'post', 'translate'));
    }

    public function post($slug1, $slug2, $id){
        $settings = Setting::first();
        $theme = Theme::where('active', 1)->first();
        $home = false;
        $post = Post::find($id);
        $related = Post::where('id', '<>', $post->id)->where('publish', 1)->orderBy('publish_at', 'DESC')->paginate(6);
        $translate = Post::getPostLink($post);
        return view('themes.'.$theme->slug.'.pages.post', compact('settings', 'theme', 'home', 'related', 'post', 'translate'));
    }

    public function promotions(){
        $settings = Setting::first();
        $theme = Theme::where('active', 1)->first();
        $home = false;
        app()->getLocale() == 'en'? $translate = url('it/promozioni') : $translate = url('en/promotions');
        return view('themes.'.$theme->slug.'.pages.promotions', compact('settings', 'theme', 'home', 'translate'));
    }

    public function proba(){
        /*$old = Post::first();

        for ($i=1;$i<=20;$i++){
            $new = new Post();
            $new->user_id = $old->user_id;
            $new->category_id = $old->category_id;
            $new->image = $old->image;
            $new->title = 'News post ' . $i;
            $new->slug = str_slug('News post ' . $i);
            $new->short = $old->short;
            $new->body = $old->body;
            $new->publish_at = Carbon::now();
            $new->publish = 1;
            $new->save();

            sleep(1);
        }*/
        return 'done';
    }
}
