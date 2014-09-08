<?php
/* ====================
[BEGIN_COT_EXT]
Code=chunks
Name=Chunks system
Category=development-maintenance
Description=Extender for template system to use admin editable chunks
Version=1.1.0-alpha
Date=2014-Sep-08
Author=Andrey Matsovkin & Dr2005alex
Copyright=Copyright (c) 2014 Cotonti team
Notes=
Auth_guests=R1
Lock_guests=W2345A
Auth_members=RW1
Lock_members=2345
Recommends_modules=
Recommends_plugins=slots
Requires_modules=
Requires_plugins=
[END_COT_EXT]

[BEGIN_COT_EXT_CONFIG]
allowed_loops=01:string:0:0:Allowed same tag recursion loops
throw_exception=02:radio:0,1:0:Always throw exception on chunk parsing errors
[END_COT_EXT_CONFIG]
==================== */

/**
 * Template chunks plugin for Cotonti CMF
 *
 * @package chunks
 * Author=Andrey Matsovkin & Dr2005alex
 * Copyright=Copyright (c) 2014 Cotonti team
 * @license Distributed under BSD license.
 * Made with «Extension Template» (https://github.com/macik/cot-extension_template)

 *
Allowed Var types:
var1=11:select:0,1,2,3,4,5,6:3:Description
var2=12:radio:0,1:1:Enable this
var3=13:string::test:Test string
var4=14:callback:cot_get_editors():markitup:Simple callback
var5=15:separator:::Separator
var6=16:range:0,5:1:Range
var7=17:text:0,5:1,2:Text
var8=18:custom:user_func():def_value:Description
 *
 */

defined('COT_CODE') or die('Wrong URL.');