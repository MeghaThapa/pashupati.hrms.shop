<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeSettingsContoller extends Controller
{
    public function settings(Request $request) {

        if($request->mode) {
            $this->modeChange();
        }
        if($request->fixedNav) {
            $this->navbarFixedTop();
        }
        if($request->borderBtm) {
            $this->isBorderBtm();
        }
        if($request->collapsedSidebar) {
            $this->collapsedSidebar();
        }

        if($request->fixedSidebar) {
            $this->fixedSidebar();
        }

        if($request->darkSidebar) {
            $this->darkSidebar();
        }

    }


    // DARK OR LIGHT MODE
    static function modeChange(){
        if (session()->has('isDark')) {
            session()->put('isDark', !session('isDark'));
        }
        else {
            //provide an initial value of isDark
            session()->put('isDark', true);
        }
        return true;
    }


    // NAVBAR FIXED TOP
    static function navbarFixedTop(){
        if (session()->has('isNavFixed')) {
            session()->put('isNavFixed', !session('isNavFixed'));
        }
        else {
            //provide an initial value of isNavFixed
            session()->put('isNavFixed', true);
        }
        return true;
    }

    // BORDER BOTTOM NONE
    static function isBorderBtm(){
        if (session()->has('isBorderBtm')) {
            session()->put('isBorderBtm', !session('isBorderBtm'));
        }
        else {
            //provide an initial value of isNavFixed
            session()->put('isBorderBtm', true);
        }
        return true;
    }


    // BORDER BOTTOM NONE
    static function collapsedSidebar(){
        if (session()->has('isSidebarCollapsed')) {
            session()->put('isSidebarCollapsed', !session('isSidebarCollapsed'));
        }
        else {
            //provide an initial value of isNavFixed
            session()->put('isSidebarCollapsed', true);
        }
        return true;
    }

    // BORDER BOTTOM NONE
    static function fixedSidebar(){
        if (session()->has('isSidebarFixed')) {
            session()->put('isSidebarFixed', !session('isSidebarFixed'));
        }
        else {
            //provide an initial value of isNavFixed
            session()->put('isSidebarFixed', true);
        }
        return true;
    }

    // BORDER BOTTOM NONE
    static function darkSidebar(){
        if (session()->has('isSidebarDark')) {
            session()->put('isSidebarDark', !session('isSidebarDark'));
        }
        else {
            //provide an initial value of isNavFixed
            session()->put('isSidebarDark', true);
        }
        return true;
    }
}
