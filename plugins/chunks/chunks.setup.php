<?php
/* ====================
[BEGIN_COT_EXT]
Code=chunks
Name=Chunks system
Category=development-maintenance
Description=Extender for template system to use admin editable chunks
Version=1.0.0
Date=2014-Sep-01
Author=Andrey Matsovkin
Copyright=Copyright (c) 2011-2014, Andrey Matsovkin
Notes=If&nbsp;your enjoy my&nbsp;plugin please consider donating to&nbsp;help support future developments. <b>Thanks!</b> <br /><a href="mailto:macik.spb@gmail.com">macik.spb@gmail.com</a>
Auth_guests=R1
Lock_guests=W2345A
Auth_members=RW1
Lock_members=2345
Recommends_modules=
Recommends_plugins=
Requires_modules=
Requires_plugins=
[END_COT_EXT]

[BEGIN_COT_EXT_CONFIG]
[END_COT_EXT_CONFIG]
==================== */

/**
 * Template chunks plugin for Cotonti CMF
 *
 * @package chunks
 * @author Andrey Matsovkin
 * @copyright Copyright (c) 2011-2014
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