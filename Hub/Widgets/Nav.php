<?php

namespace Hub\Widgets;

use Hub\Base\Widget;

class Nav extends Widget
{

    protected $items = [];

    public function prepare($options = [])
    {
        if(isset($options["items"])){
            $this->items = $options["items"];
        }
    }

    public function execute()
    {
        return $this->toHtml();
    }

    public function toHtml()
    {
        $out = [];
        $out[] = "<div class='collapse navbar-collapse'>";
        $out[] = "<ul class='navbar-nav'>";
        foreach($this->items as $item){

            $attributes = [];

            if(isset($item["items"])){
                $attributes[] = 'data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"';
            }

            $attributes = implode($attributes);

            $dropdown = (isset($item["items"]) ? "dropdown" : "");

            $out[] = "<li class='nav-item $dropdown'>";

            $out[] = "<a class='nav-link' href='" . $item["link"] . "' " . $attributes . ">";

            $out[] = $item["label"];

            $out[] = "</a>";

            if(isset($item["items"])){
                $out[] = $this->createDropdown($item["items"]);
            }
            $out[] = "</li>";
        }
        $out[] = "</ul>";
        $out[] = "</div>";
        return implode($out);
    }

    public function createItem()
    {

    }

    public function createDropdown($items = [])
    {
        $out = [];

        $out[] = "<div class='dropdown-menu'>";
        foreach($items as $item){

            $attributes = [];

            if(isset($item["items"])){
                $attributes[] = 'data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"';
            }

            $attributes = implode($attributes);

            $out[] = "<a class='dropdown-item' href='" . $item["link"] . "' " . $attributes . ">";

            $out[] = $item["label"];

            $out[] = "</a>";

            if(isset($item["items"])){
                $out[] = $this->createDropdown($item["items"]);
            }
        }
        $out[] = "</div>";

        return implode($out);
    }

    // public function toHtml()
    // {
    //     $out = [];
    //     $out[] = "<ul class='nav nav-default'>";
    //     $out[] = $this->createItems($this->items);
    //     $out[] = "</ul>";
    //
    //     return implode($out);
    // }
    //
    // public function createItems($items = [])
    // {
    //     $out = [];
    //     foreach($items as $item){
    //         $out[] = $this->createItem($item);
    //     }
    //
    //     return implode($out);
    // }
    //
    // public function createItem($item = [])
    // {
    //     $out = [];
    //     $out[] = "<li>";
    //
    //     if(isset($item["link"])){
    //         $out[] = "<a href='" . $item["link"] . "'>";
    //     }
    //
    //     $out[] = "<span>" . $item["label"] . "</span>";
    //
    //     if(isset($item["items"])){
    //         $out[] = "<ul>";
    //         $out[] = $this->createItems($item["items"]);
    //         $out[] = "</ul>";
    //     }
    //
    //     if(isset($item["link"])){
    //         $out[] = "</a>";
    //     }
    //     $out[] = "</li>";
    //
    //     return implode($out);
    // }
}
